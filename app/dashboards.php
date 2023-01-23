<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\rank;
use App\sql;
use App\subRankings;
use App\brand;
use App\rankings;
class dashboards extends rank{

    public function someTotals($something){
        $sumChild = 0.0;
        for ($s0=0; $s0 < sizeof($something['child']); $s0++) { 
            $sumChild += $something['child'][$s0]['total'];
        }
        $temp0 = array('clientID' => -1,'client' => 'TOTAL','total' => $sumChild );
        array_push($something['child'], $temp0);

        $sumMonth = 0.0;
        for ($s1=0; $s1 < sizeof($something['byMonth']); $s1++) { 
            $sumMonth += $something['byMonth'][$s1]['value'];
        }

        $temp1 = array('month' => 'TOTAL','value' => $sumMonth);

        array_push($something['byMonth'], $temp1);

        return $something;
    }

    public function clearBrands($brand){ 
        $size = sizeof($brand);

        for ($b=0; $b < $size; $b++) { 
            if($brand[$b]['value'] <= 0){
                unset($brand[$b]);
            }
        }

        $brand = array_values($brand);

        return $brand;
    }

    public function excelBV($base,$mc,$handle,$year){

        $child = $handle['child'];

        $byBrand = $this->clearBrands($handle['byBrand']);

        $byMonth = $handle['byMonth'];
        for ($b=0; $b <sizeof($byBrand) ; $b++) { 
            $brandColor[$b] = $base->getBrandColor($byBrand[$b]['brand']);
            $brandTextColor[$b] = $base->getBrandTextColor($byBrand[$b]['brand']);
            $brandNames[$b] = $byBrand[$b]['brand'];
        }
        
        $childChart = $mc->bvChild($child,$year);
        
        $byMonthChart = $mc->bvMonth($byMonth,$year);
        $byBrandChart = $mc->bvBrand($byBrand,$year);

        $charts = array(
                            'child' => $childChart,
                            'byMonth' => $byMonthChart,
                            'byBrand' => array(
                                                'graph' => $byBrandChart,
                                                'brandColor' => $brandColor,
                                                'brandTextColor' => $brandTextColor,
                                                'brandNames' => $brandNames
                                              )
                        );

        return $charts;
        
    }

    public function bvAnalisis($current,$band){

        $currentVal = $current['total'];

        if($band){
            //NOW
            if($currentVal < $band[0]['fromValue']){
                $currentBand = 0;
                $currentPercentage = 0;
                $currentBV = $currentVal*$currentPercentage;
                $pivot = -1; 
            }else{
                for ($b=0; $b < sizeof($band); $b++) { 
                    if($currentVal < $band[$b]['toValue'] && $currentVal > $band[$b]['fromValue']){
                        $currentBand = $band[$b]['toValue']*1;
                        $currentPercentage = $band[$b]['percentage']*1;
                        $currentBV = $currentVal*$currentPercentage;
                        $pivot = $b;
                        break;
                    }
                }
            }

            //NEXT
            if( isset($band[($pivot+1)]) ){
                $nextBandVal = $band[($pivot+1)]['fromValue']*1;
                $nextBandBV = $nextBandVal*$band[($pivot+1)]['percentage'];
                $nextBandDiff = $band[($pivot+1)]['fromValue'] - $currentVal;
                $nextBandPercentage = $band[($pivot+1)]['percentage']*100;
            }else{
                // FAIXA MAXIMA
            }

            //TOP
            $maxBandVal = $band[(sizeof($band)-1)]['fromValue'];
            $maxBandPercentage = $band[(sizeof($band)-1)]['percentage']*100;
            $maxBandDiff = $band[(sizeof($band)-1)]['fromValue'] - $currentVal;

            if($currentVal > $maxBandVal){
                $maxBandCurrentVal = $currentVal*1;
            }else{
                $maxBandCurrentVal = $maxBandVal*1;
            }

            $maxBandBV = $maxBandVal*($maxBandPercentage/100);
        }else{

            $currentBand = false;
            $currentPercentage = false;
            $currentBV = false;
            $nextBandVal = false;
            $nextBandDiff = false;
            $nextBandPercentage = false;
            $nextBandBV = false;
            $maxBandCurrentVal = false;
            $maxBandPercentage = false;
            $maxBandBV = false;
            $maxBandDiff = false;
        }
        
        $rtr = array(
                        'currentVal' => $currentVal,

                        'currentBand' => $currentBand,
                        'currentPercentage' => $currentPercentage,
                        'currentBV' => $currentBV,

                        'nextBandVal' => $nextBandVal,
                        'nextBandDiff' => $nextBandDiff,
                        'nextBandPercentage' => $nextBandPercentage,
                        'nextBandBV' => $nextBandBV,

                        'maxBandCurrentVal' => $maxBandCurrentVal,
                        'maxBandPercentage' => $maxBandPercentage,
                        'maxBandBV' => $maxBandBV,
                        'maxBandDiff' => $maxBandDiff
                    );

        return($rtr);
        
    }

    public function bandsBV($con,$p,$type,$regionID,$currency,$value,$agencyGroup,$years){
        $sql = new sql();
        $sr = new subRankings();
        $table = 'bv_band';
        $currencyName = $p->getCurrency($con, array($currency))[0]['name'];
        if($currencyName == "USD"){ $pRate = $p->getPRateByRegionAndYear($con, array($regionID), array($years[0]));}else{ $pRate = 1.0;}        

        if($value == "gross"){ $column = "gross_revenue"; }else{$column = "net_revenue";}
        $from = array('agencyGroup','fromValue','toValue','percentage');

        for ($y=0; $y < sizeof($years); $y++) { 
            $select[$y] = "SELECT ag.name AS 'agencyGroup',
                              b.from_value AS 'fromValue',
                              b.to_value AS 'toValue',
                              b.percentage AS 'percentage'
                              FROM $table b
                              LEFT JOIN agency_group ag ON ag.ID = b.agency_group_id
                              WHERE (agency_group_id = '$agencyGroup')
                              AND (year = '".$years[$y]."')



                      ";

            $res[$y] = $con->query($select[$y]);
            $bands[$y] = $sql->fetch($res[$y],$from,$from);

            if($bands[$y]){
                for ($w=0; $w < sizeof($bands[$y]); $w++) { 
                    $bands[$y][$w]['fromValue'] /= $pRate;
                    $bands[$y][$w]['toValue'] /= $pRate;
                }
            }
        }

        return $bands;
    }

    public function shareLastYear($con,$p,$regionID,$years){

        $sql = new sql();

        $year = intval($years[0]) - 1;

        $select = "SELECT
                        SUM(gross) AS 'revenue',
                        month AS 'month'
                        FROM cmaps 
                        WHERE(year = '$year')
                        GROUP BY month
                  ";

        $from = array('month','revenue');

        $sql = new sql();

        $res = $con->query($select);

        $list = $sql->fetch($res,$from,$from);

        $sum = 0.0;

        for ($l=0; $l < sizeof($list); $l++) { 
            $sum += $list[$l]['revenue'];
        }

        for ($l=0; $l < sizeof($list); $l++) { 
            $share[$l]['percentage'] = $list[$l]['revenue']/$sum;
            $share[$l]['months'] = $this->months[$l];
        }

        return $share;

    }

    public function forecastBV($con,$p,$type,$regionID,$currency,$value,$baseFilter,$years,$startMonthFcst){
        
        $share = $this->shareLastYear($con,$p,$regionID,$years);

        $year = $years[0];
        $sql = new sql();
        $sr = new subRankings();
        $table = 'sf_pr';
        $currencyName = $p->getCurrency($con, array($currency))[0]['name'];
        if($currencyName == "USD"){ $pRate = 1.0; }else{ $pRate = $p->getPRateByRegionAndYear($con, array($regionID), array($years[0]));}        
        if($value == "gross"){ $column = "gross_revenue"; }else{$column = "net_revenue";}

        $select = "SELECT 
                        c.name AS 'client',
                        from_date AS 'fromDate',
                        to_date AS 'toDate',
                        SUM($column) AS 'revenue'                      
                        FROM $table t
                           LEFT JOIN region r ON r.ID = t.region_id
                           LEFT JOIN client c ON c.ID = t.client_id
                           LEFT JOIN agency a ON a.ID = t.agency_id
                           LEFT JOIN agency_group ag ON ag.ID = a.agency_group_id
                           WHERE(agency_group_id IN ('$baseFilter'))
                           AND ( year_from = $year )
                           AND ( year_to = $year )
                           AND ( stage NOT IN ('5','6') )
                           AND (from_date >= $startMonthFcst)                           
                           GROUP BY client
        ";
        $from = array("client",'fromDate','toDate',"revenue");

        $res = $con->query($select);
        
        $fcst = $sql->fetch($res,$from,$from);
        if($fcst){
            $fcst = $this->multPRate($fcst,$pRate);
            $deal = $this->dealWithFcst($fcst,$share);
            $final = $this->addTotal($deal);            
        }else{
            $final = false;
        }
        return $final;
    }

    public function multPRate($fcst,$pRate){

        for ($f=0; $f < sizeof($fcst); $f++) { 
            $fcst[$f]['revenue'] *= $pRate;
        }

        return $fcst;
    }

    public function addTotal($deal){

        $currentMonth = intval(date('m')) - 1;

        $final['client'] = "Total";
        $final['fromDate'] = false;
        $final['toDate'] = false;
        $final['revenue'] = 0.0;
        for ($i=$currentMonth; $i < 12; $i++) { 
            $final['split'][$i] = 0.0;
        }

        for ($d=0; $d < sizeof($deal); $d++) { 
            $final['revenue'] += $deal[$d]['revenue'];
            for ($e=$currentMonth; $e < 12; $e++) { 
                $final['split'][$e] += $deal[$d]['split'][$e];
            }
        }

        array_push($deal, $final);
        
        return $deal;


    }

    public function dealWithFcst($fcst,$share){
        
        $currentMonth = intval(date('m')) - 1;

        if($fcst){        
            for ($f=0; $f < sizeof($fcst); $f++) { 

                $months[$f] = $this->handleMonths($fcst[$f]['fromDate'],$fcst[$f]['toDate']); 
                $shareExp[$f] = array();

                if(sizeof($months[$f]) > 1){

                    for ($m=0; $m < sizeof($months[$f]); $m++) { 
                        for ($s=0; $s < sizeof($share); $s++) { 
                            if($share[$s]['months'] == $months[$f][$m]){
                                array_push($shareExp[$f], $share[$s]['percentage']);
                            }
                        }
                    }

                    $totShareExp[$f] = 0.0;

                    for ($s=0; $s < sizeof($shareExp[$f]); $s++) { 
                        $totShareExp[$f] += $shareExp[$f][$s];
                    }

                    for ($s=0; $s < sizeof($shareExp[$f]); $s++) { 
                        $shareExpDiv[$f][$s] = $shareExp[$f][$s]/$totShareExp[$f];
                        $value[$f][$s] = $fcst[$f]['revenue']*$shareExpDiv[$f][$s];
                    }

                    for ($m=0; $m < sizeof($months[$f]); $m++) { 
                        $temp[$f][$m]['month'] = intval($months[$f][$m]);
                        $temp[$f][$m]['revenue'] = $value[$f][$m];
                    }

                    for ($x=$currentMonth; $x < 12; $x++) { 
                        $revenue[$f][$x] = 0.0;                            
                        for ($m=0; $m < sizeof($months[$f]); $m++) { 
                            if( ($x+1) == intval($months[$f][$m]) ){                            
                                $revenue[$f][$x] = $temp[$f][$m]['revenue'];
                                break;
                            }
                        }   
                    }

                }else{
                    $temp[$f][0]['month'] = intval($months[$f][0]);
                    $temp[$f][0]['revenue'] = doubleval($fcst[$f]['revenue']);

                    for ($x=$currentMonth; $x < 12; $x++) { 
                        $revenue[$f][$x] = 0.0;                            
                        for ($m=0; $m < sizeof($months[$f]); $m++) { 
                            if( ($x+1) == intval($months[$f][$m]) ){                            
                                $revenue[$f][$x] = $temp[$f][$m]['revenue'];
                                break;
                            }
                        }   
                    }
                }

                $fcst[$f]['split'] = $revenue[$f];
            }
        }else{
            $fcst = false;
        }
        
        return $fcst;
    }

    public function handleMonths($from,$to){
        if($from == $to){
            $arr = array($from);
        }else{
            $arr = array();            
            $cc = $from;
            while ($cc <= $to) {
                array_push($arr,$cc);
                $cc++;
            }
        }

        return $arr;
    }

    public function analisisPreviousYear($con,$p,$type,$regionID,$currency,$value,$baseFilter,$years,$kind,$bands){

        $current = $this->infoPreviousYear($con,$p,$type,$regionID,$currency,$value,$baseFilter,$years,$kind);
        $currentVal = $current['total'];

        if(isset($bands[1]) && $bands[1]){
            $pBand = $bands[1];
            if($currentVal < $pBand[0]['fromValue']){
                $currentBand = 0;
                $currentPercentage = 0;
                $currentBV = $currentVal*$currentPercentage;
                $pivot = -1; 
            }else{
                for ($b=0; $b < sizeof($pBand); $b++) { 
                    if($currentVal < $pBand[$b]['toValue'] && $currentVal > $pBand[$b]['fromValue']){
                        $currentBand = $pBand[$b]['toValue']*1;
                        $currentPercentage = $pBand[$b]['percentage']*1;
                        $currentBV = $currentVal*$currentPercentage;
                        $pivot = $b;
                        break;
                    }
                }
            }
            $pa = array(
                        'finalValue' => $currentVal,
                        'finalBand' => $currentBand,
                        'finalPercentage' => $currentPercentage,
                        'finalBV' => $currentBV
            );

        }else{
            $pa = false;
        }

        return $pa;

    }

    public function infoPreviousYear($con,$p,$type,$regionID,$currency,$value,$baseFilter,$years,$kind){
        $secondaryFilter = false;
        $sr = new subRankings();
        $table = $kind;
        $currencyName = $p->getCurrency($con, array($currency))[0]['name'];
        if($currencyName == "USD"){ $pRate = $p->getPRateByRegionAndYear($con, array($regionID), array($years[0])); }else{ $pRate = 1.0;}
        
        if($value == "gross"){
            $column = "gross";
        }else{
            $column = "net";  
        }        

        switch ($type) {
            default:
                if($type == "agency"){          
                    $current = $this->current($con,"agency",$type,$p,$sr,$table,$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency);/*
                    $child = $this->child($con,"client","child",$type,$p,$sr,$table,$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency);
                    $byBrand = $this->byBrand($con,$type,$p,$sr,$table,$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency);
                    $byMonth = $this->byMonth($con,$type,$p,$sr,$table,$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency);*/
                }else{
                    $current = $this->current($con,"agencyGroup",$type,$p,$sr,$table,$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency);/*
                    $child = $this->child($con,"agency","child",$type,$p,$sr,$table,$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency);
                    $byBrand = $this->byBrand($con,$type,$p,$sr,$table,$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency);
                    $byMonth = $this->byMonth($con,$type,$p,$sr,$table,$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency);*/
                    
                }

                break;
        }

        return $current;

    }

    public function mountBV($con,$p,$type,$regionID,$currency,$value,$baseFilter,$years,$kind){
        $secondaryFilter = false;
        $sr = new subRankings();
        $table = $kind;
        $currencyName = $p->getCurrency($con, array($currency))[0]['name'];
        if($currencyName == "USD"){ $pRate = $p->getPRateByRegionAndYear($con, array($regionID), array($years[0])); }else{ $pRate = 1.0;}
        
        if($value == "gross"){
            $column = "gross";
        }else{
            $column = "net";  
        }        

        switch ($type) {
            default:
                if($type == "agency"){          
                    $current = $this->current($con,"agency",$type,$p,$sr,$table,$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency);
                    $child = $this->child($con,"client","child",$type,$p,$sr,$table,$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency);
                    $byBrand = $this->byBrand($con,$type,$p,$sr,$table,$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency);
                    $byMonth = $this->byMonth($con,$type,$p,$sr,$table,$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency);
                }else{
                    $current = $this->current($con,"agencyGroup",$type,$p,$sr,$table,$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency);
                    $child = $this->child($con,"agency","child",$type,$p,$sr,$table,$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency);
                    $byBrand = $this->byBrand($con,$type,$p,$sr,$table,$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency);
                    $byMonth = $this->byMonth($con,$type,$p,$sr,$table,$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency);
                    
                }

                break;
        }
       
        $rtr = array( "current" => $current,
                      "child" => $child,                      
                      "byBrand" => $byBrand,
                      "byMonth" => $byMonth
                    );

        return $rtr;

    }

	public function mount($con,$p,$type,$regionID,$currency,$value,$baseFilter,$secondaryFilter,$years){
		$sr = new subRankings();
	   	
	   	/*DEFINIR SE PARA BRASIL PEGA CMAPS OU NAO*/
        $currencyName = $p->getCurrency($con, array($currency))[0]['name'];
              

        if ($regionID == "1") {
            if ($currencyName == "USD") {
                 $pRate = $p->getPRateByRegionAndYear($con, array($regionID), array($years[0]));
            }else{
               $pRate = 1.0;
            }
            if($value == "gross"){
                $column = "gross_value";
                $columnD = "gross_value";
            }else{
                $column = "net_value";  
                $columnD = "net_value";   
            }  

            //if ($years[0] >= 2022 || $years[1] >= 2022 || $years[2] >= 2022 ) {
                switch ($type) {
                    case 'client':
                        $last3YearsRoot = $this->last3Years($con,"agency","root",$type,$p,$sr,'wbd',$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency);
                        $last3YearsChild = $this->last3Years($con,"client","child",$type,$p,$sr,'wbd',$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency);
                        $last3YearsByMonth = $this->last3YearsByMonth($con,$type,$p,$sr,'wbd',$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency,$columnD);
                        $last3YearsByBrand = $this->last3YearsByBrand($con,$type,$p,$sr,'wbd',$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency,$columnD);
                        $last3YearsByProduct = $this->last3YearsByProduct($con,$type,$p,$sr,'ytd',$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency);
                        break;

                    case 'agency':          
                            $last3YearsRoot = $this->last3Years($con,"agency","root",$type,$p,$sr,'wbd',$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency);
                            $last3YearsChild = $this->last3Years($con,"client","child",$type,$p,$sr,'wbd',$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency);
                            $last3YearsByMonth = $this->last3YearsByMonth($con,$type,$p,$sr,'wbd',$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency,$columnD);
                            $last3YearsByBrand = $this->last3YearsByBrand($con,$type,$p,$sr,'wbd',$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency,$columnD);
                            $last3YearsByProduct = $this->last3YearsByProduct($con,$type,$p,$sr,'ytd',$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency);
                            break;
                    case 'agencyGroup':
                            $last3YearsRoot = $this->last3Years($con,"agencyGroup","root",$type,$p,$sr,'wbd',$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency);
                            $last3YearsChild = $this->last3Years($con,"agency","child",$type,$p,$sr,'wbd',$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency);
                            $last3YearsByMonth = $this->last3YearsByMonth($con,$type,$p,$sr,'wbd',$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency,$columnD);
                            $last3YearsByBrand = $this->last3YearsByBrand($con,$type,$p,$sr,'wbd',$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency,$columnD);
                            $last3YearsByProduct = $this->last3YearsByProduct($con,$type,$p,$sr,'ytd',$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency);                       

                        break;
                    default:
                        $last3YearsRoot = false;
                        $last3YearsChild = false;
                        $last3YearsByMonth = false;
                        $last3YearsByBrand = false;
                        $last3YearsByProduct = false;
                        break;
                }
           // }
        }else{
            if ($currencyName == "USD") {
                $pRate = 1.0;
            }else{
                $pRate = $p->getPRateByRegionAndYear($con, array($regionID), array($years[0]));
            }
            if($value == "gross"){
                $column = "gross_revenue_prate";
                $columnD = "gross_revenue";
            }else{
                $column = "net_revenue_prate";  
                $columnD = "net_revenue";   
            }  
            switch ($type) {
                case 'client':
                    $last3YearsRoot = $this->last3Years($con,"agency","root",$type,$p,$sr,'ytd',$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency);
                    $last3YearsChild = $this->last3Years($con,"client","child",$type,$p,$sr,'ytd',$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency);
                    $last3YearsByMonth = $this->last3YearsByMonth($con,$type,$p,$sr,'ytd',$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency,$columnD);
                    $last3YearsByBrand = $this->last3YearsByBrand($con,$type,$p,$sr,'ytd',$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency,$columnD);
                    $last3YearsByProduct = $this->last3YearsByProduct($con,$type,$p,$sr,'ytd',$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency);
                    break;

                case 'agency':          
                        $last3YearsRoot = $this->last3Years($con,"agency","root",$type,$p,$sr,'ytd',$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency);
                        $last3YearsChild = $this->last3Years($con,"client","child",$type,$p,$sr,'ytd',$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency);
                        $last3YearsByMonth = $this->last3YearsByMonth($con,$type,$p,$sr,'ytd',$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency,$columnD);
                        $last3YearsByBrand = $this->last3YearsByBrand($con,$type,$p,$sr,'ytd',$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency,$columnD);
                        $last3YearsByProduct = $this->last3YearsByProduct($con,$type,$p,$sr,'ytd',$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency);
                        break;
                case 'agencyGroup':
                        $last3YearsRoot = $this->last3Years($con,"agencyGroup","root",$type,$p,$sr,'ytd',$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency);
                        $last3YearsChild = $this->last3Years($con,"agency","child",$type,$p,$sr,'ytd',$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency);
                        $last3YearsByMonth = $this->last3YearsByMonth($con,$type,$p,$sr,'ytd',$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency,$columnD);
                        $last3YearsByBrand = $this->last3YearsByBrand($con,$type,$p,$sr,'ytd',$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency,$columnD);
                        $last3YearsByProduct = $this->last3YearsByProduct($con,$type,$p,$sr,'ytd',$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency);
                    

                    break;
                default:
                    $last3YearsRoot = false;
                    $last3YearsChild = false;
                    $last3YearsByMonth = false;
                    $last3YearsByBrand = false;
                    $last3YearsByProduct = false;
                    break;
            }

        }
	    
	    $rtr = array( "last3YearsRoot" => $last3YearsRoot,
	    			  "last3YearsChild" => $last3YearsChild,
	    			  "last3YearsByMonth" => $last3YearsByMonth,
	    			  "last3YearsByBrand" => $last3YearsByBrand,
	    			  "last3YearsByProduct" => $last3YearsByProduct,

	                );

	    return $rtr;

	}

	public function last3YearsByProduct($con,$type,$p,$sr,$table,$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency){
		$sql = new sql(); 	

        if (is_array($secondaryFilter)) {
            $secondaryFilter = implode(",", $secondaryFilter);
        }

		$products = $this->getProducts($con,$table,$type,$baseFilter);

		for ($y=0; $y < sizeof($years); $y++) { 
			for ($p=0; $p < sizeof($products); $p++) { 
				if($type == "agencyGroup"){
		    		$smt = "agency_group";
		    		$join = "LEFT JOIN agency a ON a.ID = y.agency_id";
		    		$where = "WHERE(year = \"".$years[$y]."\")
		    					AND (client_product = \"".$products[$p]['product']."\")
		    					AND ( ".$smt."_id = \"".$baseFilter->id."\")
                                AND ( client_id = \"".$products[$p]['clientID']."\")
                                AND (agency_id IN (".$secondaryFilter."))";
		    	}elseif ($type == "agency") {
                    $join = false;
                    $where = "WHERE(year = \"".$years[$y]."\")
                                AND (client_product = \"".$products[$p]['product']."\")
                                AND ( ".$type."_id = \"".$baseFilter->id."\")
                                AND ( client_id = \"".$products[$p]['clientID']."\")
                                AND (client_id IN (".$secondaryFilter."))";
                }else{
		    		$join = false;
		    		$where = "WHERE(year = \"".$years[$y]."\")
		    					AND (client_product = \"".$products[$p]['product']."\")
		    					AND ( ".$type."_id = \"".$baseFilter->id."\")
                                AND ( client_id = \"".$products[$p]['clientID']."\")
                                AND (agency_id IN (".$secondaryFilter."))";
		    	}

				$some[$y][$p] = "SELECT SUM($column) AS mySum 
		    					FROM $table y 
		    					$join
		    					$where";


		    	$res[$y][$p] = $con->query($some[$y][$p]);
		    	$from = array("mySum");
		    	$values[$y][$p] = $sql->fetch($res[$y][$p],$from,$from)[0]['mySum']*$pRate;
			}
		}

		$rtr = array("products" => $products , "values" => $values);
		return $rtr;
	}

    public function child($con,$what,$kind,$type,$p,$sr,$table,$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency){
        $sql = new sql();    

        $brands = $this->getBrands($con);
        $months = $this->months;
        $cr = $p->getCurrency($con, array($currency));
        $null = null;

        if($kind == "root"){
            if($type == "agencyGroup")
                $somekind = $sr->getAllValues($con,$table,$type,$type, $brands, $regionID, $value, $years, $months, $cr, $null, "", "agency", $secondaryFilter);
            else
                $somekind = $sr->getAllValues($con,$table,$type,$type, $brands, $regionID, $value, $years,$months,$cr, $null, null, null, $secondaryFilter);
        }else{
            $filter = 'agencyGroup';

            if($type == "agency")
                $auxFilter = $baseFilter;
            else
                $auxFilter = "teste";            

            $values = $sr->getNewSubResults($con, $brands, $type, $regionID, $value, $cr, $months, $years, $filter, $auxFilter, $secondaryFilter,$baseFilter)[0];
            
        }
        
        return $values;
    }

    public function byMonth($con,$type,$p,$sr,$table,$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency){
        $sql = new sql(); 
        $months = $this->months; 

        if (is_array($secondaryFilter)) {
            $secondaryFilter = implode(",", $secondaryFilter);
        }

        $year = $years[0];

        for ($m=0; $m < sizeof($months); $m++) { 
            
            if($type == "agencyGroup"){
                $smt = "agency_group";
                $join = "LEFT JOIN agency a ON a.ID = y.agency_id";
                $where = "WHERE(year = \"".$year."\")
                            AND (month = \"".$months[$m]."\")
                            AND ( ".$smt."_id = \"".$baseFilter."\")";
                
            }elseif ($type == "agency") {
                $join = false;
                $where = "WHERE(year = \"".$year."\")
                            AND (month = \"".$months[$m]."\")
                            AND ( ".$type."_id = \"".$baseFilter."\")";
                
            }else{
                $join = false;
                $where = "WHERE(year = \"".$year."\")
                            AND (month = \"".$months[$m]."\")
                            AND ( ".$type."_id = \"".$baseFilter."\")";
                
            }

            $some[$m] = "SELECT SUM($column) AS mySum 
                            FROM $table y
                            $join
                            $where";

            $res[$m] = $con->query($some[$m]);

            $from = array("mySum");
            $values[$m]['month'] = $months[$m];
            $values[$m]['value'] = doubleval($sql->fetch($res[$m],$from,$from)[0]['mySum']);//*$pRate;

            $values[$m]['value'] /= $pRate;


        }

        return $values;
    }

	public function byBrand($con,$type,$p,$sr,$table,$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency){
		$sql = new sql(); 
		$brands = $this->getBrands($con);

        if (is_array($secondaryFilter)) {
            $secondaryFilter = implode(",", $secondaryFilter);
        }

        $year = $years[0];

	    for ($b=0; $b < sizeof($brands); $b++) { 
	    	if($type == "agencyGroup"){
	    		$smt = "agency_group";
	    		$join = "LEFT JOIN agency a ON a.ID = y.agency_id
                         LEFT JOIN agency_group c ON c.ID = a.agency_group_id";
                $where = "WHERE (brand_id = \"".$brands[$b][0]."\")
                            AND (year = '$year')
                            AND ( ".$smt."_id = \"".$baseFilter."\")";                    
	    	}elseif ($type == "agency") {
                $join = false;
                $where = "WHERE (brand_id = \"".$brands[$b][0]."\")
                        AND (year = '$year')
                        AND ( ".$type."_id = \"".$baseFilter."\")";    
            }else{
	    		$join = false;
                $where = "WHERE (brand_id = \"".$brands[$b][0]."\")
                        AND (year = '$year')
                        AND ( ".$type."_id = \"".$baseFilter."\")";    
	    	}	
            
            $some[$b] = "SELECT SUM($column) AS mySum 
                        FROM $table y
                        $join
                        $where";              
	    	$res[$b] = $con->query($some[$b]);
	    	$from = array("mySum");
            $values[$b]['brand'] = $brands[$b][1];            
	    	$values[$b]['value'] = doubleval($sql->fetch($res[$b],$from,$from)[0]['mySum']);            
	    }
 
    	return $values;
	}

    public function last3YearsByBrand($con,$type,$p,$sr,$table,$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency,$columnD){
        $sql = new sql(); 
        $brands = $this->getBrands($con);

        array_push($brands, array(13, "ONL-SM"));
        array_push($brands, array(14, "ONL-DSS"));
        array_push($brands, array(15, "ONL-G9"));
        array_push($brands, array(16, "VOD"));

        //var_dump($brands);

        if (is_array($secondaryFilter)) {
            $secondaryFilter = implode(",", $secondaryFilter);
        }

        for ($y=0; $y < sizeof($years); $y++) { 
            for ($b=0; $b < sizeof($brands); $b++) { 
                
                if($type == "agencyGroup"){
                    $smt = "agency_group";
                    $join = "LEFT JOIN agency a ON a.ID = y.agency_id";
                    $where = "WHERE(year = \"".$years[$y]."\")
                            AND (brand_id = \"".$brands[$b][0]."\")
                            AND ( ".$smt."_id = \"".$baseFilter->id."\")
                            AND (agency_id IN (".$secondaryFilter."))";
                }elseif ($type == "agency") {
                    $join = false;
                    $where = "WHERE(year = \"".$years[$y]."\")
                            AND (brand_id = \"".$brands[$b][0]."\")
                            AND ( ".$type."_id = \"".$baseFilter->id."\")
                            AND (client_id IN (".$secondaryFilter."))";    
                }else{
                    $join = false;
                    $where = "WHERE(year = \"".$years[$y]."\")
                            AND (brand_id = \"".$brands[$b][0]."\")
                            AND ( ".$type."_id = \"".$baseFilter->id."\")
                            AND (agency_id IN (".$secondaryFilter."))";    
                    
                }           

                $some[$y][$b] = "SELECT SUM($column) AS mySum 
                                FROM $table y
                                $join
                                $where";
                //var_dump($some[$y][$b]);
        
                $res[$y][$b] = $con->query($some[$y][$b]);
                $from = array("mySum");
                if ($table == "wbd") {
                    if ($brands[$b][0] == 13 || $brands[$b][0] == 14 || $brands[$b][0] == 15 || $brands[$b][0] == 16){
                        $values[$y][8] += $sql->fetch($res[$y][$b],$from,$from)[0]['mySum']/$pRate;
                    }else{
                        $values[$y][$b] = $sql->fetch($res[$y][$b],$from,$from)[0]['mySum']/$pRate;
                    }
                }else{
                    if ($brands[$b][0] == 13 || $brands[$b][0] == 14 || $brands[$b][0] == 15 || $brands[$b][0] == 16){
                        $values[$y][8] += $sql->fetch($res[$y][$b],$from,$from)[0]['mySum']*$pRate;
                    }else{
                        $values[$y][$b] = $sql->fetch($res[$y][$b],$from,$from)[0]['mySum']*$pRate;
                    }
                }
                
            }
        }

        //var_dump($values);
        return $values;
    }

	public function last3YearsByMonth($con,$type,$p,$sr,$table,$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency,$columnD){
		$sql = new sql(); 
		$months = $this->months; 
        
        if (is_array($secondaryFilter)) {
            $secondaryFilter = implode(",", $secondaryFilter);
        }

		for ($y=0; $y < sizeof($years); $y++) { 
		    for ($m=0; $m < sizeof($months); $m++) { 
		    	
		    	if($type == "agencyGroup"){
		    		$smt = "agency_group";
		    		$join = "LEFT JOIN agency a ON a.ID = y.agency_id";
		    		$where = "WHERE(year = \"".$years[$y]."\")
		    					AND (month = \"".$months[$m]."\")
		    					AND ( ".$smt."_id = \"".$baseFilter->id."\")
                                AND (agency_id IN (".$secondaryFilter."))";
                    $someD[$y][$m] = "SELECT SUM($columnD) as 'mySum' FROM fw_digital y $join WHERE (year = \"".$years[$y]."\") AND (month = \"".$months[$m]."\") AND ( ".$smt."_id = \"".$baseFilter->id."\") AND (agency_id IN (".$secondaryFilter."))";
		    	}elseif ($type == "agency") {
                    $join = false;
                    $where = "WHERE(year = \"".$years[$y]."\")
                                AND (month = \"".$months[$m]."\")
                                AND ( ".$type."_id = \"".$baseFilter->id."\") AND (client_id IN (".$secondaryFilter."))";
                    $someD[$y][$m] = "SELECT SUM($columnD) as 'mySum' FROM fw_digital y $join WHERE (year = \"".$years[$y]."\") AND (month = \"".$months[$m]."\") AND ( ".$type."_id = \"".$baseFilter->id."\") AND (client_id IN (".$secondaryFilter."))";
                }else{
		    		$join = false;
		    		$where = "WHERE(year = \"".$years[$y]."\")
		    					AND (month = \"".$months[$m]."\")
		    					AND ( ".$type."_id = \"".$baseFilter->id."\") AND (agency_id IN (".$secondaryFilter."))";
                    $someD[$y][$m] = "SELECT SUM($columnD) as 'mySum' FROM fw_digital y $join WHERE (year = \"".$years[$y]."\") AND (month = \"".$months[$m]."\") AND ( ".$type."_id = \"".$baseFilter->id."\") AND (agency_id IN (".$secondaryFilter."))";
		    	}

		    	$some[$y][$m] = "SELECT SUM($column) AS mySum 
		    					FROM $table y
		    					$join
		    					$where";
               // var_dump($some);
		    	$res[$y][$m] = $con->query($some[$y][$m]);

		    	$from = array("mySum");
                if ($table == 'wbd') {
                    $values[$y][$m] = $sql->fetch($res[$y][$m],$from,$from)[0]['mySum']/$pRate;

                    $resD[$y][$m] = $con->query($someD[$y][$m]);

                    $valuesD[$y][$m] = $sql->fetch($resD[$y][$m],$from,$from)[0]['mySum']/$pRate;

                }else{
                    $values[$y][$m] = $sql->fetch($res[$y][$m],$from,$from)[0]['mySum']*$pRate;

                    $resD[$y][$m] = $con->query($someD[$y][$m]);

                    $valuesD[$y][$m] = $sql->fetch($resD[$y][$m],$from,$from)[0]['mySum']*$pRate;

                }
		    	
                $values[$y][$m] += $valuesD[$y][$m];

		    }
    	}
    	return $values;
	}

    public function current($con,$what,$type,$p,$sr,$table,$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency){
        $sql = new sql();    

        $brands = $this->getBrands($con);
        $months = $this->months;
        $cr = $p->getCurrency($con, array($currency));
        $null = null;

        if ($type == "agencyGroup") {
            $somekind = $sr->getAllNewValues($con,$table,$type,$type, $brands, $regionID, $value, $years, $months, $cr, $null, "", "agency", $secondaryFilter,$baseFilter);
        }else{
            $somekind = $sr->getAllNewValues($con,$table,$type,$type, $brands, $regionID, $value, $years,$months,$cr, $null, null, null, $secondaryFilter,$baseFilter);
        }

        $value = $somekind[0][0];
            
        return $value;
    }

    

    public function last3Years($con,$what,$kind,$type,$p,$sr,$table,$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency){
        $sql = new sql();    

        $brands = $this->getBrands($con);
        $months = $this->months;
        $cr = $p->getCurrency($con, array($currency));
        $null = null;

        if($kind == "root"){
            if ($type == "agencyGroup") {
                $somekind = $sr->getAllValues($con,$table,$type,$type, $brands, $regionID, $value, $years, $months, $cr, $null, "", "agency", $secondaryFilter);
                $somekind2 = $sr->getAllValues($con,$table,$type,$type, $brands, $regionID, $value, $years, $months, $cr, $null, "", "agency");
            }else{
                $somekind = $sr->getAllValues($con,$table,$type,$type, $brands, $regionID, $value, $years,$months,$cr, $null, null, null, $secondaryFilter);
                $somekind2 = $sr->getAllValues($con,$table,$type,$type, $brands, $regionID, $value, $years,$months,$cr, $null);
            }
            
            $filterValues = $sr->filterValues2($somekind, array($baseFilter), $type);

            $values = $this->assembler($somekind,array($baseFilter), $years, $type, $filterValues, $somekind2);

            unset($values[1]);
            
        }else{
            $filter = $baseFilter->$type;

            if ($type == "agency") {
                $auxFilter = $baseFilter->agencyGroup;
            }else{
                $auxFilter = "teste";
            }

            $values = $sr->getSubResults($con, $brands, $type, $regionID, $value, $cr, $months, $years, $filter, $auxFilter, $secondaryFilter);
            
            $mtx = $sr->assembler($values,$years,$type);
        }
        
        return $values;
    }
    
    public function getProducts($con,$table,$type,$filter){
    	$sql = new sql();

    	if($type == "client"){
    		$smt = "client";
            $join = "LEFT JOIN client c ON c.ID = y.client_id";
            $where = "WHERE( ".$smt."_id = \"".$filter->id."\" )";
    	}else{
    		$smt = "agency";

    		if($type == "agencyGroup"){

    			$join = "LEFT JOIN agency a ON a.ID = y.agency_id 
                         LEFT JOIN client c ON c.ID = y.client_id";

    			$where = "WHERE( ".$smt."_group_id = \"".$filter->id."\" )";
    		}else{
                $join = "LEFT JOIN client c ON c.ID = y.client_id";
    			$where = "WHERE( ".$smt."_id = \"".$filter->id."\" )";
    		}    		

    	}

    	$select = "SELECT DISTINCT client_product, client_id, c.name AS \"client\"

    						FROM $table y $join $where";
                            
    	$res = $con->query($select);
    	$from = array("client_product","client_id","client");
    	$to = array("product","clientID","client");
    	$products = $sql->fetch($res,$from,$to);


    	return $products;
    }

    public function getBrands($con){
    	$b = new brand();
    	$temp = $b->getBrand($con);

    	for ($i=0; $i < sizeof($temp); $i++) { 
    		$brands[$i][0] = $temp[$i]['id'];
    		$brands[$i][1] = $temp[$i]['name'];
    	}

    	return $brands;
    }

    public function assembler($values, $type2, $years, $type, $filterValues, $somekind){

        if (strlen($type) > 6) {
            $var = "agency groups";
            $aux = "agencyGroup";
        }else{
            if ($type == "client") {
                $var = "Client";
                $aux = $type;    
            }else{
                $var = "Agency";
                $aux = $type;
            }
            
        }

        for ($y=0; $y < sizeof($years); $y++) { 
            $mtx[$y][0] = "Pos. ".$years[$y];
        }

        $last = $y;
        
        $mtx[$last][0] = "Agency Group";

        if ($type == "agency") {
            $option = 2;
            $mtx[$last+1][0] = ucfirst($var);
        }else{
            $option = 1;
        }
        
        if ($type == "client") {
            $mtx[$last][0] = ucfirst($var);
        }

        for ($l=0; $l < sizeof($years); $l++) { 
            $mtx[(sizeof($years)+$l+$option)][0] = "Rev. ".$years[$l];
        }

        if (sizeof($years) >= 2) {
            $last = $l+sizeof($years)+$option;

            $mtx[$last][0] = "VAR ABS.";
            $mtx[$last+1][0] = "VAR %";    
        }

        if (is_array($values[0])) {
            $p = 0;
        }elseif (is_array($values[1])) {
            $p = 1;
        }else{
            $p = 2;
        }

        $name = $values[$p][0][$type];

        for ($i=0; $i < sizeof($somekind); $i++) {
            if (is_array($somekind[$i])) {
                for ($j=0; $j < sizeof($somekind[$i]); $j++) { 
                    if ($somekind[$i][$j][$type] == $name) {
                        for ($p=0; $p < 3; $p++) { 
                            if (is_array($values[$i])) {
                                $somekind[$i][$j]['total'] = $values[$p][0]['total'];
                            }else{
                                $somekind[$i][$j]['total'] = 0;
                            }   
                        }
                    }
                }   
            }
        }

        for ($i=0; $i < sizeof($somekind); $i++) {
            if (is_array($somekind[$i])) {
                usort($somekind[$i], array($this,'compare'));
            }
        }

        for ($t=0; $t < sizeof($type2); $t++) { 
            
            if ($filterValues[$type2[$t]->id] == 1) {
                for ($m=0; $m < sizeof($mtx); $m++) { 
                    array_push($mtx[$m], $this->checkColumn($mtx, $m, $type2, $t, $values, $years, $aux, sizeof($mtx[$m]), $somekind));
                }
            }
        }

        $total = $this->assemblerTotal($mtx, $years, sizeof($mtx[0]));

        return array($mtx, $total);
    }

    public function checkColumn($mtx, $m, $type2, $t, $values, $years, $type, $p, $somekind){

        if (substr($mtx[$m][0], 0, 3) == "Pos") {
            $var = substr($mtx[$m][0], 5);

            $res = $this->checkOtherYearsPosition($type2[$t]->$type, $values, $var, $years, $type, $somekind);
        }elseif (substr($mtx[$m][0], 0, 3) == "Rev") {
            $var = substr($mtx[$m][0], 5);

            $res = $this->getValueByYear($type2[$t]->$type, $values, $var, $years, $type);
        }elseif ($mtx[$m][0] == "VAR ABS.") {
            if ($mtx[$m-sizeof($years)][$p] == "-" && $mtx[$m-sizeof($years)+1][$p] == "-") {
                $res = "-";
            }elseif ($mtx[$m-sizeof($years)][$p] == "-") {
                $res = ($mtx[$m-sizeof($years)+1][$p]*-1);
            }elseif ($mtx[$m-sizeof($years)+1][$p] == "-") {
                $res = $mtx[$m-sizeof($years)][$p];
            }else{
                $res = $mtx[$m-sizeof($years)][$p] - $mtx[$m-sizeof($years)+1][$p];
            }
        }elseif ($mtx[$m][0] == "VAR %") {
            if ($mtx[$m-sizeof($years)][$p] == 0 || $mtx[$m-sizeof($years)][$p] == "-" || $mtx[$m-sizeof($years)-1][$p] == "-") {
                $res = 0.0;
            }else{
                $res = ($mtx[$m-sizeof($years)-1][$p] / $mtx[$m-sizeof($years)][$p])*100;
            }
        }elseif ($type == "agency" || $type == "agencyGroup") {
            if ($mtx[$m][0] == "Agency Group") {
                $res = $type2[$t]->agencyGroup;
            }else{
                $res = $type2[$t]->$type;    
            }
        }else{
            $res = $type2[$t]->$type;
        }    
        

        return $res;

    }

    public function checkOtherYearsPosition($name, $values, $year, $years, $type, $somekind){
        
        for ($y=0; $y < sizeof($years); $y++) { 
            if ($year == $years[$y]) {
                $p = $y;       
            }
        }

        $ok = 0;

        if (is_array($somekind[$p])) {
            for ($v=0; $v < sizeof($somekind[$p]); $v++) { 
                if ($somekind[$p][$v][$type] == $name) {
                    $pos = $v+1;
                    $ok = 1;
                }
            }   
        }else{
            $pos = false;
        }

        if ($ok == 0) {
            $pos = "-";
        }

        return $pos;

    }

    public function getValueByYear($name, $values, $year, $years, $type){

        for ($y=0; $y < sizeof($years); $y++) { 
            if ($year == $years[$y]) {
                $p = $y;
            }
        }

        $ok = 0;

        if (is_array($values[$p])) {
            for ($v=0; $v < sizeof($values[$p]); $v++) { 
                    if ($name == "Others") {
                        //var_dump("name", $values[$p][$v][$type]);
                        //var_dump("value", $values[$p][$v]["total"]);
                    }
                if ($values[$p][$v][$type] == $name) {
                    $rtr = $values[$p][$v]['total'];
                    $ok = 1;
                }
            }
        }else{
            $rtr = false;
        }

        if ($ok == 0) {
            $rtr = "-";
        }

        return $rtr;
    }

    public function getMonths(){
    	return $this->months;
    }

    public function getMonthsFullName(){
    	return $this->monthsFullName;
    }

    public function getMonthsMidName(){
        return $this->monthsMidName;
    }

    protected $months = array(1,2,3,4,5,6,7,8,9,10,11,12);
    protected $monthsFullName = array("January",
    	 					  "February",
    	 					  "March",
    	 					  "April",
    	 					  "May",
    	 					  "June",
    	 					  "July",
    	 					  "August",
    	 					  "September",
    	 					  "October",
    	 					  "November",
    	 					  "December"
                             );
    protected $monthsMidName = array("Jan",
                              "Feb",
                              "Mar",
                              "Apr",
                              "May",
                              "Jun",
                              "Jul",
                              "Aug",
                              "Sep",
                              "Oct",
                              "Nov",
                              "Dec"
                             );

}
