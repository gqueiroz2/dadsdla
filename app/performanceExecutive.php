<?php

namespace App;

use App\performance;
use App\region;
use App\results;
use App\sql;
use App\base;
use App\brand;
use App\salesRep;
use App\pRate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

class performanceExecutive extends performance
{
    public function makeMatrix($con){
    	$b = new brand();
        $r = new region();
        $base = new base();
        $sql = new sql();
        $sr = new salesRep();
        $pr = new pRate();

 		$region = Request::get('region');
 		$year = Request::get('year');
 		$brand = $base->handleBrand(Request::get('brand'));
 		$source = Request::get('source');
        $salesRepGroup = Request::get('salesRepGroup');
 		$salesRep = Request::get('salesRep');
 		$currency = Request::get('currency');
 		$month = Request::get('month');
 		$value = Request::get('value');
        $tier = Request::get('tier');


        $tmp = array($year);
 		//valor da moeda para divisões
        $div = $base->generateDiv($con,$pr,$region,$tmp,$currency);
        $div = 1/$div;

        $currencyId = $currency;

        //nome da moeda pra view
        $tmp = array($currency);
        $currency = $pr->getCurrency($con,$tmp)[0]["name"];

        //valor para view
        if ($value == "gross") {
            $valueView = "Gross";
            $value = "GROSS";
        }else{
            $valueView = "Net";
            $value = "NET";
        }

        //year view
        $yearView = $year;
    	

    	//nome da região na view
    	$tmp = array($region);
        $regionView = $r->getRegion($con,$tmp)[0]["name"];

        //define de onde vai se tirar as informações do banco, sendo as opções ytd(IBMS), cmaps, header ou digital.
        //$actualMonth = date("m");
        for ($b=0; $b <sizeof($brand); $b++) {
            for ($m=0; $m <sizeof($month) ; $m++) {
                if ($brand[$b][1] == "ONL" || $brand[$b][1] == "VIX") {
                    $table[$b][$m] = "digital";
                }else{
                    $table[$b][$m] = "ytd";
                }
                //pega colunas
                $sum[$b][$m] = $this->generateColumns($value,$table[$b][$m]);
            }
        }

		//olha quais nucleos serão selecionados
        $salesGroup = $sr->getSalesRepGroupById($con,$salesRepGroup);
        $salesRep = $sr->getSalesRepById($con,$salesRep);

        for ($b=0; $b < sizeof($table); $b++){ 
            for ($m=0; $m <sizeof($table[$b]) ; $m++){
                $values[$b][$m] = $this->generateValue($con,$sql,$region,$year,$brand[$b],$salesRep,$month[$m],$sum[$b][$m],$table[$b][$m]);
                $planValues[$b][$m] = $this->generateValue($con,$sql,$region,$year,$brand[$b],$salesRep,$month[$m],"value","plan_by_sales",$value);
            }
        }

        $mtx = $this->assembler($values,$planValues,$salesRep,$month,$brand,$salesGroup,$tier,$regionView,$yearView,$currency,$valueView,$div);

        return $mtx;
    }

    public function assembler($values,$planValues,$salesRep,$month,$brand,$salesGroup,$tier,$region,$year,$currency,$valueView,$div){
        $base = new base();

        $tmp1["values"] = array();
        $tmp1["planValues"] = array();
        $tmp2["values"] = array();
        $tmp2["planValues"] = array();

        for ($b=0; $b <sizeof($brand); $b++) { 
            for ($m=0; $m <sizeof($month); $m++) { 
                for ($s=0; $s <sizeof($salesRep); $s++) { 
                    $tmp[$s][$b][$m] = 0; 
                    $tmp_2[$s][$b][$m] = 0; 
                }
            }
        }

        for ($b=0; $b <sizeof($brand); $b++) { 
            for ($m=0; $m <sizeof($month); $m++) { 
                for ($s=0; $s <sizeof($salesRep) ; $s++) { 
                    $tmp[$s][$b][$m] = $values[$b][$m][$s]/$div; 
                    $tmp_2[$s][$b][$m] = $planValues[$b][$m][$s]/$div; 
                }
            }
        }

        $values = $tmp;
        $planValues = $tmp_2;

        $mtx["valueView"] = $valueView;
        $mtx["currency"] = $currency;
        $mtx["region"] = $region;
        $mtx["year"] = $year;
        $mtx["case4"]["values"] = $values;
        $mtx["case4"]["planValues"] = $planValues;
        $mtx["salesRep"] = $salesRep;
        $mtx["salesGroup"] = $salesGroup;
        $mtx["brand"] = $brand;
        $mtx["tier"] = $tier;
        $mtx["quarters"] = $base->monthToQuarter($month);
        $mtx["month"] = $base->intToMonth($month);

        for ($s=0; $s <sizeof($values);  $s++) { 
            for ($b=0; $b <sizeof($values[$s]) ; $b++) { 
                for ($m=0; $m <sizeof($values[$s][$b]) ; $m++) { 
                    $oldVarAbs[$s][$b][$m] = 0;
                    $oldVarPrc[$s][$b][$m] = 0;
                }
            }
        }

        for ($s=0; $s <sizeof($values); $s++) { 
            for ($b=0; $b <sizeof($values[$s]) ; $b++) { 
                for ($m=0; $m <sizeof($values[$s][$b]) ; $m++) { 
                    $oldVarAbs[$s][$b][$m] = $values[$s][$b][$m] - $planValues[$s][$b][$m];
                    if ($planValues[$s][$b][$m] != 0) {
                        $oldVarPrc[$s][$b][$m] = $values[$s][$b][$m] / $planValues[$s][$b][$m];
                    }else{
                        $oldVarPrc[$s][$b][$m] = 0;
                    }
                }
            }
        }

        $mtx["case4"]["varAbs"] = $oldVarAbs;
        $mtx["case4"]["varPrc"] = $oldVarPrc;


        



        //Começou a agrupar por tier
        for ($s=0; $s <sizeof($salesRep); $s++) { 
            for ($t=0; $t <sizeof($mtx["tier"]); $t++) { 
                for ($m=0; $m <sizeof($month); $m++) { 
                    $tmp2["values"][$s][$t][$m] = 0;
                    $tmp2["planValues"][$s][$t][$m] = 0;
                }
                $mtx["case1"]["totalValueTier"][$s][$t] = 0;
                $mtx["case1"]["totalPlanValueTier"][$s][$t] = 0;
            }
        }

        for ($s=0; $s <sizeof($salesRep); $s++) { 
            for ($t=0; $t <sizeof($mtx["tier"]); $t++) { 
                for ($m=0; $m <sizeof($month); $m++) { 
                    for ($b=0; $b <sizeof($brand); $b++) { 
                        if (($brand[$b][1] == 'DC' || $brand[$b][1] == 'HH' || $brand[$b][1] == 'DK') && $mtx["tier"][$t] == "T1") {
                            $tmp2["values"][$s][$t][$m] += $mtx["case4"]["values"][$s][$b][$m];
                            $tmp2["planValues"][$s][$t][$m] += $mtx["case4"]["planValues"][$s][$b][$m];
                            $mtx["case1"]["totalValueTier"][$s][$t] += $mtx["case4"]["values"][$s][$b][$m];
                            $mtx["case1"]["totalPlanValueTier"][$s][$t] += $mtx["case4"]["planValues"][$s][$b][$m];
                        }elseif($brand[$b][1] == 'OTH' && $mtx["tier"][$t] == "TOTH"){
                            $tmp2["values"][$s][$t][$m] += $mtx["case4"]["values"][$s][$b][$m];
                            $tmp2["planValues"][$s][$t][$m] += $mtx["case4"]["planValues"][$s][$b][$m];
                            $mtx["case1"]["totalValueTier"][$s][$t] += $mtx["case4"]["values"][$s][$b][$m];
                            $mtx["case1"]["totalPlanValueTier"][$s][$t] += $mtx["case4"]["planValues"][$s][$b][$m];
                        }elseif($mtx["tier"][$t] == "T2" && ($brand[$b][1] == 'AP' || $brand[$b][1] == 'TLC' || $brand[$b][1] == 'ID' || $brand[$b][1] == 'DT' || $brand[$b][1] == 'FN' || $brand[$b][1] == 'ONL' || $brand[$b][1] == 'VIX' || $brand[$b][1] == 'HGTV')){
                            $tmp2["values"][$s][$t][$m] += $mtx["case4"]["values"][$s][$b][$m];
                            $tmp2["planValues"][$s][$t][$m] += $mtx["case4"]["planValues"][$s][$b][$m];
                            $mtx["case1"]["totalValueTier"][$s][$t] += $mtx["case4"]["values"][$s][$b][$m];
                            $mtx["case1"]["totalPlanValueTier"][$s][$t] += $mtx["case4"]["planValues"][$s][$b][$m];
                        }
                    }
                }
            }
        }
        //terminou

        $mtx["case3"] = $tmp2;

        //Começou a agrupar mes
        for ($s=0; $s <sizeof($salesRep) ; $s++) { 
            for ($t=0; $t <sizeof($mtx["tier"]) ; $t++) { 
                for ($q=0; $q <sizeof($mtx["quarters"]) ; $q++) { 
                    $mtx["case1"]["value"][$s][$t][$q] = 0;
                    $mtx["case1"]["planValue"][$s][$t][$q] = 0;
                }
            }
        }

        for ($s=0; $s <sizeof($salesRep) ; $s++) { 
            for ($t=0; $t <sizeof($mtx["tier"]) ; $t++) { 
                for ($q=0; $q <sizeof($mtx["quarters"]) ; $q++) { 
                    for ($m=0; $m <sizeof($month) ; $m++) { 
                        if(($month[$m] == '1' || $month[$m] == '2'|| $month[$m] == '3' ) && $mtx["quarters"][$q] == "Q1"){
                            $mtx["case1"]["value"][$s][$t][$q] += $tmp2["values"][$s][$t][$m];
                            $mtx["case1"]["planValue"][$s][$t][$q] += $tmp2["planValues"][$s][$t][$m];
                        }elseif(($month[$m] == '4' || $month[$m] == '5' || $month[$m] == '6') && $mtx["quarters"][$q] == "Q2") {
                            $mtx["case1"]["value"][$s][$t][$q] += $tmp2["values"][$s][$t][$m];
                            $mtx["case1"]["planValue"][$s][$t][$q] += $tmp2["planValues"][$s][$t][$m];
                        }elseif(($month[$m] == '7' || $month[$m] == '8' || $month[$m] == '9' ) && $mtx["quarters"][$q] == "Q3") {
                            $mtx["case1"]["value"][$s][$t][$q] += $tmp2["values"][$s][$t][$m];
                            $mtx["case1"]["planValue"][$s][$t][$q] += $tmp2["planValues"][$s][$t][$m];
                        }elseif(($month[$m] == '10' || $month[$m] == '11' || $month[$m] == '12' ) && $mtx["quarters"][$q] == "Q4"){
                            $mtx["case1"]["value"][$s][$t][$q] += $tmp2["values"][$s][$t][$m];
                            $mtx["case1"]["planValue"][$s][$t][$q] += $tmp2["planValues"][$s][$t][$m];
                        }

                    } 
                }
            }
        }
        //terminou

        for ($s=0; $s <sizeof($mtx["case1"]["value"]); $s++) { 
            for ($t=0; $t <sizeof($mtx["case1"]["value"][$s]); $t++) { 
                for ($q=0; $q <sizeof($mtx["case1"]["value"][$s][$t]); $q++) { 
                    $mtx["case1"]["varAbs"][$s][$t][$q] = $mtx["case1"]["value"][$s][$t][$q] - $mtx["case1"]["planValue"][$s][$t][$q]; 
                    if ($mtx["case1"]["planValue"][$s][$t][$q] != 0) {
                        $mtx["case1"]["varPrc"][$s][$t][$q] = ($mtx["case1"]["value"][$s][$t][$q]/$mtx["case1"]["planValue"][$s][$t][$q])*100;
                    }else{
                        $mtx["case1"]["varPrc"][$s][$t][$q] = 0 ;
                    }
                }
            }
        }

        for ($s=0; $s <sizeof($mtx["case1"]["value"]); $s++) { 
            for ($t=0; $t <sizeof($mtx["case1"]["value"][$s]); $t++) { 
                $mtx["case1"]["totalVarAbs"][$s][$t] = $mtx["case1"]["totalValueTier"][$s][$t] - $mtx["case1"]["totalPlanValueTier"][$s][$t];
                if ($mtx["case1"]["totalPlanValueTier"][$s][$t] != 0) {
                    $mtx["case1"]["totalVarPrc"][$s][$t] = ($mtx["case1"]["totalValueTier"][$s][$t] / $mtx["case1"]["totalPlanValueTier"][$s][$t])*100;
                }else{
                    $mtx["case1"]["totalVarPrc"][$s][$t] = 0;
                }
            }
        }

        for ($s=0; $s <sizeof($mtx["case1"]["value"]) ; $s++) { 
            for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
                $mtx["case1"]["totalSG"][$s][$q] = 0;
                $mtx["case1"]["totalPlanSG"][$s][$q] = 0;
            }
        }

        for ($s=0; $s <sizeof($mtx["case1"]["value"]) ; $s++) { 
            for ($t=0; $t <sizeof($mtx["case1"]["value"][$s]); $t++) { 
                for ($q=0; $q <sizeof($mtx["case1"]["value"][$s][$t]); $q++) { 
                    $mtx["case1"]["totalSG"][$s][$q] += $mtx["case1"]["value"][$s][$t][$q];
                    $mtx["case1"]["totalPlanSG"][$s][$q] += $mtx["case1"]["planValue"][$s][$t][$q];                   
                }
            }
        }

        for ($s=0; $s <sizeof($mtx["case1"]["value"]) ; $s++) { 
            for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
                $mtx["case1"]["totalSGVarAbs"][$s][$q] = $mtx["case1"]["totalSG"][$s][$q] - $mtx["case1"]["totalPlanSG"][$s][$q];
                if ($mtx["case1"]["totalPlanSG"][$s][$q] != 0) {
                    $mtx["case1"]["totalSGVarPrc"][$s][$q] = ($mtx["case1"]["totalSG"][$s][$q] / $mtx["case1"]["totalPlanSG"][$s][$q])*100;
                }else{
                    $mtx["case1"]["totalSGVarPrc"][$s][$q] = 0;
                }
            }
        }

        for ($s=0; $s <sizeof($mtx["case1"]["value"]) ; $s++) { 
            $mtx["case1"]["totalTotalSG"][$s] = 0;
            $mtx["case1"]["totalPlanTotalSG"][$s] = 0;
        }

        for ($s=0; $s <sizeof($mtx["case1"]["value"]) ; $s++) { 
            for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
                $mtx["case1"]["totalTotalSG"][$s] += $mtx["case1"]["totalSG"][$s][$q];
                $mtx["case1"]["totalPlanTotalSG"][$s] += $mtx["case1"]["totalPlanSG"][$s][$q];
            }
        }

        for ($s=0; $s <sizeof($mtx["case1"]["value"]) ; $s++) { 
            $mtx["case1"]["totalTotalSGVarAbs"][$s] = $mtx["case1"]["totalTotalSG"][$s] - $mtx["case1"]["totalPlanTotalSG"][$s];

            if ($mtx["case1"]["totalPlanTotalSG"][$s] != 0) {
                $mtx["case1"]["totalTotalSGVarPrc"][$s] = ($mtx["case1"]["totalTotalSG"][$s] / $mtx["case1"]["totalPlanTotalSG"][$s])*100;
            }else{
                $mtx["case1"]["totalTotalSGVarPrc"][$s] = 0;
            }
        }

        $tmp3 = array();

        for ($s=0; $s <sizeof($mtx["salesRep"]); $s++) { 
            for ($b=0; $b <sizeof($mtx["brand"]); $b++) {
                for ($q=0; $q <sizeof($mtx["quarters"]) ; $q++) { 
                    $tmp3["value"][$s][$b][$q] = 0;
                    $tmp3["planValue"][$s][$b][$q] = 0;
                }
            }
        }

        for ($s=0; $s <sizeof($mtx["salesRep"]) ; $s++) { 
            for ($b=0; $b <sizeof($mtx["brand"]); $b++) {
                for ($q=0; $q <sizeof($mtx["quarters"]) ; $q++) { 
                    for ($m=0; $m <sizeof($mtx["month"]) ; $m++) { 
                        if(($month[$m] == '1' || $month[$m] == '2'|| $month[$m] == '3' ) && $mtx["quarters"][$q] == "Q1"){
                            $tmp3["value"][$s][$b][$q] += $mtx["case4"]["values"][$s][$b][$m];
                            $tmp3["planValue"][$s][$b][$q] += $mtx["case4"]["planValues"][$s][$b][$m];
                        }elseif(($month[$m] == '4' || $month[$m] == '5' || $month[$m] == '6') && $mtx["quarters"][$q] == "Q2") {
                            $tmp3["value"][$s][$b][$q] += $mtx["case4"]["values"][$s][$b][$m];
                            $tmp3["planValue"][$s][$b][$q] += $mtx["case4"]["planValues"][$s][$b][$m];
                        }elseif(($month[$m] == '7' || $month[$m] == '8' || $month[$m] == '9' ) && $mtx["quarters"][$q] == "Q3") {
                            $tmp3["value"][$s][$b][$q] += $mtx["case4"]["values"][$s][$b][$m];
                            $tmp3["planValue"][$s][$b][$q] += $mtx["case4"]["planValues"][$s][$b][$m];
                        }elseif(($month[$m] == '10' || $month[$m] == '11' || $month[$m] == '12' ) && $mtx["quarters"][$q] == "Q4"){
                            $tmp3["value"][$s][$b][$q] += $mtx["case4"]["values"][$s][$b][$m];
                            $tmp3["planValue"][$s][$b][$q] += $mtx["case4"]["planValues"][$s][$b][$m];
                        }
                    }
                }
            }
        }

        $mtx["case2"] = $tmp3;
        // Agrupamentos Case 2
        

        for ($s=0; $s <sizeof($mtx["salesRep"]) ; $s++) { 
            for ($b=0; $b <sizeof($mtx["brand"]); $b++) {
                for ($q=0; $q <sizeof($mtx["quarters"]) ; $q++) { 
                	$mtx["case2"]["varAbs"][$s][$b][$q] = $mtx["case2"]["value"][$s][$b][$q] - $mtx["case2"]["planValue"][$s][$b][$q];
                	if ($mtx["case2"]["planValue"][$s][$b][$q] != 0) {
	                	$mtx["case2"]["varPrc"][$s][$b][$q] = ($mtx["case2"]["value"][$s][$b][$q] / $mtx["case2"]["planValue"][$s][$b][$q])*100;
                	}else{
                		$mtx["case2"]["varPrc"][$s][$b][$q] = 0;
                	}
        		}
        	}
        }

        for ($s=0; $s <sizeof($mtx["salesRep"]) ; $s++) { 
            for ($b=0; $b <sizeof($mtx["brand"]); $b++) {
                $mtx["case2"]["totalPlanValueBrand"][$s][$b] = 0;
                $mtx["case2"]["totalValueBrand"][$s][$b] = 0;
                $mtx["case2"]["totalVarAbs"][$s][$b] = 0;
                $mtx["case2"]["totalVarPrc"][$s][$b] = 0;
                for ($q=0; $q <sizeof($mtx["quarters"]) ; $q++) { 
                	$mtx["case2"]["totalPlanValueBrand"][$s][$b] += $mtx["case2"]["planValue"][$s][$b][$q];
                	$mtx["case2"]["totalValueBrand"][$s][$b] += $mtx["case2"]["value"][$s][$b][$q];
        			$mtx["case2"]["totalVarAbs"][$s][$b] += $mtx["case2"]["varAbs"][$s][$b][$q];
        		}
                if ($mtx["case2"]["totalPlanValueBrand"][$s][$b] != 0) {
                    $mtx["case2"]["totalVarPrc"][$s][$b] = ($mtx["case2"]["totalValueBrand"][$s][$b]/$mtx["case2"]["totalPlanValueBrand"][$s][$b]) * 100;
                }else{
                    $mtx["case2"]["totalVarPrc"][$s][$b] = 0;
                }
        	}
        }

        //Agrupamentos Case 3

        for ($s=0; $s <sizeof($mtx["salesRep"]) ; $s++) { 
        	for ($t=0; $t <sizeof($mtx["tier"]) ; $t++) { 
        		for ($m=0; $m <sizeof($mtx["month"]); $m++) { 
        			$mtx["case3"]["varAbs"][$s][$t][$m] = $mtx["case3"]["values"][$s][$t][$m] - $mtx["case3"]["planValues"][$s][$t][$m];
        			if ($mtx["case3"]["planValues"][$s][$t][$m] != 0) {
	        			$mtx["case3"]["varPrc"][$s][$t][$m] = ($mtx["case3"]["values"][$s][$t][$m] / $mtx["case3"]["planValues"][$s][$t][$m])*100;
        			}else{
        				$mtx["case3"]["varPrc"][$s][$t][$m] = 0;
        			}
        		}
        	}
        }

        for ($s=0; $s <sizeof($mtx["salesRep"]) ; $s++) { 
        	for ($t=0; $t <sizeof($mtx["tier"]) ; $t++) { 
        		$mtx["case3"]["totalValueTier"][$s][$t] = 0;
    			$mtx["case3"]["totalPlanValueTier"][$s][$t] = 0;
    			$mtx["case3"]["totalVarAbs"][$s][$t] = 0;
    			$mtx["case3"]["totalVarPrc"][$s][$t] = 0;
        		for ($m=0; $m <sizeof($mtx["month"]); $m++) { 
        			$mtx["case3"]["totalValueTier"][$s][$t] += $mtx["case3"]["values"][$s][$t][$m];
        			$mtx["case3"]["totalPlanValueTier"][$s][$t] += $mtx["case3"]["planValues"][$s][$t][$m];
        			$mtx["case3"]["totalVarAbs"][$s][$t] += $mtx["case3"]["varAbs"][$s][$t][$m];
        		}
                if ($mtx["case3"]["totalPlanValueTier"][$s][$t] != 0) {
                    $mtx["case3"]["totalVarPrc"][$s][$t] = ($mtx["case3"]["totalValueTier"][$s][$t] / $mtx["case3"]["totalPlanValueTier"][$s][$t])*100;
                }else{
                    $mtx["case3"]["totalVarPrc"][$s][$t] = 0;
                }
        	}
        }


        //Agrupamento caso 4

        for ($s=0; $s <sizeof($mtx["salesRep"]) ; $s++) { 
        	for ($b=0; $b <sizeof($mtx["brand"]) ; $b++) { 
        		for ($m=0; $m <sizeof($mtx["month"]) ; $m++) { 
        			$mtx["case4"]["varAbs"][$s][$b][$m] = $mtx["case4"]["values"][$s][$b][$m] - $mtx["case4"]["planValues"][$s][$b][$m];
        			if ($mtx["case4"]["planValues"][$s][$b][$m] != 0) {
        				$mtx["case4"]["varPrc"][$s][$b][$m] = ($mtx["case4"]["values"][$s][$b][$m] / $mtx["case4"]["planValues"][$s][$b][$m])*100;
        			}else{
        				$mtx["case4"]["varPrc"][$s][$b][$m] = 0;
        			}
        		}
        	}
        }

        for ($s=0; $s <sizeof($mtx["salesRep"]) ; $s++) { 
        	for ($b=0; $b <sizeof($mtx["brand"]) ; $b++) { 
        		$mtx["case4"]["totalValueTier"][$s][$b] = 0;
        		$mtx["case4"]["totalPlanValueTier"][$s][$b] = 0;
        		$mtx["case4"]["totalVarAbs"][$s][$b] = 0;
        		$mtx["case4"]["totalVarPrc"][$s][$b] = 0;
        		for ($m=0; $m <sizeof($mtx["month"]) ; $m++) { 
        			$mtx["case4"]["totalValueTier"][$s][$b] += $mtx["case4"]["values"][$s][$b][$m];
        			$mtx["case4"]["totalPlanValueTier"][$s][$b] += $mtx["case4"]["planValues"][$s][$b][$m];
        			$mtx["case4"]["totalVarAbs"][$s][$b] += $mtx["case4"]["varAbs"][$s][$b][$m];
        		}
                if ($mtx["case4"]["totalPlanValueTier"][$s][$b] != 0) {
                    $mtx["case4"]["totalVarPrc"][$s][$b] = ($mtx["case4"]["totalValueTier"][$s][$b]/$mtx["case4"]["totalPlanValueTier"][$s][$b]) * 100;
                }else{
                    $mtx["case4"]["totalVarPrc"][$s][$b] = 0;
                }
        	}
        }

        // Começou DN case2

        for ($s=0; $s <sizeof($mtx["salesRep"]) ; $s++) { 
   			for ($m=0; $m <sizeof($mtx["quarters"]) ; $m++) { 
   				$mtx["case2"]["dnPlanValue"][$s][$m] = 0;
   				$mtx["case2"]["dnValue"][$s][$m] = 0;
   				$mtx["case2"]["dnVarAbs"][$s][$m] = 0;
   				$mtx["case2"]["dnVarPrc"][$s][$m] = 0;
   				for ($b=0; $b <sizeof($mtx["brand"]) ; $b++) { 
   					$mtx["case2"]["dnPlanValue"][$s][$m] += $mtx["case2"]["planValue"][$s][$b][$m]; 
   					$mtx["case2"]["dnValue"][$s][$m] += $mtx["case2"]["value"][$s][$b][$m]; 
   					$mtx["case2"]["dnVarAbs"][$s][$m] += $mtx["case2"]["varAbs"][$s][$b][$m]; 
   				}
                if ($mtx["case2"]["dnPlanValue"][$s][$m] != 0) {
                    $mtx["case2"]["dnVarPrc"][$s][$m] = ($mtx["case2"]["dnValue"][$s][$m]/$mtx["case2"]["dnPlanValue"][$s][$m])*100; 
                }else{
                    $mtx["case2"]["dnVarPrc"][$s][$m] = 0;
                }
   			}
        }

        for ($s=0; $s <sizeof($mtx["salesRep"]) ; $s++) { 
        	$mtx["case2"]["dnTotalPlanValue"][$s] = 0;
        	$mtx["case2"]["dnTotalValue"][$s] = 0;
        	$mtx["case2"]["dnTotalVarAbs"][$s] = 0;
        	$mtx["case2"]["dnTotalVarPrc"][$s] = 0;
        	for ($m=0; $m <sizeof($mtx["quarters"]) ; $m++) { 
        		$mtx["case2"]["dnTotalPlanValue"][$s] += $mtx["case2"]["dnPlanValue"][$s][$m];
        		$mtx["case2"]["dnTotalValue"][$s] += $mtx["case2"]["dnValue"][$s][$m];
        		$mtx["case2"]["dnTotalVarAbs"][$s] += $mtx["case2"]["dnVarAbs"][$s][$m];
        	}
            if ($mtx["case2"]["dnTotalPlanValue"][$s] != 0) {
                $mtx["case2"]["dnTotalVarPrc"][$s] = ($mtx["case2"]["dnTotalValue"][$s]/$mtx["case2"]["dnTotalPlanValue"][$s])*100;
            }

        }


        // Começou DN case3

        for ($s=0; $s <sizeof($mtx["salesRep"]) ; $s++) { 
            for ($m=0; $m <sizeof($mtx["month"]) ; $m++) { 
                $mtx["case3"]["dnPlanValue"][$s][$m] = 0;
                $mtx["case3"]["dnValue"][$s][$m] = 0;
                $mtx["case3"]["dnVarAbs"][$s][$m] = 0;
                $mtx["case3"]["dnVarPrc"][$s][$m] = 0;
                for ($t=0; $t <sizeof($mtx["tier"]) ; $t++) { 
                    $mtx["case3"]["dnPlanValue"][$s][$m] += $mtx["case3"]["planValues"][$s][$t][$m]; 
                    $mtx["case3"]["dnValue"][$s][$m] += $mtx["case3"]["values"][$s][$t][$m]; 
                    $mtx["case3"]["dnVarAbs"][$s][$m] += $mtx["case3"]["varAbs"][$s][$t][$m]; 
                }
                if ($mtx["case3"]["dnPlanValue"][$s][$m]) {
                    $mtx["case3"]["dnVarPrc"][$s][$m] = ($mtx["case3"]["dnValue"][$s][$m]/$mtx["case3"]["dnPlanValue"][$s][$m])*100; 
                }else{
                    $mtx["case3"]["dnVarPrc"][$s][$m] = 0;
                }
            }
        }

        for ($s=0; $s <sizeof($mtx["salesRep"]) ; $s++) { 
            $mtx["case3"]["dnTotalPlanValue"][$s] = 0;
            $mtx["case3"]["dnTotalValue"][$s] = 0;
            $mtx["case3"]["dnTotalVarAbs"][$s] = 0;
            $mtx["case3"]["dnTotalVarPrc"][$s] = 0;
            for ($m=0; $m <sizeof($mtx["month"]) ; $m++) { 
                $mtx["case3"]["dnTotalPlanValue"][$s] += $mtx["case3"]["dnPlanValue"][$s][$m];
                $mtx["case3"]["dnTotalValue"][$s] += $mtx["case3"]["dnValue"][$s][$m];
                $mtx["case3"]["dnTotalVarAbs"][$s] += $mtx["case3"]["dnVarAbs"][$s][$m];
            }
            if ($mtx["case3"]["dnTotalPlanValue"][$s]) {
                $mtx["case3"]["dnTotalVarPrc"][$s] = ($mtx["case3"]["dnTotalValue"][$s]/$mtx["case3"]["dnTotalPlanValue"][$s])*100;
            }else{
                $mtx["case3"]["dnTotalVarPrc"][$s] = 0;
            }
        }


        // Começou DN case4

        for ($s=0; $s <sizeof($mtx["salesRep"]) ; $s++) { 
            for ($m=0; $m <sizeof($mtx["month"]) ; $m++) { 
                $mtx["case4"]["dnPlanValue"][$s][$m] = 0;
                $mtx["case4"]["dnValue"][$s][$m] = 0;
                $mtx["case4"]["dnVarAbs"][$s][$m] = 0;
                $mtx["case4"]["dnVarPrc"][$s][$m] = 0;
                for ($b=0; $b <sizeof($mtx["brand"]) ; $b++) { 
                	$mtx["case4"]["dnPlanValue"][$s][$m] += $mtx["case4"]["planValues"][$s][$b][$m];
                	$mtx["case4"]["dnValue"][$s][$m] += $mtx["case4"]["values"][$s][$b][$m];
                	$mtx["case4"]["dnVarAbs"][$s][$m] += $mtx["case4"]["varAbs"][$s][$b][$m];
                }
                if ($mtx["case4"]["dnPlanValue"][$s][$m] != 0) {
                    $mtx["case4"]["dnVarPrc"][$s][$m] = ($mtx["case4"]["dnValue"][$s][$m]/$mtx["case4"]["dnPlanValue"][$s][$m])*100;
                }else{
                    $mtx["case4"]["dnVarPrc"][$s][$m] = 0;
                }
            }
        }

        for ($s=0; $s <sizeof($mtx["salesRep"]) ; $s++) { 
        	$mtx["case4"]["dnTotalPlanValue"][$s] = 0;
        	$mtx["case4"]["dnTotalValue"][$s] = 0;
        	$mtx["case4"]["dnTotalVarAbs"][$s] = 0;
        	$mtx["case4"]["dnTotalVarPrc"][$s] = 0;
        	for ($m=0; $m <sizeof($mtx["month"]) ; $m++) { 
        		$mtx["case4"]["dnTotalPlanValue"][$s] += $mtx["case4"]["dnPlanValue"][$s][$m]; 
        		$mtx["case4"]["dnTotalValue"][$s] += $mtx["case4"]["dnValue"][$s][$m]; 
        		$mtx["case4"]["dnTotalVarAbs"][$s] += $mtx["case4"]["dnVarAbs"][$s][$m]; 
        	}
            if($mtx["case4"]["dnTotalPlanValue"][$s] != 0){
                $mtx["case4"]["dnTotalVarPrc"][$s] = ($mtx["case4"]["dnTotalValue"][$s]/$mtx["case4"]["dnTotalPlanValue"][$s])*100; 
            }else{
                $mtx["case4"]["dnTotalVarPrc"][$s] = 0;
            }
        }

        //total

        for ($t=0; $t <sizeof($mtx["tier"]); $t++) { 
            for ($m=0; $m <sizeof($mtx["month"]); $m++) { 
                $mtx["total"]["case3"]["values"][$t][$m] = 0;
                $mtx["total"]["case3"]["planValues"][$t][$m] = 0;
            }
            for ($m=0; $m <sizeof($mtx["quarters"]) ; $m++) { 
                $mtx["total"]["case1"]["values"][$t][$m] = 0;
                $mtx["total"]["case1"]["planValues"][$t][$m] = 0;
            }
        }

        for ($b=0; $b <sizeof($mtx["brand"]); $b++) {
            for ($m=0; $m <sizeof($mtx["month"]) ; $m++) { 
                $mtx["total"]["case4"]["values"][$b][$m] = 0;
                $mtx["total"]["case4"]["planValues"][$b][$m] = 0;
            }
            for ($q=0; $q <sizeof($mtx["quarters"]) ; $q++) { 
                $mtx["total"]["case2"]["values"][$b][$q] = 0;
                $mtx["total"]["case2"]["planValues"][$b][$q] = 0;
            }
        }

        for ($sg=0; $sg <sizeof($mtx["salesRep"]) ; $sg++) { 
            for ($b=0; $b <sizeof($mtx["brand"]); $b++) {
                for ($m=0; $m <sizeof($mtx["month"]) ; $m++) { 
                    $mtx["total"]["case4"]["values"][$b][$m] += $mtx["case4"]["values"][$sg][$b][$m];
                    $mtx["total"]["case4"]["planValues"][$b][$m] += $mtx["case4"]["planValues"][$sg][$b][$m];
                }
                for ($q=0; $q <sizeof($mtx["quarters"]) ; $q++) { 
                    $mtx["total"]["case2"]["values"][$b][$q]  +=  $mtx["case2"]["value"][$sg][$b][$q];
                    $mtx["total"]["case2"]["planValues"][$b][$q] +=  $mtx["case2"]["planValue"][$sg][$b][$q] ;
                }
            }
            for ($t=0; $t <sizeof($mtx["tier"]); $t++) { 
                for ($m=0; $m <sizeof($mtx["month"]) ; $m++) { 
                    $mtx["total"]["case3"]["values"][$t][$m] += $mtx["case3"]["values"][$sg][$t][$m];
                    $mtx["total"]["case3"]["planValues"][$t][$m] += $mtx["case3"]["planValues"][$sg][$t][$m];
                }
                for ($q=0; $q <sizeof($mtx["quarters"]) ; $q++) { 
                    $mtx["total"]["case1"]["values"][$t][$q] += $mtx["case1"]["value"][$sg][$t][$q];
                    $mtx["total"]["case1"]["planValues"][$t][$q] += $mtx["case1"]["planValue"][$sg][$t][$q];
                }
            }
        }

        for ($b=0; $b <sizeof($mtx["brand"]); $b++) {
            for ($m=0; $m <sizeof($mtx["month"]) ; $m++) { 
                $mtx["total"]["case4"]["varAbs"][$b][$m] = $mtx["total"]["case4"]["values"][$b][$m] - $mtx["total"]["case4"]["planValues"][$b][$m];
                if ($mtx["total"]["case4"]["planValues"][$b][$m] == 0) {
                    $mtx["total"]["case4"]["varPrc"][$b][$m] = 0;
                }else{
                    $mtx["total"]["case4"]["varPrc"][$b][$m] = $mtx["total"]["case4"]["values"][$b][$m] / $mtx["total"]["case4"]["planValues"][$b][$m]*100;
                }
            }
            for ($q=0; $q <sizeof($mtx["quarters"]) ; $q++) { 
                $mtx["total"]["case2"]["varAbs"][$b][$q] = $mtx["total"]["case2"]["values"][$b][$q] -  $mtx["total"]["case2"]["planValues"][$b][$q];
                if( $mtx["total"]["case2"]["planValues"][$b][$q] == 0){
                    $mtx["total"]["case2"]["varPrc"][$b][$q] = 0;
                }else{
                    $mtx["total"]["case2"]["varPrc"][$b][$q] = $mtx["total"]["case2"]["values"][$b][$q] / $mtx["total"]["case2"]["planValues"][$b][$q]*100;
                }
            }
        }
        for ($t=0; $t <sizeof($mtx["tier"]); $t++) { 
            for ($m=0; $m <sizeof($mtx["month"]) ; $m++) { 
                $mtx["total"]["case3"]["varAbs"][$t][$m] = $mtx["total"]["case3"]["values"][$t][$m] - $mtx["total"]["case3"]["planValues"][$t][$m];
                if ($mtx["total"]["case3"]["planValues"][$t][$m]== 0) {
                    $mtx["total"]["case3"]["varPrc"][$t][$m] = 0;
                }else{
                    $mtx["total"]["case3"]["varPrc"][$t][$m] = $mtx["total"]["case3"]["values"][$t][$m] / $mtx["total"]["case3"]["planValues"][$t][$m]*100;
                }
            }
            for ($q=0; $q <sizeof($mtx["quarters"]) ; $q++) { 
                $mtx["total"]["case1"]["varAbs"][$t][$q] = $mtx["total"]["case1"]["values"][$t][$q] - $mtx["total"]["case1"]["planValues"][$t][$q];
                if (  $mtx["total"]["case1"]["planValues"][$t][$q] == 0) {
                    $mtx["total"]["case1"]["varPrc"][$t][$q] = 0;
                }else{
                    $mtx["total"]["case1"]["varPrc"][$t][$q] = $mtx["total"]["case1"]["values"][$t][$q] / $mtx["total"]["case1"]["planValues"][$t][$q]*100;
                }
            }
        }


        //caso 4 completo
        for ($m=0; $m <sizeof($mtx["month"]) ; $m++) {         
            $mtx["total"]["case4"]["dnPlanValue"][$m] = 0;
            $mtx["total"]["case4"]["dnValue"][$m] = 0;
            for ($sg=0; $sg <sizeof($mtx["salesRep"]) ; $sg++) { 
                $mtx["total"]["case4"]["dnPlanValue"][$m] += $mtx["case4"]["dnPlanValue"][$sg][$m];
                $mtx["total"]["case4"]["dnValue"][$m] += $mtx["case4"]["dnValue"][$sg][$m];
            }
            $mtx["total"]["case4"]["dnVarAbs"][$m] = $mtx["total"]["case4"]["dnValue"][$m] - $mtx["total"]["case4"]["dnPlanValue"][$m];
            if ($mtx["total"]["case4"]["dnPlanValue"][$m] == 0) {
                $mtx["total"]["case4"]["dnVarPrc"][$m] = 0;
            }else{
                $mtx["total"]["case4"]["dnVarPrc"][$m] = $mtx["total"]["case4"]["dnValue"][$m] / $mtx["total"]["case4"]["dnPlanValue"][$m]*100;
            }
        }
  
        for ($b=0; $b <sizeof($mtx["brand"]) ; $b++) { 
            $mtx["total"]["case4"]["totalValueTier"][$b] = 0;
            $mtx["total"]["case4"]["totalPlanValueTier"][$b] = 0;
            for ($sg=0; $sg <sizeof($mtx["salesRep"]) ; $sg++) { 
                $mtx["total"]["case4"]["totalValueTier"][$b] += $mtx["case4"]["totalValueTier"][$sg][$b];
                $mtx["total"]["case4"]["totalPlanValueTier"][$b] += $mtx["case4"]["totalPlanValueTier"][$sg][$b];
            }
            $mtx["total"]["case4"]["totalVarAbs"][$b] = $mtx["total"]["case4"]["totalValueTier"][$b] - $mtx["total"]["case4"]["totalPlanValueTier"][$b];
            if ($mtx["total"]["case4"]["totalPlanValueTier"][$b] == 0) {
                $mtx["total"]["case4"]["totalVarPrc"][$b] = 0;
            }else{
                $mtx["total"]["case4"]["totalVarPrc"][$b] = $mtx["total"]["case4"]["totalValueTier"][$b] / $mtx["total"]["case4"]["totalPlanValueTier"][$b]*100;
            }
        }

        $mtx["total"]["case4"]["dnTotalValue"] = 0;
        $mtx["total"]["case4"]["dnTotalPlanValue"] = 0;

        for ($b=0; $b <sizeof($mtx["brand"]) ; $b++) {
            $mtx["total"]["case4"]["dnTotalValue"] += $mtx["total"]["case4"]["totalValueTier"][$b];
            $mtx["total"]["case4"]["dnTotalPlanValue"] += $mtx["total"]["case4"]["totalPlanValueTier"][$b];
        }

        $mtx["total"]["case4"]["dnTotalVarAbs"] = $mtx["total"]["case4"]["dnTotalValue"] - $mtx["total"]["case4"]["dnTotalPlanValue"]; 

        if ($mtx["total"]["case4"]["dnTotalPlanValue"] == 0) {
            $mtx["total"]["case4"]["dnTotalVarPrc"] = 0;
        }else{
            $mtx["total"]["case4"]["dnTotalVarPrc"] = $mtx["total"]["case4"]["dnTotalValue"] / $mtx["total"]["case4"]["dnTotalPlanValue"]*100; 
        }

        //case 3
        for ($m=0; $m <sizeof($mtx["month"]) ; $m++) {         
            $mtx["total"]["case3"]["dnPlanValue"][$m] = 0;
            $mtx["total"]["case3"]["dnValue"][$m] = 0;
            for ($sg=0; $sg <sizeof($mtx["salesRep"]) ; $sg++) { 
                $mtx["total"]["case3"]["dnPlanValue"][$m] += $mtx["case3"]["dnPlanValue"][$sg][$m];
                $mtx["total"]["case3"]["dnValue"][$m] += $mtx["case3"]["dnValue"][$sg][$m];
            }
            $mtx["total"]["case3"]["dnVarAbs"][$m] = $mtx["total"]["case3"]["dnValue"][$m] - $mtx["total"]["case3"]["dnPlanValue"][$m];
            if ($mtx["total"]["case3"]["dnPlanValue"][$m] == 0) {
                $mtx["total"]["case3"]["dnVarPrc"][$m] = 0;
            }else{
                $mtx["total"]["case3"]["dnVarPrc"][$m] = $mtx["total"]["case3"]["dnValue"][$m] / $mtx["total"]["case3"]["dnPlanValue"][$m]*100;
            }
        }
        
        for ($t=0; $t <sizeof($mtx["tier"]); $t++) { 
            $mtx["total"]["case3"]["totalValueTier"][$t] = 0;
            $mtx["total"]["case3"]["totalPlanValueTier"][$t] = 0;
            for ($m=0; $m <sizeof($mtx["month"]) ; $m++) { 
                $mtx["total"]["case3"]["totalValueTier"][$t] +=  $mtx["total"]["case3"]["values"][$t][$m];
                $mtx["total"]["case3"]["totalPlanValueTier"][$t] +=  $mtx["total"]["case3"]["planValues"][$t][$m];
            }
            $mtx["total"]["case3"]["totalVarAbs"][$t] = $mtx["total"]["case3"]["totalValueTier"][$t] - $mtx["total"]["case3"]["totalPlanValueTier"][$t];
            if ($mtx["total"]["case3"]["totalPlanValueTier"][$t] == 0) {
                $mtx["total"]["case3"]["totalVarPrc"][$t] = 0;
            }else{
                $mtx["total"]["case3"]["totalVarPrc"][$t] = $mtx["total"]["case3"]["totalValueTier"][$t] / $mtx["total"]["case3"]["totalPlanValueTier"][$t]*100;
            }
        }

        $mtx["total"]["case3"]["dnTotalValue"] = 0;
        $mtx["total"]["case3"]["dnTotalPlanValue"] = 0;

        for ($t=0; $t <sizeof($mtx["tier"]); $t++) { 
            $mtx["total"]["case3"]["dnTotalValue"] += $mtx["total"]["case3"]["totalValueTier"][$t];
            $mtx["total"]["case3"]["dnTotalPlanValue"] += $mtx["total"]["case3"]["totalPlanValueTier"][$t];
        }

        $mtx["total"]["case3"]["dnTotalVarAbs"] = $mtx["total"]["case3"]["dnTotalValue"] - $mtx["total"]["case3"]["dnTotalPlanValue"]; 

        if ($mtx["total"]["case3"]["dnTotalPlanValue"] == 0) {
            $mtx["total"]["case3"]["dnTotalVarPrc"] = 0;
        }else{
            $mtx["total"]["case3"]["dnTotalVarPrc"] = $mtx["total"]["case3"]["dnTotalValue"] / $mtx["total"]["case3"]["dnTotalPlanValue"]*100; 
        }


        //case 2
        for ($q=0; $q <sizeof($mtx["quarters"]) ; $q++) { 
            $mtx["total"]["case2"]["dnPlanValue"][$q] = 0;
            $mtx["total"]["case2"]["dnValue"][$q] = 0;
            for ($sg=0; $sg <sizeof($mtx["salesRep"]) ; $sg++) { 
                $mtx["total"]["case2"]["dnPlanValue"][$q] += $mtx["case2"]["dnPlanValue"][$sg][$q];
                $mtx["total"]["case2"]["dnValue"][$q] += $mtx["case2"]["dnValue"][$sg][$q];
            }
            $mtx["total"]["case2"]["dnVarAbs"][$q] = $mtx["total"]["case2"]["dnValue"][$q] - $mtx["total"]["case2"]["dnPlanValue"][$q];
            if ($mtx["total"]["case2"]["dnPlanValue"][$q] == 0) {
                $mtx["total"]["case2"]["dnVarPrc"][$q] = 0;
            }else{
                $mtx["total"]["case2"]["dnVarPrc"][$q] = $mtx["total"]["case2"]["dnValue"][$q] / $mtx["total"]["case2"]["dnPlanValue"][$q]*100;
            }
        }

        for ($b=0; $b <sizeof($mtx["brand"]) ; $b++) { 
            $mtx["total"]["case2"]["totalValueBrand"][$b] = 0;
            $mtx["total"]["case2"]["totalPlanValueBrand"][$b] = 0;
            for ($sg=0; $sg <sizeof($mtx["salesRep"]) ; $sg++) { 
                $mtx["total"]["case2"]["totalValueBrand"][$b] += $mtx["case2"]["totalValueBrand"][$sg][$b];
                $mtx["total"]["case2"]["totalPlanValueBrand"][$b] += $mtx["case2"]["totalPlanValueBrand"][$sg][$b];
            }
            $mtx["total"]["case2"]["totalVarAbs"][$b] = $mtx["total"]["case2"]["totalValueBrand"][$b] - $mtx["total"]["case2"]["totalPlanValueBrand"][$b];
            if ($mtx["total"]["case2"]["totalPlanValueBrand"][$b] == 0) {
                $mtx["total"]["case2"]["totalVarPrc"][$b] = 0;
            }else{
                $mtx["total"]["case2"]["totalVarPrc"][$b] = $mtx["total"]["case2"]["totalValueBrand"][$b] / $mtx["total"]["case2"]["totalPlanValueBrand"][$b]*100;
            }
        }

        $mtx["total"]["case2"]["dnTotalValue"] = 0;
        $mtx["total"]["case2"]["dnTotalPlanValue"] = 0;

        for ($b=0; $b <sizeof($mtx["brand"]) ; $b++) { 
            $mtx["total"]["case2"]["dnTotalValue"] += $mtx["total"]["case2"]["totalValueBrand"][$b];
            $mtx["total"]["case2"]["dnTotalPlanValue"] += $mtx["total"]["case2"]["totalPlanValueBrand"][$b];
        }

        $mtx["total"]["case2"]["dnTotalVarAbs"] = $mtx["total"]["case2"]["dnTotalValue"] - $mtx["total"]["case2"]["dnTotalPlanValue"]; 

        if ($mtx["total"]["case2"]["dnTotalPlanValue"] == 0) {
            $mtx["total"]["case2"]["dnTotalVarPrc"] = 0; 
        }else{
            $mtx["total"]["case2"]["dnTotalVarPrc"] = $mtx["total"]["case2"]["dnTotalValue"] / $mtx["total"]["case2"]["dnTotalPlanValue"]*100; 
        }


        //Case 1
        for ($q=0; $q <sizeof($mtx["quarters"]) ; $q++) {
            $mtx["total"]["case1"]["dnPlanValue"][$q] = 0;
            $mtx["total"]["case1"]["dnValue"][$q] = 0;
            for ($sg=0; $sg <sizeof($mtx["salesRep"]) ; $sg++) { 
                $mtx["total"]["case1"]["dnPlanValue"][$q] += $mtx["case1"]["totalPlanSG"][$sg][$q];
                $mtx["total"]["case1"]["dnValue"][$q] += $mtx["case1"]["totalSG"][$sg][$q];
            }
            $mtx["total"]["case1"]["dnVarAbs"][$q] = $mtx["total"]["case1"]["dnValue"][$q] - $mtx["total"]["case1"]["dnPlanValue"][$q];
            if ($mtx["total"]["case1"]["dnPlanValue"][$q] == 0) {
                $mtx["total"]["case1"]["dnVarPrc"][$q] = 0;
            }else{
                $mtx["total"]["case1"]["dnVarPrc"][$q] = $mtx["total"]["case1"]["dnValue"][$q] / $mtx["total"]["case1"]["dnPlanValue"][$q]*100;
            }
        }

        for ($t=0; $t <sizeof($mtx["tier"]); $t++) { 
            $mtx["total"]["case1"]["totalValueTier"][$t] = 0;
            $mtx["total"]["case1"]["totalPlanValueTier"][$t] = 0;
            for ($q=0; $q <sizeof($mtx["quarters"]) ; $q++) { 
                $mtx["total"]["case1"]["totalValueTier"][$t] +=  $mtx["total"]["case1"]["values"][$t][$q];
                $mtx["total"]["case1"]["totalPlanValueTier"][$t] +=  $mtx["total"]["case1"]["planValues"][$t][$q];
            }
            $mtx["total"]["case1"]["totalVarAbs"][$t] = $mtx["total"]["case1"]["totalValueTier"][$t] - $mtx["total"]["case1"]["totalPlanValueTier"][$t];
            if ($mtx["total"]["case1"]["totalPlanValueTier"][$t] == 0) {
                $mtx["total"]["case1"]["totalVarPrc"][$t] = 0;
            }else{
                $mtx["total"]["case1"]["totalVarPrc"][$t] = $mtx["total"]["case1"]["totalValueTier"][$t] / $mtx["total"]["case1"]["totalPlanValueTier"][$t]*100;
            }
        }

        $mtx["total"]["case1"]["dnTotalValue"] = 0;
        $mtx["total"]["case1"]["dnTotalPlanValue"] = 0;

        for ($t=0; $t <sizeof($mtx["tier"]); $t++) { 
            $mtx["total"]["case1"]["dnTotalValue"] += $mtx["total"]["case1"]["totalValueTier"][$t];
            $mtx["total"]["case1"]["dnTotalPlanValue"] += $mtx["total"]["case1"]["totalPlanValueTier"][$t];
        }

        $mtx["total"]["case1"]["dnTotalVarAbs"] = $mtx["total"]["case1"]["dnTotalValue"] - $mtx["total"]["case1"]["dnTotalPlanValue"];
        if ($mtx["total"]["case1"]["dnTotalPlanValue"] == 0) {
            $mtx["total"]["case1"]["dnTotalVarPrc"] = 0;
        }else{
            $mtx["total"]["case1"]["dnTotalVarPrc"] = $mtx["total"]["case1"]["dnTotalValue"] / $mtx["total"]["case1"]["dnTotalPlanValue"]*100;
        }

        return $mtx;
    }
}
