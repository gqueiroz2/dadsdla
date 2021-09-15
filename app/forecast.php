<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\salesRep;
use App\brand;
use App\base;
use App\sql;

class forecast extends forecastBase{

    public function limitCheck($select,$regionID,$week){
        if ($regionID == "1") {
            $select .= "AND read_q = \"$week\"";
        }

        return $select;
    }

    public function baseLoad($con,$r,$pr,$cYear,$pYear, $regionID,$salesRepID,$currencyID,$value){
        
        $sr = new salesRep();        
        $br = new brand();
        $base = new base();    
        $sql = new sql();
        $reg = $r;

        $actualMonth = date('n');
        $data = date('Y-m-d');

        $week = $this->weekOfMonth($data);

        /* Verifica se há Forecast prévio salvo */        
        $select = "SELECT oppid,ID,type_of_value,currency_id,submitted FROM forecast WHERE sales_rep_id = \"".$salesRepID[0]."\"  AND month = \"$actualMonth\" AND year = \"$cYear\" AND type_of_forecast = \"AE\"";
        $select = $this->limitCheck($select,$regionID,$week);        
        $select .= "ORDER BY last_modify_date DESC";        
        $result = $con->query($select);
        $from = array("oppid","ID","type_of_value","currency_id", "submitted");
        $save = $sql->fetch($result,$from,$from);        

        /* Lista os clientes do SF e do BTS*/
        $listOfClients = $this->listClientsByAE($con,$sql,$salesRepID,$cYear,$pYear,$regionID);
        
        if(sizeof($listOfClients) == 0){
            return false;
        }

        /*Verifica se existe Forecast anterior para ser carregado*/
        
        if (!$save) {
            $save = false;
            $valueCheck = false;
            $currencyCheck = false;
        }else{
            $submitted = 0;

            for ($s=0; $s < sizeof($save); $s++) { 
                if ($save[$s]['submitted'] == 1) {
                    $submitted = 1;
                }
            }

            $temp[0] = $base->adaptCurrency($con,$pr,$save,$currencyID,$cYear);
            
            $currencyCheck = $temp[0]["currencyCheck"][0];
            $newCurrency = $temp[0]["newCurrency"][0];
            $oldCurrency = $temp[0]["oldCurrency"][0];

            $temp2 = $base->adaptValue($value,$save,$regionID,$listOfClients);

            $valueCheck = $temp2["valueCheck"][0];
            $multValue = $temp2["multValue"][0];
        }

        $regionName = $reg->getRegion($con,array($regionID))[0]['name'];        
        $salesRep = $sr->getSalesRepById($con,$salesRepID);


        $temp = $this->getSeparatedBrands($con,$sql,$salesRepID,$cYear,$regionID);

        $discoveryBrands = $temp['discovery'];
        $sonyBrands = $temp['sony'];               
        $month = $base->getMonth();
        $div = $base->generateDiv($con,$pr,$regionID,array($cYear),$currencyID);
        $currency = $pr->getCurrency($con,array($currencyID))[0]["name"];
        $readable = $this->monthAnalise($base);

        if($regionName == "Brazil"){
            $splitted = $this->isSplitted($con,$sql,$salesRepID,$listOfClients,$cYear,$pYear);
        }else{
            $splitted = false;
        }


        /* Gerando Soma para Canais Discovery */
        for ($b=0; $b < sizeof($discoveryBrands); $b++) {
            for ($m=0; $m < sizeof($month); $m++) {
                $tableDisc[$b][$m] = "ytd";
                $sumDisc[$b][$m] = $this->generateColumns($value,$tableDisc[$b][$m]);
            }
        }

        /* Gerando Soma para Canais Sony */
        for ($s=0; $s < sizeof($sonyBrands); $s++) {
            for ($m=0; $m < sizeof($month); $m++) {
                $tableSony[$s][$m] = "ytd";
                $sumSony[$s][$m] = $this->generateColumns($value,$tableSony[$s][$m]);
            }
        }

        /* Valores do Ano anterior de Discovery */
        
        for ($m=0; $m <sizeof($month) ; $m++) {
            $lastYearDisc[$m] = $this->generateValueWB($con,$sql,$regionID,$pYear,$month[$m][1], $this->generateColumns($value,"ytd") ,"ytd",$value)*$div;
        }
        $lastYearDisc = $this->addQuartersAndTotalOnArray(array($lastYearDisc))[0];

        /* Valores do Ano anterior de Sony */        
        for ($m=0; $m <sizeof($month) ; $m++) {
            $lastYearSony[$m] = $this->generateValueWB($con,$sql,$regionID,$pYear,$month[$m][1], $this->generateColumns($value,"ytd") ,"ytd",$value)*$div;
        }
        $lastYearSony = $this->addQuartersAndTotalOnArray(array($lastYearSony))[0];

        /*Valores de Target para Canais Discovery */
        for ($b=0; $b < sizeof($tableDisc); $b++){ 
            for ($m=0; $m <sizeof($tableDisc[$b]) ; $m++){
                $targetValuesDiscovery[$b][$m] = $this->generateValueS($con,$sql,$regionID,$cYear,$discoveryBrands[$b]['brandID'],$salesRep,$month[$m][1],"value","plan_by_sales",$value)[0]*$div;            
            }
        }
        $mergeTargetDiscovery = $this->mergeTarget($targetValuesDiscovery,$month);
        $targetValuesDiscovery = $mergeTargetDiscovery;

        /*Valores de Target para Canais Sony */
        for ($b=0; $b < sizeof($tableSony); $b++){ 
            for ($m=0; $m <sizeof($tableSony[$b]) ; $m++){
                $targetValuesSony[$b][$m] = $this->generateValueS($con,$sql,$regionID,$cYear,$sonyBrands[$b]['brandID'],$salesRep,$month[$m][1],"value","plan_by_sales",$value)[0]*$div;            
            }
        }

        $mergeTargetSony = $this->mergeTarget($targetValuesSony,$month);
        $targetValuesSony = $mergeTargetSony;
        /* Valores dos Clientes no Ano Atual - Discovery */
        $clientRevenueCYearDisc = $this->revenueByClientAndAE($con,$sql,$base,$pr,$regionID,$cYear,$month,$salesRepID[0],$splitted,$currency,$currencyID,$value,$listOfClients,"cYear",$cYear,$discoveryBrands);
        $clientRevenueCYearTMPDisc = $clientRevenueCYearDisc;
        $clientRevenueCYearDisc = $this->addQuartersAndTotalOnArray($clientRevenueCYearDisc);

        /* Valores dos Clientes no Ano Atual - Sony */
        $clientRevenueCYearSony = $this->revenueByClientAndAE($con,$sql,$base,$pr,$regionID,$cYear,$month,$salesRepID[0],$splitted,$currency,$currencyID,$value,$listOfClients,"cYear",$cYear,$sonyBrands);
        $clientRevenueCYearTMPSony = $clientRevenueCYearSony;
        $clientRevenueCYearSony = $this->addQuartersAndTotalOnArray($clientRevenueCYearSony);

        /* Valores dos Clientes no Ano Anterior - Discovery */
        $clientRevenuePYearDisc = $this->revenueByClientAndAE($con,$sql,$base,$pr,$regionID,$pYear,$month,$salesRepID[0],$splitted,$currency,$currencyID,$value,$listOfClients,"pYear",$cYear,$discoveryBrands);
        $clientRevenuePYearDisc = $this->addQuartersAndTotalOnArray($clientRevenuePYearDisc);

        /* Valores dos Clientes no Ano Anterior - Sony */
        $clientRevenuePYearSony = $this->revenueByClientAndAE($con,$sql,$base,$pr,$regionID,$pYear,$month,$salesRepID[0],$splitted,$currency,$currencyID,$value,$listOfClients,"pYear",$cYear,$sonyBrands);
        $clientRevenuePYearSony = $this->addQuartersAndTotalOnArray($clientRevenuePYearSony);


        /* --------------- VERIFICAR --------------- */
        $tmpDisc = $this->getBookingExecutive($con,$sql,$salesRepID[0],$month,$regionID,$cYear,$value,$currency,$pr,$discoveryBrands);
        $executiveRevenueCYearDisc = $this->addQuartersAndTotal($tmpDisc);
        $executiveRevenuePYearDisc = $this->consolidateAEFcst($clientRevenuePYearDisc,$splitted);

        /* --------------- VERIFICAR --------------- */
        $tmpSony = $this->getBookingExecutive($con,$sql,$salesRepID[0],$month,$regionID,$cYear,$value,$currency,$pr,$sonyBrands);
        $executiveRevenueCYearSony = $this->addQuartersAndTotal($tmpSony);
        $executiveRevenuePYearSony = $this->consolidateAEFcst($clientRevenuePYearSony,$splitted);

        $executiveRevenueCYear = $this->sumNetworks($executiveRevenueCYearDisc,$executiveRevenueCYearSony);
        $executiveRevenuePYear = $this->sumNetworks($executiveRevenuePYearDisc,$executiveRevenuePYearSony);


        if ($save){
            if($submitted == 1){ $sourceSave = "LAST SUBMITTED"; }else{ $sourceSave = "LAST SAVED"; }
            
            $select = array();
            $result = array();
            $from = "value";
            
            if ($regionID == "1") {
                $from2 = array("sales_reps");
                for ($c=0; $c <sizeof($listOfClients); $c++) { 
                    
                    $select2[$c] = "SELECT DISTINCT sales_rep_owner_id AS sales_reps 
                                        FROM sf_pr 
                                        WHERE sales_rep_splitter_id = \"".$salesRepID[0]."\" 
                                        AND client_id = \"".$listOfClients[$c]["clientID"]."\"                                         
                                        AND stage != '6' 
                                        AND stage != '7'";

                    $result2[$c] = $con->query($select2[$c]);
                    $salesReps[$c] = $sql->fetch($result2[$c],$from2,$from2);

                    if ($salesReps[$c]) {
                        $salesRepsOR[$c] = "( f2.sales_rep_id = \"".$salesReps[$c][0]['sales_reps']."\"";
                        if (sizeof($salesReps[$c])>1) {
                            for ($s=1; $s < sizeof($salesReps[$c]) ; $s++) { 
                                $salesRepsOR[$c] .= " OR f2.sales_rep_id = \"".$salesReps[$c][$s]['sales_reps']."\"";
                            }
                        }
                        $salesRepsOR[$c] .= ")";
                    }else{
                        $salesRepsOR[$c] = "";
                    }
                }
            }else{
                $salesRepsOR = "sales_rep_id = \"".$salesRepID[0]."\"";
            }

            $auxYear = date('Y');
            $cMonth = date(('n'));
            for ($c=0; $c < sizeof($listOfClients); $c++) {
                if ($splitted) {
                    if ($splitted[$c]["splitted"]) {
                        $mul = 2;
                    }else{
                        $mul = 1;
                    }
                }else{
                    $mul = 1;
                }
                for ($m=0; $m <12 ; $m++) { 
                    $selectDisc[$c][$m] = "SELECT SUM(value) AS value 
                                            FROM forecast_client f 
                                            LEFT JOIN forecast f2 ON f.forecast_id = f2.ID 
                                            WHERE f.client_id = \"".$listOfClients[$c]["clientID"]."\"
                                            AND f.agency_id = \"".$listOfClients[$c]["agencyID"]."\"
                                            AND f.month = \"".($m+1)."\" 
                                            AND f.company = \"DISC\"
                                            AND f2.month = \"".$cMonth."\"  
                                            AND f2.year = \"".$cYear."\"
                                            AND f2.submitted = \"".$submitted."\"";

                    //echo "<pre>".$selectDisc[$c][$m]."</pre>";

                    if($regionID == "1") {
                        $selectDisc[$c][$m] .= " AND read_q = \"".$week."\" AND ".$salesRepsOR[$c]." ";
                    }else{
                        $selectDisc[$c][$m] .= " AND ".$salesRepsOR." ";
                    }
                    $resultDisc[$c][$m] = $con->query($selectDisc[$c][$m]);
                    $saidaDisc[$c][$m] = $sql->fetchSum($resultDisc[$c][$m],$from);

                    $selectSony[$c][$m] = "SELECT SUM(value) AS value 
                                            FROM forecast_client f 
                                            LEFT JOIN forecast f2 ON f.forecast_id = f2.ID 
                                            WHERE f.client_id = \"".$listOfClients[$c]["clientID"]."\"
                                            AND f.agency_id = \"".$listOfClients[$c]["agencyID"]."\"
                                            AND f.month = \"".($m+1)."\" 
                                            AND f.company = \"SONY\"
                                            AND f2.month = \"".$cMonth."\"  
                                            AND f2.year = \"".$cYear."\"
                                            AND f2.submitted = \"".$submitted."\"";
                    if($regionID == "1") {
                        $selectSony[$c][$m] .= " AND read_q = \"".$week."\" AND ".$salesRepsOR[$c]." ";
                    }else{
                        $selectSony[$c][$m] .= " AND ".$salesRepsOR." ";
                    }
                    $resultSony[$c][$m] = $con->query($selectSony[$c][$m]);
                    $saidaSony[$c][$m] = $sql->fetchSum($resultSony[$c][$m],$from);
                }
                if ($saidaDisc[$c]) {
                    for ($m=0; $m < sizeof($saidaDisc[$c]); $m++) { 
                        $rollingFCSTDisc[$c][$m] = floatval($saidaDisc[$c][$m]['value']);                
                    }
                }else{
                    for ($m=0; $m < 12; $m++) { 
                        $rollingFCSTDisc[$c][$m] = 0;
                    }
                }

                if ($saidaSony[$c]) {
                    for ($m=0; $m < sizeof($saidaSony[$c]); $m++) { 
                        $rollingFCSTSony[$c][$m] = floatval($saidaSony[$c][$m]['value']);                
                    }
                }else{
                    for ($m=0; $m < 12; $m++) { 
                        $rollingFCSTSony[$c][$m] = 0;
                    }
                }

                if ($valueCheck) {
                    for ($m=0; $m < sizeof($rollingFCSTDisc[$c]); $m++) { 
                        $rollingFCSTDisc[$c][$m] = $rollingFCSTDisc[$c][$m]*$multValue[$c];
                    }
                    for ($m=0; $m < sizeof($rollingFCSTSony[$c]); $m++) { 
                        $rollingFCSTSony[$c][$m] = $rollingFCSTSony[$c][$m]*$multValue[$c];
                    }
                }

                if ($currencyCheck) {
                    for ($m=0; $m < sizeof($rollingFCSTDisc[$c]); $m++) { 
                        $rollingFCSTDisc[$c][$m] = ($rollingFCSTDisc[$c][$m]*$newCurrency)/$oldCurrency;
                    }
                    for ($m=0; $m < sizeof($rollingFCSTSony[$c]); $m++) { 
                        $rollingFCSTSony[$c][$m] = ($rollingFCSTSony[$c][$m]*$newCurrency)/$oldCurrency;
                    }
                }
            }

            $tmpRollingFCSTDisc = $this->rollingFCSTByClientAndAE($con,$sql,$base,$pr,$regionID,$cYear,$month,$discoveryBrands,$currency,$currencyID,$value,$listOfClients,$salesRepID[0],$splitted);//Ibms meses fechados e fw total
            $tmpRollingFCSTDisc = $this->addQuartersAndTotalOnArray($tmpRollingFCSTDisc);
            $fcstDisc = $this->calculateForecast($con,$sql,$base,$pr,$regionID,$cYear,$month,$discoveryBrands,$currency,$currencyID,$value,$listOfClients,$salesRepID[0],$rollingFCSTDisc,$splitted,$clientRevenuePYearDisc,$executiveRevenuePYearDisc,$lastYearDisc);
            $fcstAmountByStageDisc = $fcstDisc['fcstAmountByStage'];
            $toRollingFCSTDisc = $fcstDisc['fcstAmount'];
            $tmpRollingFCSTDisc = $this->addFcstWithBooking($tmpRollingFCSTDisc,$toRollingFCSTDisc);//Meses fechados e abertos
            $rollingFCSTDisc = $this->addQuartersAndTotalOnArray($rollingFCSTDisc);
            for ($r=0; $r <sizeof($rollingFCSTDisc) ; $r++) { 
                if ($rollingFCSTDisc[$r][16] == 0) {
                    $rollingFCSTDisc[$r]=$tmpRollingFCSTDisc[$r];
                }
            }
            $rollingFCSTDisc = $this->addClosedFcst($rollingFCSTDisc,$tmpRollingFCSTDisc);
            $rollingFCSTDisc = $this->adjustFCST($rollingFCSTDisc);

            $fcstAmountByStageDisc = $this->addClosed($fcstAmountByStageDisc,$rollingFCSTDisc);//Adding Closed to fcstByStage

            $fcstAmountByStageExDisc = $this->makeFcstAmountByStageEx($fcstAmountByStageDisc,$splitted);
            
            $lastRollingFCSTDisc = $this->rollingFCSTByClientAndAE($con,$sql,$base,$pr,$regionID,$cYear,$month,$discoveryBrands,$currency,$currencyID,$value,$listOfClients,$salesRepID[0],$splitted);//Ibms meses fechados e fw total

            $tmp1 = $this->calculateForecast($con,$sql,$base,$pr,$regionID,$cYear,$month,$discoveryBrands,$currency,$currencyID,$value,$listOfClients,$salesRepID[0],$lastRollingFCSTDisc,$splitted,$clientRevenuePYearDisc,$executiveRevenuePYearDisc,$lastYearDisc);

            $tmp2 = $tmp1['fcstAmount'];

            $lastRollingFCSTDisc = $this->addQuartersAndTotalOnArray($lastRollingFCSTDisc);

            $lastRollingFCSTDisc = $this->addFcstWithBooking($lastRollingFCSTDisc,$tmp2);

            $lastRollingFCSTDisc = $this->adjustFCST($lastRollingFCSTDisc);

            $emptyCheckDisc = $this->checkEmpty($tmp2);


            /////////////////////////////////////////////////////////////


            $tmpRollingFCSTSony = $this->rollingFCSTByClientAndAE($con,$sql,$base,$pr,$regionID,$cYear,$month,$sonyBrands,$currency,$currencyID,$value,$listOfClients,$salesRepID[0],$splitted);//Ibms meses fechados e fw total
            $tmpRollingFCSTSony = $this->addQuartersAndTotalOnArray($tmpRollingFCSTSony);
            $fcstSony = $this->calculateForecast($con,$sql,$base,$pr,$regionID,$cYear,$month,$sonyBrands,$currency,$currencyID,$value,$listOfClients,$salesRepID[0],$rollingFCSTSony,$splitted,$clientRevenuePYearSony,$executiveRevenuePYearSony,$lastYearSony);
            $fcstAmountByStageSony = $fcstSony['fcstAmountByStage'];
            $toRollingFCSTSony = $fcstSony['fcstAmount'];
            $tmpRollingFCSTSony = $this->addFcstWithBooking($tmpRollingFCSTSony,$toRollingFCSTSony);//Meses fechados e abertos
            $rollingFCSTSony = $this->addQuartersAndTotalOnArray($rollingFCSTSony);
            for ($r=0; $r <sizeof($rollingFCSTSony) ; $r++) { 
                if ($rollingFCSTSony[$r][16] == 0) {
                    $rollingFCSTSony[$r]=$tmpRollingFCSTSony[$r];
                }
            }
            $rollingFCSTSony = $this->addClosedFcst($rollingFCSTSony,$tmpRollingFCSTSony);
            $rollingFCSTSony = $this->adjustFCST($rollingFCSTSony);

            $fcstAmountByStageSony = $this->addClosed($fcstAmountByStageSony,$rollingFCSTSony);//Adding Closed to fcstByStage

            $fcstAmountByStageExSony = $this->makeFcstAmountByStageEx($fcstAmountByStageSony,$splitted);
            
            $lastRollingFCSTSony = $this->rollingFCSTByClientAndAE($con,$sql,$base,$pr,$regionID,$cYear,$month,$sonyBrands,$currency,$currencyID,$value,$listOfClients,$salesRepID[0],$splitted);//Ibms meses fechados e fw total

            $tmp1 = $this->calculateForecast($con,$sql,$base,$pr,$regionID,$cYear,$month,$sonyBrands,$currency,$currencyID,$value,$listOfClients,$salesRepID[0],$lastRollingFCSTSony,$splitted,$clientRevenuePYearSony,$executiveRevenuePYearSony,$lastYearSony);

            $tmp2 = $tmp1['fcstAmount'];

            $lastRollingFCSTSony = $this->addQuartersAndTotalOnArray($lastRollingFCSTSony);

            $lastRollingFCSTSony = $this->addFcstWithBooking($lastRollingFCSTSony,$tmp2);

            $lastRollingFCSTSony = $this->adjustFCST($lastRollingFCSTSony);

            $emptyCheckSony = $this->checkEmpty($tmp2);
            
        }else{
            $sourceSave = "DISCOVERY CRM";

            /* BTS meses Fechado para Discovery */
            $rollingFCSTDisc = $this->rollingFCSTByClientAndAE($con,$sql,$base,$pr,$regionID,$cYear,$month,$discoveryBrands,$currency,$currencyID,$value,$listOfClients,$salesRepID[0],$splitted);//Ibms meses fechados e fw total

            /* BTS meses Fechado para Discovery */
            $rollingFCSTSony = $this->rollingFCSTByClientAndAE($con,$sql,$base,$pr,$regionID,$cYear,$month,$sonyBrands,$currency,$currencyID,$value,$listOfClients,$salesRepID[0],$splitted);//Ibms meses fechados e fw total

            
            $fcstDisc = $this->calculateForecast($con,$sql,$base,$pr,$regionID,$cYear,$month,$discoveryBrands,$currency,$currencyID,$value,$listOfClients,$salesRepID[0],$rollingFCSTDisc,$splitted,$clientRevenuePYearDisc,$executiveRevenuePYearDisc,$lastYearDisc);
            $fcstAmountByStageDisc = $fcstDisc['fcstAmountByStage'];
            $toRollingFCSTDisc = $fcstDisc['fcstAmount'];
            $rollingFCSTDisc = $this->addQuartersAndTotalOnArray($rollingFCSTDisc);
            $rollingFCSTDisc = $this->addFcstWithBooking($rollingFCSTDisc,$toRollingFCSTDisc);//Meses fechados e abertos
            $rollingFCSTDisc = $this->adjustFCST($rollingFCSTDisc);
            $fcstAmountByStageDisc = $this->addClosed($fcstAmountByStageDisc,$rollingFCSTDisc);//Adding Closed to fcstByStage
            $emptyCheckDisc = $this->checkEmpty($toRollingFCSTDisc);
            $lastRollingFCSTDisc = $rollingFCSTDisc;
            

            $fcstSony = $this->calculateForecast($con,$sql,$base,$pr,$regionID,$cYear,$month,$sonyBrands,$currency,$currencyID,$value,$listOfClients,$salesRepID[0],$rollingFCSTSony,$splitted,$clientRevenuePYearSony,$executiveRevenuePYearSony,$lastYearDisc);

            $fcstAmountByStageSony = $fcstSony['fcstAmountByStage'];
            $toRollingFCSTSony = $fcstSony['fcstAmount'];
            $rollingFCSTSony = $this->addQuartersAndTotalOnArray($rollingFCSTSony);
            $rollingFCSTSony = $this->addFcstWithBooking($rollingFCSTSony,$toRollingFCSTSony);//Meses fechados e abertos
            $rollingFCSTSony = $this->adjustFCST($rollingFCSTSony);
            $fcstAmountByStageSony = $this->addClosed($fcstAmountByStageSony,$rollingFCSTSony);//Adding Closed to fcstByStage
            $emptyCheckSony = $this->checkEmpty($toRollingFCSTSony);
            $lastRollingFCSTSony = $rollingFCSTSony;
        }
        
        $fcstAmountByStageDisc = $this->addLost($con,$listOfClients,$fcstAmountByStageDisc,$value,$div);
        $fcstAmountByStageExDisc = $this->makeFcstAmountByStageEx($fcstAmountByStageDisc,$splitted);

        $fcstAmountByStageSony = $this->addLost($con,$listOfClients,$fcstAmountByStageSony,$value,$div);
        $fcstAmountByStageExSony = $this->makeFcstAmountByStageEx($fcstAmountByStageSony,$splitted);
        
        $fcstAmountByStage = $this->sumDiscAndSonyTA($fcstAmountByStageDisc,$fcstAmountByStageSony);
        $fcstAmountByStageEx = $this->sumDiscAndSonyMA($fcstAmountByStageExDisc,$fcstAmountByStageExSony);

        $rollingFCST = $this->sumDiscAndSonyMA($rollingFCSTDisc,$rollingFCSTSony);
        $executiveRevenueCYear = $this->sumDiscAndSonyA($executiveRevenueCYearDisc,$executiveRevenueCYearSony);
        $targetValues = $this->sumDiscAndSonyA($targetValuesDiscovery,$targetValuesSony);

        $executiveRF = $this->consolidateAEFcst($rollingFCST,$splitted);
        $executiveRF = $this->closedMonthEx($executiveRF,$executiveRevenueCYear);
        $executiveRF = $this->addBookingRollingFCST($executiveRF,$executiveRevenueCYear);

        $pending = $this->subArrays($executiveRF,$executiveRevenueCYear);

        $executiveRFDisc = $this->consolidateAEFcst($rollingFCSTDisc,$splitted);
        $executiveRFDisc = $this->closedMonthEx($executiveRFDisc,$executiveRevenueCYearDisc);
        $executiveRFDisc = $this->addBookingRollingFCST($executiveRFDisc,$executiveRevenueCYearDisc);

        $executiveRFSony = $this->consolidateAEFcst($rollingFCSTSony,$splitted);
        $executiveRFSony = $this->closedMonthEx($executiveRFSony,$executiveRevenueCYearSony);
        $executiveRFSony = $this->addBookingRollingFCST($executiveRFSony,$executiveRevenueCYearSony);

        $RFvsTarget = $this->subArrays($executiveRF,$targetValues);
        $RFvsTargetDisc = $this->subArrays($executiveRFDisc,$targetValuesDiscovery);
        $RFvsTargetSony = $this->subArrays($executiveRFSony,$targetValuesSony);
        $targetAchievement = $this->divArrays($executiveRF,$targetValues);
        $targetAchievementDisc = $this->divArrays($executiveRFDisc,$targetValuesDiscovery);
        $targetAchievementSony = $this->divArrays($executiveRFDisc,$targetValuesSony);               
        
        $pendingDisc = $this->subArrays($executiveRFDisc,$executiveRevenueCYearDisc);
        $pendingSony = $this->subArrays($executiveRFSony,$executiveRevenueCYearSony);        
        
        $currencyName = $pr->getCurrency($con,array($currencyID))[0]['name'];
        $fcstAmountByStage = $this->adjustFcstAmountByStage($fcstAmountByStage);
        $fcstAmountByStageEx = $this->adjustFcstAmountByStageEx($fcstAmountByStageEx);

        $fcstAmountByStageDisc = $this->adjustFcstAmountByStage($fcstAmountByStageDisc);
        $fcstAmountByStageExDisc = $this->adjustFcstAmountByStageEx($fcstAmountByStageExDisc);

        $fcstAmountByStageSony = $this->adjustFcstAmountByStage($fcstAmountByStageSony);
        $fcstAmountByStageExSony = $this->adjustFcstAmountByStageEx($fcstAmountByStageExSony);
        $brandsPerClient = $this->getBrandsClient($con, $listOfClients, $salesRep);

        if ($value == 'gross') { $valueView = 'Gross'; }
        elseif($value == 'net'){ $valueView = 'Net'; }
        else{ $valueView = 'Net Net'; }

        //$secondary = $listOfClients;

        //$nSecondary = $this->mergeSecondary($secondary,$rollingFCST,$lastRollingFCST,$clientRevenueCYear,$clientRevenuePYear,$fcstAmountByStage,$revenueDiscovery,$revenueDiscoveryPYear,$revenueSony,$revenueSonyPYear);
        
        $rtr = array(   
                        "cYear" => $cYear,
                        "pYear" => $pYear,
                        "readable" => $readable,

                        "salesRep" => $salesRep[0],
                        "client" => $listOfClients,
                        "splitted" => $splitted,
                        
                        "targetValuesDiscovery" => $targetValuesDiscovery,
                        "targetValuesSony" => $targetValuesSony,
                        "targetValues" => $targetValues,

                        "rollingFCSTDisc" => $rollingFCSTDisc,
                        "rollingFCSTSony" => $rollingFCSTSony,

                        "lastRollingFCSTDisc" => $lastRollingFCSTDisc, 
                        "lastRollingFCSTSony" => $lastRollingFCSTSony, 

                        "clientRevenueCYearDisc" => $clientRevenueCYearDisc, 
                        "clientRevenueCYearSony" => $clientRevenueCYearSony, 

                        "clientRevenuePYearDisc" => $clientRevenuePYearDisc, 
                        "clientRevenuePYearSony" => $clientRevenuePYearSony, 

                        "executiveRF" => $executiveRF,
                        "executiveRFDisc" => $executiveRFDisc,
                        "executiveRFSony" => $executiveRFSony,

                        "executiveRevenuePYearDisc" => $executiveRevenuePYearDisc,
                        "executiveRevenuePYearSony" => $executiveRevenuePYearSony,
                        "executiveRevenuePYear" => $executiveRevenuePYear,
                        
                        "executiveRevenueCYearDisc" => $executiveRevenueCYearDisc,
                        "executiveRevenueCYearSony" => $executiveRevenueCYearSony,
                        "executiveRevenueCYear" => $executiveRevenueCYear,

                        "pending" => $pending,
                        "pendingDisc" => $pendingDisc,
                        "pendingSony" => $pendingSony,
                        "RFvsTarget" => $RFvsTarget,
                        "RFvsTargetDisc" => $RFvsTargetDisc,
                        "RFvsTargetSony" => $RFvsTargetSony,
                        "targetAchievementDisc" => $targetAchievementDisc,
                        "targetAchievementSony" => $targetAchievementSony,
                        "targetAchievement" => $targetAchievement,
                    
                        "currency" => $currency, 
                        "value" => $value,
                        "region" => $regionID,

                        "currencyName" => $currencyName,
                        "valueView" => $valueView,
                        //"currency" => $currencyName,
                        "value" => $valueView,

                        "fcstAmountByStageDisc" => $fcstAmountByStageDisc, 
                        "fcstAmountByStageSony" => $fcstAmountByStageSony, 
                        "fcstAmountByStage" => $fcstAmountByStage, 

                        "fcstAmountByStageExDisc" => $fcstAmountByStageExDisc,
                        "fcstAmountByStageExSony" => $fcstAmountByStageExSony,
                        "fcstAmountByStageEx" => $fcstAmountByStageEx,

                        "brandsPerClient" => $brandsPerClient,
                        "sourceSave" => $sourceSave,

                        "emptyCheckDisc" => $emptyCheckDisc,
                        "emptyCheckSony" => $emptyCheckSony,
                        //"nSecondary" => $nSecondary,
                    );

        return $rtr;
        
    }


}
