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

 		//valor da moeda para divisões
        $div = $base->generateDiv($con,$pr,$region,$year,$currency);

        //nome da moeda pra view
        $tmp = array($currency);
        $currency = $pr->getCurrency($con,$tmp)[0]["name"];

        //valor para view
        if ($value == "gross") {
            $valueView = "Gross";
        }else{
            $valueView = "Net";
        }

        //year view
        $yearView = $year;
    	

    	//nome da região na view
    	$tmp = array($region);
        $regionView = $r->getRegion($con,$tmp)[0]["name"];

        //define de onde vai se tirar as informações do banco, sendo as opções ytd(IBMS), cmaps, header ou digital.
        $actualMonth = date("m");
        for ($b=0; $b <sizeof($brand); $b++) {
            for ($m=0; $m <sizeof($month) ; $m++) {
                if ($brand[$b][1] == "ONL" || $brand[$b][1] == "VIX") {
                    $table[$b][$m] = "digital";
                }else{
                    $table[$b][$m] = "ytd";
                }
                //pega colunas
                $sum[$b][$m] = $this->generateColumns($value);
            }
        }

		//olha quais nucleos serão selecionados
        $salesGroup = $sr->getSalesRepGroupById($con,$salesRepGroup);
        $salesRep = $sr->getSalesRepById($con,$salesRep);



        for ($b=0; $b < sizeof($table); $b++){ 
            for ($m=0; $m <sizeof($table[$b]) ; $m++){
                $values[$b][$m] = $this->generateValue($con,$sql,$region,$year,$brand[$b],$salesRep,$month[$m],$sum[$b][$m],$table[$b][$m]);
                $planValues[$b][$m] = $this->generateValue($con,$sql,$region,$year,$brand[$b],$salesRep,$month[$m],"value","plan_by_sales");
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
                        }elseif($brand[$b][1] == 'OTH' && $mtx["tier"][$t] == "T3"){
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
                        $mtx["case1"]["varPrc"][$s][$t][$q] = $mtx["case1"]["value"][$s][$t][$q] / $mtx["case1"]["planValue"][$s][$t][$q];
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
                    $mtx["case1"]["totalVarPrc"][$s][$t] = $mtx["case1"]["totalValueTier"][$s][$t] / $mtx["case1"]["totalPlanValueTier"][$s][$t];
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
                    $mtx["case1"]["totalSGVarPrc"][$s][$q] = $mtx["case1"]["totalSG"][$s][$q] - $mtx["case1"]["totalPlanSG"][$s][$q];
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
                $mtx["case1"]["totalTotalSGVarPrc"][$s] = $mtx["case1"]["totalTotalSG"][$s] / $mtx["case1"]["totalPlanTotalSG"][$s];
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
	                	$mtx["case2"]["varPrc"][$s][$b][$q] = $mtx["case2"]["value"][$s][$b][$q] / $mtx["case2"]["planValue"][$s][$b][$q];
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
        			$mtx["case2"]["totalVarPrc"][$s][$b] += $mtx["case2"]["varPrc"][$s][$b][$q];
        			$mtx["case2"]["totalVarAbs"][$s][$b] += $mtx["case2"]["varAbs"][$s][$b][$q];
        		}
        	}
        }

        //Agrupamentos Case 3

        for ($s=0; $s <sizeof($mtx["salesRep"]) ; $s++) { 
        	for ($t=0; $t <sizeof($mtx["tier"]) ; $t++) { 
        		for ($m=0; $m <sizeof($mtx["month"]); $m++) { 
        			$mtx["case3"]["varAbs"][$s][$t][$m] = $mtx["case3"]["values"][$s][$t][$m] - $mtx["case3"]["planValues"][$s][$t][$m];
        			if ($mtx["case3"]["planValues"][$s][$t][$m] != 0) {
	        			$mtx["case3"]["varPrc"][$s][$t][$m] = $mtx["case3"]["values"][$s][$t][$m] / $mtx["case3"]["planValues"][$s][$t][$m];
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
        			$mtx["case3"]["totalVarPrc"][$s][$t] += $mtx["case3"]["varPrc"][$s][$t][$m];
        		}
        	}
        }


        //Agrupamento caso 4

        for ($s=0; $s <sizeof($mtx["salesRep"]) ; $s++) { 
        	for ($b=0; $b <sizeof($mtx["brand"]) ; $b++) { 
        		for ($m=0; $m <sizeof($mtx["month"]) ; $m++) { 
        			$mtx["case4"]["varAbs"][$s][$b][$m] = $mtx["case4"]["values"][$s][$b][$m] - $mtx["case4"]["planValues"][$s][$b][$m];
        			if ($mtx["case4"]["planValues"][$s][$b][$m] != 0) {
        				$mtx["case4"]["varPrc"][$s][$b][$m] = $mtx["case4"]["values"][$s][$b][$m] / $mtx["case4"]["planValues"][$s][$b][$m];
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
        			$mtx["case4"]["totalVarPrc"][$s][$b] += $mtx["case4"]["varPrc"][$s][$b][$m];
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
   					$mtx["case2"]["dnVarPrc"][$s][$m] += $mtx["case2"]["varPrc"][$s][$b][$m]; 
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
        		$mtx["case2"]["dnTotalVarPrc"][$s] += $mtx["case2"]["dnVarPrc"][$s][$m];
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
                    $mtx["case3"]["dnVarPrc"][$s][$m] += $mtx["case3"]["varPrc"][$s][$t][$m]; 
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
                $mtx["case3"]["dnTotalVarPrc"][$s] += $mtx["case3"]["dnVarPrc"][$s][$m];
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
                	$mtx["case4"]["dnVarPrc"][$s][$m] += $mtx["case4"]["varPrc"][$s][$b][$m];

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
        		$mtx["case4"]["dnTotalVarPrc"][$s] += $mtx["case4"]["dnVarPrc"][$s][$m]; 
        	}
        }

        return $mtx;
    }
}
