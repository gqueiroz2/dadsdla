<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use App\pAndR;
use App\brand;
use App\salesRep;
use App\base;
use App\sql;
use App\pRate;
use App\AE;
use App\performance;
use App\forecastBase;

class baseReportPandR extends pAndR
{
    public function baseLoadReport($con, $r, $pr, $cYear, $pYear, $regionID, $salesRepID, $currencyID, $value, $baseReport)
    {

        $sr = new salesRep();
        $br = new brand();
        $base = new base();
        $sql = new sql();
        $reg = new region();
        $fb = new forecastBase();

        $actualMonth = date('n');

        $data = date('Y-m-d');

        $week = $this->weekOfMonth($data);

        if (is_array($salesRepID)) {
            $salesRepIDString = implode(",", $salesRepID);
        }

        if ($salesRepID) {
            $salesRepIDString = "";
            for ($i = 0; $i < sizeof($salesRepID); $i++) {
                if ($i == 0) {
                    $salesRepIDString .= "'" . $salesRepID[$i] . "'";
                } else {
                    $salesRepIDString .= ",'" . $salesRepID[$i] . "'";
                }
            }
        }

        $list = $this->listByAEMult($con, $sql, $salesRepID, $cYear, $regionID, $salesRepIDString, $baseReport);


        if (sizeof($list) == 0) {
            return false;
        }

        $regionName = $reg->getRegion($con, array($regionID))[0]['name'];

        $salesRep = $sr->getSalesRepById($con, $salesRepID);

        $brand = $br->getBrandBinary($con);
        $month = $base->getMonth();

        $tmp = array($cYear);
        //valor da moeda para divisões
        $div = $base->generateDiv($con, $pr, $regionID, $tmp, $currencyID);

        //nome da moeda pra view
        $tmp = array($currencyID);
        $currency = $pr->getCurrency($con, $tmp)[0]["name"];

        //$readable = $this->monthAnalise($base);

        if ($regionName == "Brazil") {
            $splitted = $this->isItSplitted($con, $sql, $salesRepID, $list, $cYear, $pYear);
        } else {
            $splitted = false;
        }

        $revenueShares = $this->revenueShares($con, $sql, $regionID, $pYear, $month, $this->generateColumns($value, "ytd"), $value);

        $brandsValueLastYear = $this->lastYearBrand($con, $sql, $pr, $br->getBrand($con), ($pYear), $value, $currency, $regionID, $currencyID);

        for ($l = 0; $l < sizeof($list); $l++) {
            for ($m = 0; $m < sizeof($month); $m++) {
                $lastYearRevenue[$l][$m] = $this->generateValuePandR($con, $sql, 'revenue', $baseReport, $regionID, $pYear, $month[$m][1], $list[$l], $this->generateColumns($value, "ytd"), $value) * $div;
            }
            $lastYearRevenue[$l] = $this->addQuartersAndTotalOnArray(array($lastYearRevenue[$l]))[0];
        }



        //var_dump($rollingFCST);
        for ($l = 0; $l < sizeof($list); $l++) {
            for ($m = 0; $m < sizeof($month); $m++) {

                //$rollingFCST[$l][$m] = $this->generateForecast($con,$sql,$baseReport,$regionID,$cYear,$month[$m][1],$list[$l],$this->generateColumns($value,"crm"),$value,$revenueShares,$br,$brandsValueLastYear, $fb,$lastYearRevenue,$splitted)*$div;

                $lastYear[$l][$m] = $this->generateValuePandR($con, $sql, 'revenue', $baseReport, $regionID, $pYear, $month[$m][1], $list[$l], $this->generateColumns($value, "ytd"), $value) * $div;

                $targetValues[$l][$m] = $this->generateValuePandR($con, $sql, 'target', $baseReport, $regionID, $cYear, $month[$m][1], $list[$l], "value", $value) * $div;

                $bookings[$l][$m] = $this->generateValuePandR($con, $sql, 'revenue', $baseReport, $regionID, $cYear, $month[$m][1], $list[$l], $this->generateColumns($value, "ytd"), $value) * $div;
            }

            $cont = $l;
            $rollingFCST[$l] = $this->generateForecast($con, $sql, $baseReport, $regionID, $cYear, $list[$l], $this->generateColumns($value, "crm"), $value, $revenueShares, $br, $brandsValueLastYear, $fb, $lastYearRevenue, $splitted, $cont);

            //$rollingFCST[$l] = $this->addQuartersAndTotalOnArray(array($rollingFCST[$l]))[0];
            //var_dump($rollingFCST);
            $lastYear[$l] = $this->addQuartersAndTotalOnArray(array($lastYear[$l]))[0];
            //var_dump($lastYear);

            $targetValues[$l] = $this->addQuartersAndTotalOnArray(array($targetValues[$l]))[0];
            $bookings[$l] = $this->addQuartersAndTotalOnArray(array($bookings[$l]))[0];

            $rollingFCST = $this->addFcstWithBooking($bookings,$rollingFCST);//with bookings value 
            //var_dump($rollingFCST[$l]);
            $rollingFCST[$l] = $this->addQuartersAndTotalOnArray(array($rollingFCST[$l]))[0];

            $rfVsCurrent[$l] = $this->subArrays($rollingFCST[$l], $bookings[$l]);
        }
        //var_dump($rollingFCST);
        //$rollingFCST = $this->addQuartersAndTotalRF($rollingFCST);
        //var_dump($rollingFCST);

        /*
        for ($b=0; $b < sizeof($bookings); $b++) { 
            var_dump($b);
            var_dump($list[$b]);
            var_dump($bookings[$b]);            
        } 
        */


        $rollingFCSTTT = $this->mergeList($rollingFCST, $list);
        $lastYearTT = $this->mergeList($lastYear, $list);
        $targetValuesTT = $this->mergeList($targetValues, $list);
        $bookingsTT = $this->mergeList($bookings, $list);

        $pendingTT = $this->subArrays($rollingFCSTTT, $bookingsTT);
        $rfVsTargetTT = $this->subArrays($rollingFCSTTT, $targetValuesTT);
        $targetAchievement = $this->divArrays($rollingFCSTTT, $targetValuesTT);


        $currencyName = $pr->getCurrency($con, array($currencyID))[0]['name'];

        if ($value == 'gross') {
            $valueView = 'Gross';
        } elseif ($value == 'net') {
            $valueView = 'Net';
        } else {
            $valueView = 'Net Net';
        }

        $rtr = array(
            "cYear" => $cYear,
            "pYear" => $pYear,
            "list" => $list,
            "rollingFCST" => $rollingFCST,
            "targetValues" => $targetValues,
            "lastYear" => $lastYear,
            "bookings" => $bookings,
            "rfVsCurrent" => $rfVsCurrent,

            "rollingFCSTTT" => $rollingFCSTTT,
            "targetValuesTT" => $targetValuesTT,
            "lastYearTT" => $lastYearTT,
            "bookingsTT" => $bookingsTT,
            "pendingTT" => $pendingTT,
            "rfVsTargetTT" => $rfVsTargetTT,
            "targetAchievement" => $targetAchievement,

            "currency" => $currency,
            "value" => $value,
            "region" => $regionID,
            "currencyName" => $currencyName,
            "valueView" => $valueView,
            "currency" => $currencyName,
            "value" => $valueView

        );

        return $rtr;
    }

    public function listByAEMult($con, $sql, $salesRepID, $cYear, $regionID, $salesRepIDString, $baseReport)
    {
        $date = date('n') - 1;

        switch ($baseReport) {
            case 'brand':

                $sf = "SELECT DISTINCT s.brand_id AS 'brandID',
        					  b.name AS 'brand'
        		            FROM sf_pr s
                            LEFT JOIN brand b ON b.ID = s.brand_id
                            WHERE ((s.sales_rep_owner_id IN ($salesRepIDString)) OR (s.sales_rep_splitter_id IN ($salesRepIDString)))
                            AND ( s.region_id = \"" . $regionID . "\") AND ( s.stage != \"6\") AND ( s.stage != \"5\") AND ( s.stage != \"7\")
                            AND (s.year_from = \"$cYear\") AND (s.from_date > \"$date\")
                            ORDER BY 1
                        ";

                $resSF = $con->query($sf);
                $from = array("brandID", "brand");
                $listSF = $sql->fetch($resSF, $from, $from);

                $ytd = "SELECT DISTINCT y.brand_id AS 'brandID',
                               b.name AS 'brand'
                            FROM ytd y
                            LEFT JOIN brand b ON b.ID = sf.brand_id
                            WHERE (y.sales_rep_id IN ($salesRepIDString) )
                            AND (y.year = \"$cYear\" )
                            AND (r.ID = \"" . $regionID . "\")
                            ORDER BY 1
                       ";

                $resYTD = $con->query($ytd);
                $from =  array("brandID", "brand");
                $listYTD = $sql->fetch($resYTD, $from, $from);
                $count = 0;

                break;

            case 'client':
                //GET FROM SALES FORCE
                $sf = "SELECT DISTINCT c.name AS 'clientName',
		    				   c.ID AS 'clientID',
		                       a.ID AS 'agencyID',
		                       a.name AS 'agencyName'
		    				FROM sf_pr s
		                    LEFT JOIN client c ON c.ID = s.client_id
		    				LEFT JOIN agency a ON a.ID = s.agency_id
		    				WHERE ((s.sales_rep_owner_id IN ($salesRepIDString)) OR (s.sales_rep_splitter_id IN ($salesRepIDString)))
		                    AND ( s.region_id = \"" . $regionID . "\") AND ( s.stage != \"6\") AND ( s.stage != \"5\") AND ( s.stage != \"7\")
		                    AND (s.year_from = \"$cYear\") AND (s.from_date > \"$date\")
		    				ORDER BY 1
		    	       ";
                $resSF = $con->query($sf);
                $from = array("clientName", "clientID", "agencyID", "agencyName");
                $listSF = $sql->fetch($resSF, $from, $from);

                //GET FROM IBMS/BTS
                $ytd = "SELECT DISTINCT c.name AS 'clientName',
		    				   c.ID AS 'clientID',
		                       a.ID AS 'agencyID',
		                       a.name AS 'agencyName'
		    				FROM ytd y
		    				LEFT JOIN client c ON c.ID = y.client_id
		                    LEFT JOIN region r ON r.ID = y.sales_representant_office_id
		                    LEFT JOIN agency a ON a.ID = y.agency_id
		    				WHERE (y.sales_rep_id IN ($salesRepIDString) )
		    				AND (y.year = \"$cYear\" )
		                    AND (r.ID = \"" . $regionID . "\")
		    				ORDER BY 1
		    	       ";

                $resYTD = $con->query($ytd);
                $from = array("clientName", "clientID", "agencyID", "agencyName");
                $listYTD = $sql->fetch($resYTD, $from, $from);
                $count = 0;
                break;

            case 'agency':
                //GET FROM SALES FORCE
                $sf = "SELECT DISTINCT 
		                       a.ID AS 'agencyID',
		                       a.name AS 'agencyName'
		    				FROM sf_pr s
		                    LEFT JOIN client c ON c.ID = s.client_id
		    				LEFT JOIN agency a ON a.ID = s.agency_id
		    				WHERE ((s.sales_rep_owner_id IN ($salesRepIDString)) OR (s.sales_rep_splitter_id IN ($salesRepIDString)))
		                    AND ( s.region_id = \"" . $regionID . "\") AND ( s.stage != \"6\") AND ( s.stage != \"5\") AND ( s.stage != \"7\")
		                    AND (s.year_from = \"$cYear\") AND (s.from_date > \"$date\")
		    				ORDER BY 1
		    	       ";
                $resSF = $con->query($sf);
                $from = array("agencyID", "agencyName");
                $listSF = $sql->fetch($resSF, $from, $from);

                //GET FROM IBMS/BTS
                $ytd = "SELECT DISTINCT 
		                       a.ID AS 'agencyID',
		                       a.name AS 'agencyName'
		    				FROM ytd y
		    				LEFT JOIN client c ON c.ID = y.client_id
		                    LEFT JOIN region r ON r.ID = y.sales_representant_office_id
		                    LEFT JOIN agency a ON a.ID = y.agency_id
		    				WHERE (y.sales_rep_id IN ($salesRepIDString) )
		    				AND (y.year = \"$cYear\" )
		                    AND (r.ID = \"" . $regionID . "\")
		    				ORDER BY 1
		    	       ";

                $resYTD = $con->query($ytd);
                $from = array("agencyID", "agencyName");
                $listYTD = $sql->fetch($resYTD, $from, $from);
                $count = 0;
                break;

            case 'agencyGroup':
                //GET FROM SALES FORCE
                $sf = "SELECT DISTINCT 
		    				   ag.name AS 'agencyGroup',
		                       ag.ID AS 'agencyGroupID'		                       
		    				FROM sf_pr s
		                    LEFT JOIN client c ON c.ID = s.client_id
		    				LEFT JOIN agency a ON a.ID = s.agency_id
		    				LEFT JOIN agency_group ag ON a.agency_group_id = ag.ID
		    				WHERE ((s.sales_rep_owner_id IN ($salesRepIDString)) OR (s.sales_rep_splitter_id IN ($salesRepIDString)))
		                    AND ( s.region_id = \"" . $regionID . "\") AND ( s.stage != \"6\") AND ( s.stage != \"5\") AND ( s.stage != \"7\")
		                    AND (s.year_from = \"$cYear\") AND (s.from_date > \"$date\")
		    				ORDER BY 1
		    	       ";
                $resSF = $con->query($sf);
                $from = array("agencyGroup", "agencyGroupID");
                $listSF = $sql->fetch($resSF, $from, $from);

                //GET FROM IBMS/BTS
                $ytd = "SELECT DISTINCT 
		    				   ag.name AS 'agencyGroup',
		                       ag.ID AS 'agencyGroupID'			                       
		    				FROM ytd y
		    				LEFT JOIN client c ON c.ID = y.client_id
		                    LEFT JOIN region r ON r.ID = y.sales_representant_office_id
		                    LEFT JOIN agency a ON a.ID = y.agency_id
		                    LEFT JOIN agency_group ag ON a.agency_group_id = ag.ID 
		    				WHERE (y.sales_rep_id IN ($salesRepIDString) )
		    				AND (y.year = \"$cYear\" )
		                    AND (r.ID = \"" . $regionID . "\")
		    				ORDER BY 1
		    	       ";

                $resYTD = $con->query($ytd);
                $from = array("agencyGroup", "agencyGroupID");
                $listYTD = $sql->fetch($resYTD, $from, $from);
                $count = 0;
                break;

            case 'ae':
                //GET FROM SALES FORCE
                $sf = "SELECT DISTINCT 
		    				   sr.ID AS 'salesRepID',
		                       sr.name AS 'salesRep'		                       
		    				FROM sf_pr s
		                    LEFT JOIN sales_rep sr ON sr.ID = s.sales_rep_owner_id
		    				WHERE ((s.sales_rep_owner_id IN ($salesRepIDString)) OR (s.sales_rep_splitter_id IN ($salesRepIDString)))
		                    AND ( s.region_id = \"" . $regionID . "\") AND ( s.stage != \"6\") AND ( s.stage != \"5\") AND ( s.stage != \"7\")
		                    AND (s.year_from = \"$cYear\") AND (s.from_date > \"$date\")
		    				ORDER BY 1
		    	       ";

                $resSF = $con->query($sf);
                $from = array("salesRep", "salesRepID");
                $listSF = $sql->fetch($resSF, $from, $from);

                //GET FROM IBMS/BTS
                $ytd = "SELECT DISTINCT 		                       
		                       sr.ID AS 'salesRepID',
		                       sr.name AS 'salesRep'
		    				FROM ytd y
		    				LEFT JOIN sales_rep sr ON sr.ID = y.sales_rep_id
		    				LEFT JOIN region r ON r.ID = y.sales_representant_office_id
		    				WHERE (y.sales_rep_id IN ($salesRepIDString) )
		    				AND (y.year = \"$cYear\" )
		                    AND (r.ID = \"" . $regionID . "\")
		    				ORDER BY 1
		    	       ";
                $resYTD = $con->query($ytd);
                $from = array("salesRep", "salesRepID");
                $listYTD = $sql->fetch($resYTD, $from, $from);
                $count = 0;
                break;

            default:
                # code...
                break;
        }

        $list = array();

        if ($listSF) {
            for ($sff = 0; $sff < sizeof($listSF); $sff++) {
                $list[$count] = $listSF[$sff];
                $count++;
            }
        }
        if ($listYTD) {
            for ($y = 0; $y < sizeof($listYTD); $y++) {
                $list[$count] = $listYTD[$y];
                $count++;
            }
        }

        $list = array_map("unserialize", array_unique(array_map("serialize", $list)));

        $list = array_values($list);

        switch ($baseReport) {
            case 'client':
                usort($list, array($this, 'orderClient'));
                break;
            case 'agency':
                usort($list, array($this, 'orderAgency'));
                break;
            case 'agencyGroup':
                usort($list, array($this, 'orderAgencyGroup'));
                break;
            case 'ae':
                usort($list, array($this, 'orderAE'));
                break;
            case 'brand':
                usort($list, array($this, 'orderBrand'));
                break;
            default:
                # code...
                break;
        }




        return $list;
    }

    public function listClientsByAEMult($con, $sql, $salesRepID, $cYear, $regionID, $salesRepIDString)
    {

        $date = date('n') - 1;


        //GET FROM SALES FORCE
        $sf = "SELECT DISTINCT c.name AS 'clientName',
    				   c.ID AS 'clientID',
                       a.ID AS 'agencyID',
                       a.name AS 'agencyName'
    				FROM sf_pr s
                    LEFT JOIN client c ON c.ID = s.client_id
    				LEFT JOIN agency a ON a.ID = s.agency_id
    				WHERE ((s.sales_rep_owner_id IN ($salesRepIDString)) OR (s.sales_rep_splitter_id IN ($salesRepIDString)))
                    AND ( s.region_id = \"" . $regionID . "\") AND ( s.stage != \"6\") AND ( s.stage != \"5\") AND ( s.stage != \"7\")
                    AND (s.year_from = \"$cYear\") AND (s.from_date > \"$date\")
    				ORDER BY 1
    	       ";
        $resSF = $con->query($sf);
        $from = array("clientName", "clientID", "agencyID", "agencyName");
        $listSF = $sql->fetch($resSF, $from, $from);

        //GET FROM IBMS/BTS
        $ytd = "SELECT DISTINCT c.name AS 'clientName',
    				   c.ID AS 'clientID',
                       a.ID AS 'agencyID',
                       a.name AS 'agencyName'
    				FROM ytd y
    				LEFT JOIN client c ON c.ID = y.client_id
                    LEFT JOIN region r ON r.ID = y.sales_representant_office_id
                    LEFT JOIN agency a ON a.ID = y.agency_id
    				WHERE (y.sales_rep_id IN ($salesRepIDString) )
    				AND (y.year = \"$cYear\" )
                    AND (r.ID = \"" . $regionID . "\")
    				ORDER BY 1
    	       ";

        $resYTD = $con->query($ytd);
        $from = array("clientName", "clientID", "agencyID", "agencyName");
        $listYTD = $sql->fetch($resYTD, $from, $from);
        $count = 0;

        $list = array();

        if ($listSF) {
            for ($sff = 0; $sff < sizeof($listSF); $sff++) {
                $list[$count] = $listSF[$sff];
                $count++;
            }
        }
        if ($listYTD) {
            for ($y = 0; $y < sizeof($listYTD); $y++) {
                $list[$count] = $listYTD[$y];
                $count++;
            }
        }

        $list = array_map("unserialize", array_unique(array_map("serialize", $list)));

        $list = array_values($list);

        usort($list, array($this, 'orderClient'));

        return $list;
    }

    public function subArrays($array, $array1)
    {
        for ($i = 0; $i < sizeof($array); $i++) {
            $sub[$i] = $array[$i] - $array1[$i];
        }

        return $sub;
    }

    public function mergeList($array, $list)
    {
        //var_dump($array);
        //var_dump($list);

        for ($s = 0; $s < 17; $s++) {
            $total[$s] = 0.0;
        }

        for ($t = 0; $t < sizeof($total); $t++) {
            for ($a = 0; $a < sizeof($array); $a++) {
                //for ($b=0; $b < sizeof($array[$a]); $b++) { 
                $total[$t] += $array[$a][$t];
                //}
            }
        }

        return $total;



        var_dump($total);
    }

    public function revenueShares($con, $sql, $region, $year, $month, $sum, $value)
    {

        for ($m = 0; $m < sizeof($month); $m++) {
            $select = "SELECT SUM($sum) AS sum
                                FROM ytd
                                WHERE(sales_representant_office_id = '" . $region . "')
                                AND (year = '" . $year . "') 
                                AND (month = '" . $month[$m][1] . "')

                      ";
            $res = $con->query($select);
            $from = array('sum');
            $fetched = $sql->fetch($res, $from, $from)[0]['sum'];
            $revenue[$m] = $fetched;
        }
        $revTT = 0.0;
        for ($r = 0; $r < sizeof($revenue); $r++) {
            $revTT += $revenue[$r];
        }
        for ($r = 0; $r < sizeof($revenue); $r++) {
            $share[$r] = $revenue[$r] / $revTT;
        }
        //var_dump($share);
        return $share;
    }

    public function generateForecast($con, $sql, $baseReport, $region, $year, $list, $sum, $value, $share, $br, $lastYearBrand, $fb, $lastYearRevenue, $splitted, $cont)
    {

        switch ($baseReport) {
            case 'brand':

                $brands = $br->getBrand($con);

                $select =  "SELECT $sum AS sum,
                                from_date AS fromDate,
                                to_date AS toDate,
                                year_from AS yearFrom,
                                year_to AS yearTo,
                                sales_rep_owner_id AS owner,
                                sales_rep_splitter_id AS splitter
                                FROM sf_pr 
                                WHERE (region_id = '" . $region . "') 
                                AND (brand_id = '" . $list['brandID'] . "') 
                                AND (year_from = '" . $year . "' OR year_to = '" . $year . "') 
                                AND (from_date = '" . $month . "' OR to_date = '" . $month . "')
                                AND (stage != '5')
                                AND (stage != '6')
                                AND (stage != 'Cr')
                                ";
                //echo "<pre>".$select."</pre>";

                $res = $con->query($select);
                $from = array('sum', 'fromDate', 'toDate', 'yearFrom', 'yearTo', 'owner', 'splitter');
                $fetched = $sql->fetch($res, $from, $from);
                //var_dump($fetched);
                if ($fetched) {


                    for ($f = 0; $f < sizeof($fetched); $f++) {
                        if ($fetched[$f]['owner'] != $fetched[$f]['splitter']) {
                            $fetched[$f]['sum'] *= 2;
                        }
                    }

                    for ($f = 0; $f < sizeof($fetched); $f++) {
                        if ($fetched[$f]['fromDate'] != $fetched[$f]['toDate']) {

                            $size = $fetched[$f]['toDate'] - $fetched[$f]['fromDate'];
                            $somat = 0.0;
                            $newShare = array();
                            $percMult = 0.0;
                            for ($s = 0; $s <= $size; $s++) {
                                $somat += $share[(($fetched[$f]['fromDate'] - 1) + $s)];
                            }

                            for ($s = 0; $s <= $size; $s++) {
                                $newShare[$s]['value'] = (($fetched[$f]['fromDate'] - 1) + $s) / $somat;
                                $newShare[$s]['month'] = (($fetched[$f]['fromDate']) + $s);
                            }
                            //var_dump($newShare);
                            for ($n = 0; $n < sizeof($newShare); $n++) {
                                if ($newShare[$n]['month'] == $month) {
                                    $percMult = $newShare[$n]['value'];
                                }
                            }
                            //var_dump($percMult);

                            $fetched[$f]['sum'] *= $percMult;
                            //var_dump($fetched[$f]['sum']);
                        }
                    }
                }

                if ($fetched) {
                    $soma = 0.0;
                    for ($f = 0; $f < sizeof($fetched); $f++) {
                        $soma += $fetched[$f]['sum'];
                    }
                } else {
                    $soma = false;
                }

                //var_dump($fetched);
                //var_dump($soma);                      

                break;

            case 'ae':
                $select =  "SELECT $sum AS sumValue,
                                from_date AS fromDate,
                                to_date AS toDate,
                                year_from AS yearFrom,
                                year_to AS yearTo,
                                sales_rep_owner_id AS owner,
                                sales_rep_splitter_id AS splitter,
                                stage as stage
                                FROM sf_pr 
                                WHERE (region_id = '" . $region . "') 
                                AND (sales_rep_owner_id = '" . $list['salesRepID'] . "' OR sales_rep_splitter_id = '" . $list['salesRepID'] . "') 
                                AND (year_from = '" . $year . "' OR year_to = '" . $year . "') 
                                AND (stage != '5')
                                AND (stage != '6')
                                AND (stage != 'Cr')
                                ";
                //echo "<pre>".$select."</pre>";

                $res = $con->query($select);
                $from = array('sumValue', 'fromDate', 'toDate', 'yearFrom', 'yearTo', 'owner', 'splitter', 'stage');
                $fetched = $sql->fetch($res, $from, $from);

                //$month = $month-1;
                if ($fetched) {

                    /*
                        AJUSTE DAS PREVISÕES QUE POSSUEM MAIS DE 1 ANO DE PREVISÃO
                    */
                    for ($f = 0; $f < sizeof($fetched); $f++) {
                        if ($fetched[$f]['yearFrom'] != $fetched[$f]['yearTo']) {

                            $fromArray = $fb->makeMonths("from", $fetched[$f]['fromDate']);
                            $toArray = $fb->makeMonths("to", $fetched[$f]['toDate']);
                            $fromShare = $fb->calculateRespectiveShare($con, $sql, $region, $value, $fetched[$f]['yearFrom'], $fromArray);
                            $toShare = $fb->calculateRespectiveShare($con, $sql, $region, $value, $fetched[$f]['yearTo'], $toArray);
                            $shareFromCYear = $fb->aggregateShare($fromShare, $toShare);

                            $fetched[$f]['sumValue'] = $fetched[$f]['sumValue'] * $shareFromCYear;
                            //var_dump($fetched[$f]['sumValue']);

                            /*if($fetched[$f]['owner'] != $fetched[$f]['splitter']){
                            $fetched[$f]['sum'] = $fetched[$f]['sum']/2;
                        }*/
                        }
                    }

                    if ($fetched) {
                        for ($o = 0; $o < sizeof($fetched); $o++) {
                            //var_dump($fetched);              
                            $period[$o] = $fb->monthOPP($fetched[$o], $year);
                        }
                    } else {
                        $period = false;
                    }
                    //var_dump($period);

                    if ($period) {

                        //var_dump($lastYearRevenue);
                        $shareSalesRep = $this->salesRepShareOnPeriod(null, $lastYearRevenue[$cont], $period);
                        //var_dump($shareSalesRep);

                        //var_dump($splitted);
                        $fcst = $this->fillFCST($fetched,$period,$shareSalesRep,$list['salesRepID'],$splitted);
                        //var_dump($fcst);

                    }else{
                        $shareSalesRep = false;
                        $fcst = false;
                    }

                    if ($fcst) {
                        $fcst = $fb->adjustValues($fcst);
                        $fcstAmount = $fb->fcstAmount($fcst,$period,$splitted,$list['salesRepID']);
                        $fcstAmount = $fb->adjustValuesForecastAmount($fcstAmount);

                        //var_dump($fcstAmount);
                    }else{
                        $fcstAmount = false;
                    }
                }
                return $fcstAmount;

                break;

            case 'client':
                $select =  "SELECT $sum AS sum,
                                from_date AS fromDate,
                                to_date AS toDate,
                                year_from AS yearFrom,
                                year_to AS yearTo,
                                sales_rep_owner_id AS owner,
                                sales_rep_splitter_id AS splitter
                                FROM sf_pr 
                                WHERE (region_id = '" . $region . "') 
                                AND (client_id = '" . $list['clientID'] . "') 
                                AND (year_from = '" . $year . "' OR year_to = '" . $year . "') 
                                AND (from_date = '" . $month . "' OR to_date = '" . $month . "')
                                AND (stage != '5')
                                AND (stage != '6')
                                AND (stage != 'Cr')
                                ";
                //echo "<pre>".$select."</pre>";

                $res = $con->query($select);
                $from = array('sum', 'fromDate', 'toDate', 'yearFrom', 'yearTo', 'owner', 'splitter');
                $fetched = $sql->fetch($res, $from, $from);
                $somat = 0.0;
                $newShare = array();
                $newValue = array();
                $percMult = 0.0;
                //var_dump($fetched);

                if ($fetched) {
                    //var_dump($fetched);
                    for ($f = 0; $f < sizeof($fetched); $f++) {
                        if ($fetched[$f]['owner'] != $fetched[$f]['splitter']) {
                            $fetched[$f]['sum'] *= 2;
                        }
                    }

                    for ($f = 0; $f < sizeof($fetched); $f++) {
                        if ($fetched[$f]['fromDate'] != $fetched[$f]['toDate']) {

                            $size = $fetched[$f]['toDate'] - $fetched[$f]['fromDate'];
                            $somat = 0.0;
                            for ($s = 0; $s <= $size; $s++) {
                                $somat += $share[(($fetched[$f]['fromDate'] - 1) + $s)];
                            }

                            for ($s = 0; $s <= $size; $s++) {
                                $newShare[$s]['value'] = (($fetched[$f]['fromDate'] - 1) + $s) / $somat;
                                $newShare[$s]['month'] = (($fetched[$f]['fromDate']) + $s);
                            }

                            for ($n = 0; $n < sizeof($newShare); $n++) {
                                if ($newShare[$n]['month'] == $month) {
                                    $percMult = $newShare[$n]['value'];
                                }
                            }

                            $fetched[$f]['sum'] *= $percMult;
                        }
                    }
                }

                if ($fetched) {
                    $soma = 0.0;
                    for ($f = 0; $f < sizeof($fetched); $f++) {
                        $soma += $fetched[$f]['sum'];
                    }
                } else {
                    $soma = false;
                }

                //var_dump($fetched);
                //var_dump($soma);
                break;

            case 'agency':
                $select =  "SELECT $sum AS sum,
                                from_date AS fromDate,
                                to_date AS toDate,
                                year_from AS yearFrom,
                                year_to AS yearTo,
                                sales_rep_owner_id AS owner,
                                sales_rep_splitter_id AS splitter
                                FROM sf_pr 
                                WHERE (region_id = '" . $region . "') 
                                AND (agency_id = '" . $list['agencyID'] . "') 
                                AND (year_from = '" . $year . "' OR year_to = '" . $year . "') 
                                AND (from_date = '" . $month . "' OR to_date = '" . $month . "')
                                AND (stage != '5')
                                AND (stage != '6')
                                AND (stage != 'Cr')
                                ";
                //echo "<pre>".$select."</pre>";

                $res = $con->query($select);
                $from = array('sum', 'fromDate', 'toDate', 'yearFrom', 'yearTo', 'owner', 'splitter');
                $fetched = $sql->fetch($res, $from, $from);
                $somat = 0.0;
                $newShare = array();
                $newValue = array();
                $percMult = 0.0;

                if ($fetched) {
                    for ($f = 0; $f < sizeof($fetched); $f++) {
                        if ($fetched[$f]['owner'] != $fetched[$f]['splitter']) {
                            $fetched[$f]['sum'] *= 2;
                        }
                    }

                    for ($f = 0; $f < sizeof($fetched); $f++) {
                        if ($fetched[$f]['fromDate'] != $fetched[$f]['toDate']) {

                            $size = $fetched[$f]['toDate'] - $fetched[$f]['fromDate'];
                            $somat = 0.0;
                            for ($s = 0; $s <= $size; $s++) {
                                $somat += $share[(($fetched[$f]['fromDate'] - 1) + $s)];
                            }

                            for ($s = 0; $s <= $size; $s++) {
                                $newShare[$s]['value'] = (($fetched[$f]['fromDate'] - 1) + $s) / $somat;
                                $newShare[$s]['month'] = (($fetched[$f]['fromDate']) + $s);
                            }

                            for ($n = 0; $n < sizeof($newShare); $n++) {
                                if ($newShare[$n]['month'] == $month) {
                                    $percMult = $newShare[$n]['value'];
                                }
                            }

                            $fetched[$f]['sum'] *= $percMult;
                        }
                    }
                }

                if ($fetched) {
                    $soma = 0.0;
                    for ($f = 0; $f < sizeof($fetched); $f++) {
                        $soma += $fetched[$f]['sum'];
                    }
                } else {
                    $soma = false;
                }


                break;

            case 'agencyGroup':
                $select =  "SELECT sf.$sum AS sum,
                                sf.from_date AS fromDate,
                                sf.to_date AS toDate,
                                sf.year_from AS yearFrom,
                                sf.year_to AS yearTo,
                                sf.sales_rep_owner_id AS owner,
                                sf.sales_rep_splitter_id AS splitter
                                FROM sf_pr sf 
                                LEFT JOIN agency a ON (a.ID = sf.agency_id)
                                LEFT JOIN agency_group ag ON (a.agency_group_id = ag.ID)
                                WHERE (sf.region_id = '" . $region . "') 
                                AND (ag.ID = '" . $list['agencyGroupID'] . "') 
                                AND (sf.year_from = '" . $year . "' OR sf.year_to = '" . $year . "') 
                                AND (sf.from_date = '" . $month . "' OR sf.to_date = '" . $month . "')
                                AND (sf.stage != '5')
                                AND (sf.stage != '6')
                                AND (sf.stage != 'Cr')
                                ";

                $res = $con->query($select);
                $from = array('sum', 'fromDate', 'toDate', 'yearFrom', 'yearTo', 'owner', 'splitter');
                $fetched = $sql->fetch($res, $from, $from);
                $somat = 0.0;
                $newShare = array();
                $newValue = array();
                $percMult = 0.0;

                if ($fetched) {
                    for ($f = 0; $f < sizeof($fetched); $f++) {
                        if ($fetched[$f]['owner'] != $fetched[$f]['splitter']) {
                            $fetched[$f]['sum'] *= 2;
                        }
                    }

                    for ($f = 0; $f < sizeof($fetched); $f++) {
                        if ($fetched[$f]['fromDate'] != $fetched[$f]['toDate']) {

                            $size = $fetched[$f]['toDate'] - $fetched[$f]['fromDate'];
                            $somat = 0.0;
                            for ($s = 0; $s <= $size; $s++) {
                                $somat += $share[(($fetched[$f]['fromDate'] - 1) + $s)];
                            }

                            for ($s = 0; $s <= $size; $s++) {
                                $newShare[$s]['value'] = (($fetched[$f]['fromDate'] - 1) + $s) / $somat;
                                $newShare[$s]['month'] = (($fetched[$f]['fromDate']) + $s);
                            }

                            for ($n = 0; $n < sizeof($newShare); $n++) {
                                if ($newShare[$n]['month'] == $month) {
                                    $percMult = $newShare[$n]['value'];
                                }
                            }

                            $fetched[$f]['sum'] *= $percMult;
                        }
                    }
                }

                if ($fetched) {
                    $soma = 0.0;
                    for ($f = 0; $f < sizeof($fetched); $f++) {
                        $soma += $fetched[$f]['sum'];
                    }
                } else {
                    $soma = false;
                }


                break;

            default:
                $soma = false;
                break;
        }

        return $soma;
    }

    public function salesRepShareOnPeriod($lyRCompany ,$lyRSP,$monthOPP){
        
        /* GET INFO FROM PREVIOUS YEAR AND MAKE SHARE BY MONTH WHEN THERE IS NO CLIENT OR SALES REP */      
        //var_dump($lyRSP); 

        for ($l=0; $l < sizeof($monthOPP); $l++){
            $amount[$l] = 0.0;
            for ($m=0; $m < sizeof($monthOPP[$l]); $m++) { 
            
                if($lyRSP[$monthOPP[$l][$m]] > 0 && $lyRSP[16] > 0){
                    /*
                        IF THE CLINET DOES NOT HAVE REVENUE ON THE MONTH LAST YEAR GET THE SHARE OF THE REP ON THE SAME MONTH ON LAST YEAR
                    */  
                    if($lyRSP[$monthOPP[$l][$m]] > 0 && $lyRSP[16] > 0){
                        $share[$l][$m] = $lyRSP[$monthOPP[$l][$m]];//$lyRSP[16];  
                        $amount[$l] += $share[$l][$m];
                    }else{
                        /*
                            IF THE SALES REP DOES NOT HAVE REVENUE ON THE MONTH LAST YEAR GET THE SHARE OF THE MONTH ON THE  ON LAST YEAR
                        */
                        $share[$l][$m] = $lyRCompany[$monthOPP[$l][$m]];//$lyRCompany[16];
                        $amount[$l] += $share[$l][$m];
                    }
                }else{
                    $share[$l][$m] = $lyRCompany[$monthOPP[$l][$m]];;
                    $amount[$l] += $share[$l][$m];
                }
            }

            $newAmount[$l] = $amount[$l];// / sizeof($monthOPP[$l]);
        }
       
        for ($s=0; $s < sizeof($share); $s++) { 
            for ($t=0; $t < sizeof($share[$s]); $t++) { 
                if ($newAmount[$s] == 0) {
                    $share[$s][$t] = 0;
                }else{
                    $share[$s][$t] = $share[$s][$t] / ( $newAmount[$s] );
                }

            }
        }        

        return $share;
    }

    public function fillFCST($sFCST, $mOPP, $sRP, $salesRepUser, $splitted)
    {

        $base = new base();

        $monthWQ = $base->monthWQ;

        for ($i = 0; $i < sizeof($sFCST); $i++) {
            for ($m = 0; $m < sizeof($monthWQ); $m++) {
                $fcst[$i][$m]['stage'] = false;
                $fcst[$i][$m]['value'] = 0.0;
            }
        }

        for ($i = 0; $i < sizeof($sFCST); $i++) {
            if ($splitted == null || !$splitted['splitted']) {
                $factor = 1;
            } else {
                $factor = 2;
            }

            $adjustedValue = $sFCST[$i]['sumValue'] * $factor;
            for ($j = 0; $j < sizeof($mOPP[$i]); $j++) {
                //var_dump( $sRP[$i]);
                $fcst[$i][$mOPP[$i][$j]]['stage'] = $sFCST[$i]['stage'];

                $fcst[$i][$mOPP[$i][$j]]['value'] = ($adjustedValue * $sRP[$i][$j]);
            }
        }

        return $fcst;
    }

    public function generateManual($con, $sql, $baseReport, $region, $year, $month, $list, $sum, $value, $share, $br, $lastYearBrand, $currency)
    {

        switch ($baseReport) {
            case 'brand':

                $brands = $br->getBrand($con);

                $select =  "SELECT $sum AS sum,
                                from_date AS fromDate,
                                to_date AS toDate,
                                year_from AS yearFrom,
                                year_to AS yearTo,
                                sales_rep_owner_id AS owner,
                                sales_rep_splitter_id AS splitter
                                FROM sf_pr 
                                WHERE (region_id = '" . $region . "') 
                                AND (brand_id = '" . $list['brandID'] . "') 
                                AND (year_from = '" . $year . "' OR year_to = '" . $year . "') 
                                AND (from_date = '" . $month . "' OR to_date = '" . $month . "')
                                AND (stage != '5')
                                AND (stage != '6')
                                AND (stage != 'Cr')
                                ";
                //echo "<pre>".$select."</pre>";

                $res = $con->query($select);
                $from = array('sum', 'fromDate', 'toDate', 'yearFrom', 'yearTo', 'owner', 'splitter');
                $fetched = $sql->fetch($res, $from, $from);
                var_dump($fetched);

                if ($fetched) {

                    for ($f = 0; $f < sizeof($fetched); $f++) {
                        if ($fetched[$f]['owner'] != $fetched[$f]['splitter']) {
                            $fetched[$f]['sum'] *= 2;
                        }
                    }

                    /*for ($f=0; $f < sizeof($fetched); $f++) {
                        if($fetched[$f]['fromDate'] != $fetched[$f]['toDate']){
                            
                            $size = $fetched[$f]['toDate'] - $fetched[$f]['fromDate'];
                            $somat = 0.0;
                            for ($s=0; $s <= $size; $s++) { 
                                $somat += $share[(($fetched[$f]['fromDate']-1)+$s)];                                                                
                            }

                            for ($s=0; $s <= $size; $s++) { 
                                $newShare[$s]['value'] = $share[(($fetched[$f]['fromDate']-1)+$s)]/$somat;                                
                                $newShare[$s]['month'] = (($fetched[$f]['fromDate'])+$s);
                            }

                            for ($n=0; $n < sizeof($newShare); $n++) { 
                                if($newShare[$n]['month'] == $month){
                                    $percMult = $newShare[$n]['value'];
                                }
                            }

                            $fetched[$f]['sum'] *= $percMult;
                        }
                    }*/
                } else {
                }

                if ($fetched) {
                    $soma = 0.0;
                    for ($f = 0; $f < sizeof($fetched); $f++) {
                        $soma += $fetched[$f]['sum'];
                    }
                } else {
                    $soma = false;
                }

                //var_dump($fetched);
                //var_dump($soma);                      

                break;
            case 'ae':
                $select =  "SELECT fsr.value AS sum,
                                f.currency_id AS currency,
                                f.region_id AS region,
                                f.type_of_value AS value, 
                                sales_rep_id AS salesRep,
                                f.month AS month
                                FROM forecast_sales_rep fsr 
                                LEFT JOIN forecast f ON fsr.forecast_id = f.ID 
                                WHERE (f.region_id = '" . $region . "') 
                                AND (f.sales_rep_id = '" . $list['salesRepID'] . "')
                                AND (f.month >= '" . $month . "')
                                AND (f.year = '" . $year . "')
                                ";
                //echo "<pre>".$select."</pre>";

                $res = $con->query($select);
                $from = array('sum', 'currency', 'region', 'value', 'salesRep', 'month');
                $fetched = $sql->fetch($res, $from, $from);

                //var_dump($fetched);

                if ($fetched) {
                    for ($f = 0; $f < sizeof($fetched); $f++) {
                        if ($currency = $fetched[$f]['currency']) {
                            if ($value = strtolower($fetched[$f]['value'])) {
                                $soma = $fetched[$f]['sum'];
                                //var_dump($soma);
                            } else {
                                $soma = 0.0;
                                //var_dump('else');
                            }
                        } else {
                        }
                    }
                }
                //var_dump($fetched);
                $soma = 0;
                //var_dump("================================");

                break;

            case 'client':
                $select =  "SELECT fc.value AS sum,
                                f.currency_id AS currency,
                                f.region_id AS region,
                                f.type_of_value AS value, 
                                client_id AS client
                                FROM forecast_client fc  
                                LEFT JOIN forecast f ON fc.forecast_id = f.ID     
                                WHERE (f.region_id = '" . $region . "') 
                                AND (f.month >= '" . $month . "')
                                AND (f.client_id = '" . $list['clientID'] . "') 
                                AND (f.year = '" . $year . "')
                                ";
                //echo "<pre>".$select."</pre>";

                $res = $con->query($select);
                $from = array('sum', 'currency', 'region', 'value', 'client', 'month');
                $fetched = $sql->fetch($res, $from, $from);
                $newValue = array();
                $newShare = array();

                if ($fetched) {
                    for ($f = 0; $f < sizeof($fetched); $f++) {
                        if ($currency = $fetched[$f]['currency']) {
                            if ($value = strtolower($fetched[$f]['value'])) {
                                $soma = $fetched[$f]['sum'];
                            } else {
                                $soma = 0.0;
                            }
                        } else {
                        }
                    }
                }
                $soma = false;

                //var_dump($fetched);
                //var_dump($soma);
                break;

            case 'agency':
                $select =  "SELECT fc.value AS sum,
                                f.currency_id AS currency,
                                f.region_id AS region,
                                f.type_of_value AS value, 
                                agency_id AS agency
                                FROM forecast_client fc  
                                LEFT jOIN forecast f ON fc.forecast_id = f.ID     
                                WHERE (f.region_id = '" . $region . "') 
                                AND (f.month >= '" . $month . "')
                                AND (f.client_id = '" . $list['agencyID'] . "') 
                                AND (f.year = '" . $year . "')
                                ";
                //echo "<pre>".$select."</pre>";

                $res = $con->query($select);
                $from = array('sum', 'currency', 'region', 'value', 'agency', 'month');
                $fetched = $sql->fetch($res, $from, $from);
                $newValue = array();
                $newShare = array();

                if ($fetched) {
                    for ($f = 0; $f < sizeof($fetched); $f++) {
                    }
                }

                if ($fetched) {
                    $soma = 0.0;
                    for ($f = 0; $f < sizeof($fetched); $f++) {
                        $soma += $fetched[$f]['sum'];
                    }
                } else {
                    $soma = false;
                }


                break;

            case 'agencyGroup':
                $select =  "SELECT SELECT fc.value AS sum,
                                f.currency_id as currency,
                                f.region_id as region,
                                f.type_of_value as value, 
                                ag.ID AS agencyGroup
                                FROM forecast_client fc
                                LEFT JOIN agency a ON (a.ID = fC.agency_id)
                                LEFT JOIN agency_group ag ON (a.agency_group_id = ag.ID)
                                WHERE (f.region_id = '" . $region . "') 
                                AND (f.month >= '" . $month . "')
                                AND (f.client_id = '" . $list['agencyGroupID'] . "') 
                                AND (f.year = '" . $year . "')
                                ";

                $res = $con->query($select);
                $from = array('sum', 'currency', 'region', 'value', 'agencyGroup', 'month');
                $fetched = $sql->fetch($res, $from, $from);
                $newValue = array();
                $newShare = array();

                if ($fetched) {
                    for ($f = 0; $f < sizeof($fetched); $f++) {
                    }
                }

                if ($fetched) {
                    $soma = 0.0;
                    for ($f = 0; $f < sizeof($fetched); $f++) {
                        $soma += $fetched[$f]['sum'];
                    }
                } else {
                    $soma = false;
                }


                break;

            default:
                $soma = false;
                break;
        }

        return $soma;
    }

    public function generateValuePandR($con, $sql, $kind, $baseReport, $region, $year, $month, $list, $sum, $value = null)
    {

        if ($kind == 'target') {
            $seek = 'plan';
            if ($baseReport == 'brand') {
                $table = 'plan_by_brand';
                $sum = 'revenue';
            } elseif ($baseReport == 'ae') {
                $table = 'plan_by_sales';
            } else {
                $table = false;
            }
        } else {
            $seek = 'ytd';
            $table = 'ytd';
        }
        if ($table) {
            if ($baseReport == 'agencyGroup') {
                $where = $this->createWhere($sql, $seek, $baseReport, $region, $year, $list, $month, $value);
                $table .= " y";
                $join = "LEFT JOIN agency a ON (a.ID = y.agency_id)
                                LEFT JOIN agency_group ag ON (a.agency_group_id = ag.ID)";
                $results = $sql->selectSum($con, $sum, "sum", $table, $join, $where);
                $values = $sql->fetchSum($results, "sum")["sum"];
            } elseif ($baseReport == 'brand') {
                $where = $this->createWhere($sql, $seek, $baseReport, $region, $year, $list, $month, $value, strtoupper($kind));
                $results = $sql->selectSum($con, $sum, "sum", $table, false, $where);
                $values = $sql->fetchSum($results, "sum")["sum"];
            } else {
                $where = $this->createWhere($sql, $seek, $baseReport, $region, $year, $list, $month, $value);
                $results = $sql->selectSum($con, $sum, "sum", $table, false, $where);
                $values = $sql->fetchSum($results, "sum")["sum"];
            }
        } else {
            $values = 0.0;
        }

        return $values;
    }

    public function createWhere($sql, $source, $baseReport, $region, $year, $list, $month, $value = null, $kind = null)
    {

        switch ($baseReport) {
            case 'brand':
                $dbColumn = 'brand_id';
                $listT = $list['brandID'];
                break;
            case 'ae':
                $dbColumn = 'sales_rep_id';
                $listT = $list['salesRepID'];
                break;
            case 'client':
                $dbColumn = 'client_id';
                $listT = $list['clientID'];
                break;
            case 'agency':
                $dbColumn = 'agency_id';
                $listT = $list['agencyID'];
                break;
            case 'agencyGroup':
                $dbColumn = 'ID';
                $listT = $list['agencyGroupID'];
                break;
        }

        if ($source == "ytd") {
            if ($baseReport == 'agencyGroup') {
                $columns = array("y.sales_representant_office_id", "ag." . $dbColumn, "y.year", "y.month");
            } else {
                $columns = array("sales_representant_office_id", $dbColumn, "year", "month");
            }
            $arrayWhere = array($region, $listT, $year, $month);
            $where = $sql->where($columns, $arrayWhere);
        } elseif ($source == "plan") {

            switch ($baseReport) {
                case 'brand':
                    $table = 'plan_by_brand';
                    $regionC = 'sales_office_id';
                    $listC = 'brand_id';
                    $listT = $list['brandID'];
                    break;
                case 'ae':
                    $table = 'plan_by_sales';
                    $regionC = 'region_id';
                    $listC = 'sales_rep_id';
                    $listT = $list['salesRepID'];
                    break;
                case 'client':
                    $table = false;
                    $listT = $list['clientID'];
                    break;
                case 'agency':
                    $table = false;
                    $listT = $list['agencyID'];
                    break;
                case 'agency_group':
                    $table = false;
                    $listT = $list['agencyGroupID'];
                    break;
            }
            if ($table) {
                if ($baseReport == 'brand') {
                    $columns = array($regionC, "year", "month", $listC, "currency_id", "type_of_revenue", "source");
                    $arrayWhere = array($region, $year, $month, $listT, '4', $value, $kind);
                    $where = $sql->where($columns, $arrayWhere);
                } else {
                    $columns = array($regionC, "year", "month", $listC, "currency_id", "type_of_revenue");
                    $arrayWhere = array($region, $year, $month, $listT, '4', $value);
                    $where = $sql->where($columns, $arrayWhere);
                }
            } else {
                $where = false;
            }
        } else {
            $where = false;
        }

        return $where;
    }

    public function weekOfMonth($date)
    {
        $date = strtotime($date);
        //Get the first day of the month.
        $firstOfMonth = strtotime(date("Y-m-01", $date));
        //Apply above formula.
        if ((intval(date("W", $date)) - intval(date("W", $firstOfMonth))) == 0) {
            return intval(date("W", $date)) - intval(date("W", $firstOfMonth)) + 1;
        } else {
            return intval(date("W", $date)) - intval(date("W", $firstOfMonth)) + 1;
        }
    }

    public function divArrays($array1, $array2)
    {
        $exit = array();

        for ($a = 0; $a < sizeof($array1); $a++) {
            if ($array2[$a] != 0) {
                $exit[$a] = ($array1[$a] / $array2[$a]) * 100;
            } else {
                $exit[$a] = 0;
            }
        }

        return $exit;
    }

    public function isiTSplitted($con, $sql, $sR, $list, $cY, $pY)
    {
        /*
        $soma = 0;

        $splitted = array();
        for ($l=0; $l < sizeof($list); $l++) { 
            $splitted[$l] = $this->checkSplitted($con,$sql,$sR[0],$list[$l],$cY);
        }        
        return $splitted;  
       	*/
    }

    public function checkSplitted($con, $sql, $sR, $list, $year)
    {
        //$rtr = array( "splitted" => false , "owner" => null );

        /*        
        CHECKING FOR SPLITTED ACCOUNTS ON BI / BTS
        */
        /*
        $date = date('n')-1;

        $select = "SELECT DISTINCT order_reference , sales_rep_id , client_id ,agency_id
                        FROM ytd
                        WHERE (client_id = \"".$list['clientID']."\")
                        AND (agency_id = \"".$list['agencyID']."\")
                        AND (year = \"".$year."\") 
                        AND (from_date > \"".$date."\")                 
                  ";


        $res = $con->query($select);
        $from = array("order_reference","sales_rep_id","client_id","agency_id");
        $orderRef = $sql->fetch($res,$from,$from);
        $cc = 0;
        if($orderRef){
            for ($o=0; $o < sizeof($orderRef); $o++) { 
                if($o == 0){
                    $comp[$cc]['sales_rep_id'] = $orderRef[$o]['sales_rep_id'];
                    $comp[$cc]['agency_id'] = $orderRef[$o]['agency_id'];
                }

                if($comp[0]['agency_id'] == $orderRef[$o]['agency_id']){
                    $splitted[$cc] = $orderRef[$o]['sales_rep_id'];
                    $cc++;    
                }                
                
            }
        }

        if( isset( $splitted ) ){
            $splitted = array_values(array_unique($splitted));
            if(sizeof($splitted) > 1){
                $rtr = array( "splitted" => true , "owner" => null );
            }
        } 

        /*
        
        CHECKING FOR SPLITTED ACCOUNTS ON BI / BTS

        */
        /*
        $selectSF = "SELECT DISTINCT oppid , sales_rep_owner_id , sales_rep_splitter_id , client_id, brand
                        FROM sf_pr
                        WHERE (client_id = \"".$list['clientID']."\") 
                        AND (agency_id = \"".$list['agencyID']."\")
                        AND (sales_rep_splitter_id != sales_rep_owner_id)
                        AND (stage != \"5\")                      
                        AND (stage != \"6\")                      
                        AND (stage != \"7\")                      
                  ";

        $resSF = $con->query($selectSF);
        $fromSF = array("oppid","sales_rep_owner_id","sales_rep_splitter_id","client_id", "brand");
        $oppid = $sql->fetch($resSF,$fromSF,$fromSF);

        if($oppid){
            $rtr = array( "splitted" => true , "owner" => false );    
            for ($o=0; $o < sizeof($oppid); $o++) {                 
                if($sR == $oppid[$o]['sales_rep_owner_id']){
                    $rtr = array( "splitted" => true , "owner" => true );
                    break;
                }
            }
        }else{
            $selectSF = "SELECT DISTINCT oppid , sales_rep_owner_id , sales_rep_splitter_id , client_id, brand
                        FROM sf_pr
                        WHERE (client_id = \"".$list['clientID']."\") 
                        AND (agency_id = \"".$list['agencyID']."\")
                        AND (sales_rep_splitter_id = sales_rep_owner_id)
                        AND (stage != \"5\")                      
                        AND (stage != \"6\")                      
                        AND (stage != \"7\")                      
                  ";

            $resSF = $con->query($selectSF);
            $fromSF = array("oppid","sales_rep_owner_id","sales_rep_splitter_id","client_id", "brand");
            $oppid = $sql->fetch($resSF,$fromSF,$fromSF);

            if($oppid){
                $rtr = array( "splitted" => false , "owner" => null );    
            }

        }        

        /*

        FIND A WAY TO USE YEAR TO CHECK FOR SPLIITING

        */

        //return $rtr;

    }

    public function addQuartersAndTotalOnArray($array)
    {
        for ($a = 0; $a < sizeof($array); $a++) {
            $newArray[$a] = $this->addQuartersAndTotal($array[$a]);
        }
        return $newArray;
    }

    public function addQuartersAndTotal($tgt){
        //JAN,FEB,MAR
        $tgtWQ[0] = $tgt[0];
        $tgtWQ[1] = $tgt[1];
        $tgtWQ[2] = $tgt[2];

        // Q1
        $tgtWQ[3] = $tgtWQ[0] + $tgtWQ[1] + $tgtWQ[2];

        //APR,MAI,JUN
        $tgtWQ[4] = $tgt[3];
        $tgtWQ[5] = $tgt[4];
        $tgtWQ[6] = $tgt[5];

        // Q2
        $tgtWQ[7] = $tgtWQ[4] + $tgtWQ[5] + $tgtWQ[6];

        //JUL,AUG,SEP
        $tgtWQ[8] = $tgt[6];
        $tgtWQ[9] = $tgt[7];
        $tgtWQ[10] = $tgt[8];

        // Q3
        $tgtWQ[11] = $tgtWQ[8] + $tgtWQ[9] + $tgtWQ[10];

        //OCT,NOV,DEC
        $tgtWQ[12] = $tgt[9];
        $tgtWQ[13] = $tgt[10];
        $tgtWQ[14] = $tgt[11];

        // Q4
        $tgtWQ[15] = $tgtWQ[12] + $tgtWQ[13] + $tgtWQ[14];

        $tgtWQ[16] = $tgtWQ[3] + $tgtWQ[7] + $tgtWQ[11] + $tgtWQ[15];

        return $tgtWQ;
    }

    public function addQuartersAndTotalRF($rf){

        for ($a = 0; $a < sizeof($rf); $a++) {
            //JAN,FEB,MAR
            $rfWQ[$a][0] = $rf[$a][0];
            $rfWQ[$a][1] = $rf[$a][1];
            $rfWQ[$a][2] = $rf[$a][2];

            // Q1
            $rfWQ[$a][3] = $rfWQ[$a][0] + $rfWQ[$a][1] + $rfWQ[$a][2];

            //APR,MAI,JUN
            $rfWQ[$a][4] = $rf[$a][3];
            $rfWQ[$a][5] = $rf[$a][4];
            $rfWQ[$a][6] = $rf[$a][5];
            
            // Q2
            $rfWQ[$a][7] = $rfWQ[$a][4] + $rfWQ[$a][5] + $rfWQ[$a][6];

            //JUL,AUG,SEP
            $rfWQ[$a][8] = $rf[$a][6];
            $rfWQ[$a][9] = $rf[$a][7];
            $rfWQ[$a][10] = $rf[$a][8];

            // Q3
            $rfWQ[$a][11] = $rfWQ[$a][8] + $rfWQ[$a][9] + $rfWQ[$a][10];

            //OCT,NOV,DEC
            $rfWQ[$a][12] = $rf[$a][9];
            $rfWQ[$a][13] = $rf[$a][10];
            $rfWQ[$a][14] = $rf[$a][11];

            // Q4
            $rfWQ[$a][15] = $rfWQ[$a][12] + $rfWQ[$a][13] + $rfWQ[$a][14];

            $rfWQ[$a][16] = $rfWQ[$a][3] + $rfWQ[$a][7] + $rfWQ[$a][11] + $rfWQ[$a][15];
        }

        var_dump($rfWQ);
        return $rfWQ;
    }

    public static function orderByValue($a, $b)
    {
        if ($a == $b)
            return 0;

        return ($a['value'] > $b['value']) ? -1 : 1;
    }

    public static function orderAgency($a, $b)
    {
        if ($a == $b)
            return 0;

        return ($a['agencyName'] < $b['agencyName']) ? -1 : 1;
    }

    public static function orderAgencyGroup($a, $b)
    {
        if ($a == $b)
            return 0;

        return ($a['agencyGroup'] < $b['agencyGroup']) ? -1 : 1;
    }

    public static function orderAE($a, $b)
    {
        if ($a == $b)
            return 0;

        return ($a['salesRepID'] < $b['salesRepID']) ? -1 : 1;
    }

    public static function orderClient($a, $b)
    {
        if ($a == $b)
            return 0;

        return ($a['clientName'] < $b['clientName']) ? -1 : 1;
    }
    public static function orderBrand($a, $b)
    {
        if ($a == $b)
            return 0;

        //return ($a['brand'] < $b['brand']) ? -1 : 1;
    }

    public function lastYearBrand($con, $sql, $pr, $brands, $year, $value, $currency, $region, $currencyID)
    {
        if ($value == "gross") {
            $col = "gross_revenue_prate";
            $colFW = "gross_revenue";
        } else {
            $col = "net_revenue_prate";
            $colFW = "net_revenue";
        }

        $date = date('n') - 1;

        if ($currency == "USD") {
            $div = 1.0;
        } else {
            $div = $pr->getPRateByRegionAndYear($con, array($currencyID), array($year));
        }

        for ($b = 0; $b < sizeof($brands); $b++) {
            for ($m = 0; $m < 12; $m++) {
                if ($m >= $date) {
                    if ($brands[$b]['name'] == 'ONL') {
                        //pegar ONL do FW
                        $select[$b] = "SELECT SUM($colFW) AS value FROM fw_digital WHERE (region_id = \"" . $region . "\") AND (month = \"" . ($m + 1) . "\") AND (brand_id != \"10\") AND (year = \"" . $year . "\")";
                    } elseif ($brands[$b]['name'] == 'VIX') {
                        //pegar Vix do FW (diferente do ONL pq onl é tudo menos Vix)
                        $select[$b] = "SELECT SUM($colFW) AS value FROM fw_digital WHERE (region_id = \"" . $region . "\")  AND (month = \"" . ($m + 1) . "\") AND (brand_id = \"" . $brands[$b]['id'] . "\") AND (year = \"" . $year . "\")";
                    } else {
                        $select[$b] = "SELECT SUM($col) AS value FROM ytd WHERE (sales_representant_office_id = \"" . $region . "\") AND (month = \"" . ($m + 1) . "\") AND (brand_id = \"" . $brands[$b]['id'] . "\") AND (year = \"" . $year . "\")";
                    }

                    $res[$b] = $con->query($select[$b]);
                    $resp[$b] = $sql->fetchSum($res[$b], "value")['value'] * $div;
                }/*else{
                    $resp[$b][$m] = 0;
                }*/
            }
        }

        //var_dump($select);
        return $resp;
    }

    public function getBooking($con, $sql, $pr, $brands, $year, $value, $currency, $region, $currencyID, $salesRep)
    {

        if ($value == "gross") {
            $col = "gross_revenue_prate";
            $colFW = "gross_revenue";
        } else {
            $col = "net_revenue_prate";
            $colFW = "net_revenue";
        }

        if ($currency == "USD") {
            $div = 1.0;
        } else {
            $div = $pr->getPRateByRegionAndYear($con, array($currencyID), array(date('Y')));
        }

        for ($b = 0; $b < sizeof($brands); $b++) {
            for ($m = 0; $m < 12; $m++) {
                if ($brands[$b]['name'] == 'ONL') {
                    //pegar ONL do FW
                    $select[$b][$m] = "SELECT SUM($colFW) AS value FROM fw_digital WHERE (region_id = \"" . $region . "\") AND (month = \"" . ($m + 1) . "\") AND (brand_id != \"10\") AND (year = \"" . $year . "\") AND (sales_rep_id = \"" . $salesRep[0]["id"] . "\")";
                } elseif ($brands[$b]['name'] == 'VIX') {
                    //pegar Vix do FW (diferente do ONL pq onl é tudo menos Vix)
                    $select[$b][$m] = "SELECT SUM($colFW) AS value FROM fw_digital WHERE (region_id = \"" . $region . "\") AND (month = \"" . ($m + 1) . "\") AND (brand_id = \"" . $brands[$b]['id'] . "\") AND (year = \"" . $year . "\") AND (sales_rep_id = \"" . $salesRep[0]["id"] . "\")";
                } else {
                    $select[$b][$m] = "SELECT SUM($col) AS value FROM ytd WHERE (sales_representant_office_id = \"" . $region . "\") AND (month = \"" . ($m + 1) . "\") AND (brand_id = \"" . $brands[$b]['id'] . "\") AND (year = \"" . $year . "\") AND (sales_rep_id = \"" . $salesRep[0]["id"] . "\")";
                }

                $res[$b][$m] = $con->query($select[$b][$m]);
                $resp[$b][$m] = $sql->fetchSum($res[$b][$m], "value")['value'] * $div;
            }
        }
        //var_dump($select);
        return $resp;
    }

    public function addFcstWithBooking($booking, $fcst)
    {

        $date = date('n') - 1;

        if ($date < 3) {
        } elseif ($date < 6) {
            $date++;
        } elseif ($date < 9) {
            $date += 2;
        } else {
            $date += 3;
        }

        for ($c = 0; $c < sizeof($booking); $c++) {
            for ($f = 0; $f < sizeof($booking[$c]); $f++) {
                if ($f < $date) {
                    $sum[$c][$f] = $booking[$c][$f];
                } else {
                    $sum[$c][$f] = $fcst[$c][$f];
                }
            }
        }
        return $sum;
    }

    public function generateColumns($value, $source)
    {

        if ($value && $source == "ytd") {
            $columns = $value . "_revenue_prate";
        } else if ($value && $source == "crm") {
            $columns = $value . "_revenue";;
        } elseif ($value) {
            $columns = $value . "_revenue";
        } else {
            $columns = false;
        }

        return $columns;
    }
}
