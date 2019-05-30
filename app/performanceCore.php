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

class performanceCore extends performance
{

	public function makeCore($con){
    	
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
                $sum[$b][$m] = $this->generateColumns($table[$b][$m],$value);
            }
        }

		//olha quais nucleos serão selecionados
        $salesGroup = $sr->getSalesRepGroupById($con,$salesRepGroup);
        $salesRep = $sr->getSalesRepById($con,$salesRep);



        for ($b=0; $b < sizeof($table); $b++) { 
            for ($m=0; $m <sizeof($table[$b]) ; $m++) {
                $values[$b][$m] = $this->generateValue($con,$sql,$region,$year,$brand[$b],$salesRep,$month[$m],$sum[$b][$m],$table[$b][$m]);
                $planValues[$b][$m] = $this->generateValue($con,$sql,$region,$year,$brand[$b],$salesRep,$month[$m],"value","plan_by_sales");
            }
        }

        $mtx = $this->assembler($values,$planValues,$salesRep,$month,$brand,$salesGroup,$tier);

        return $mtx;
    }


	public function assembler($values,$planValues,$salesRep,$month,$brand,$salesGroup,$tier){
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
                    $tmp[$s][$b][$m] = $values[$b][$m][$s]; 
                    $tmp_2[$s][$b][$m] = $planValues[$b][$m][$s]; 
                }
            }
        }

        $values = $tmp;
        $planValues = $tmp_2;


        $mtx["oldValues"] = $values;
        $mtx["oldPlanValues"] = $planValues;
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

        $mtx["oldVarAbs"] = $oldVarAbs;
        $mtx["oldVarPrc"] = $oldVarPrc;

        //começa a agrupar por sales Group
        for ($sg=0; $sg <sizeof($salesGroup) ; $sg++) {
            for ($b=0; $b <sizeof($brand); $b++) { 
                for ($m=0; $m <sizeof($month); $m++) { 
                    $tmp1["values"][$sg][$b][$m] = 0;
                    $tmp1["planValues"][$sg][$b][$m] = 0;
                }
            }
        }

        for ($sg=0; $sg <sizeof($salesGroup) ; $sg++) {
            for ($s=0; $s <sizeof($salesRep); $s++) { 
                if ($salesRep[$s]["salesRepGroup"] == $salesGroup[$sg]["name"]) {
                    for ($b=0; $b <sizeof($brand); $b++) { 
                        for ($m=0; $m <sizeof($month); $m++) { 
                            $tmp1["values"][$sg][$b][$m] += $values[$s][$b][$m];
                            $tmp1["planValues"][$sg][$b][$m] += $planValues[$s][$b][$m];
                        }
                    }
                }
            }
        }
        //Termina de Agrupar

        $mtx["case4"] = $tmp1;


        //Começou a agrupar por tier
        for ($sg=0; $sg <sizeof($salesGroup); $sg++) { 
            for ($t=0; $t <sizeof($mtx["tier"]); $t++) { 
                for ($m=0; $m <sizeof($month); $m++) { 
                    $tmp2["values"][$sg][$t][$m] = 0;
                    $tmp2["planValues"][$sg][$t][$m] = 0;
                }
                $mtx["case1"]["totalValueTier"][$sg][$t] = 0;
                $mtx["case1"]["totalPlanValueTier"][$sg][$t] = 0;
            }
        }

        for ($sg=0; $sg <sizeof($salesGroup); $sg++) { 
            for ($t=0; $t <sizeof($mtx["tier"]); $t++) { 
                for ($m=0; $m <sizeof($month); $m++) { 
                    for ($b=0; $b <sizeof($brand); $b++) { 
                        if (($brand[$b][1] == 'DC' || $brand[$b][1] == 'HH' || $brand[$b][1] == 'DK') && $mtx["tier"][$t] == "T1") {
                            $tmp2["values"][$sg][$t][$m] += $tmp1["values"][$sg][$b][$m];
                            $tmp2["planValues"][$sg][$t][$m] += $tmp1["planValues"][$sg][$b][$m];
                            $mtx["case1"]["totalValueTier"][$sg][$t] += $tmp1["values"][$sg][$b][$m];
                            $mtx["case1"]["totalPlanValueTier"][$sg][$t] += $tmp1["planValues"][$sg][$b][$m];
                        }elseif($brand[$b][1] == 'OTH' && $mtx["tier"][$t] == "T3"){
                            $tmp2["values"][$sg][$t][$m] += $tmp1["values"][$sg][$b][$m];
                            $tmp2["planValues"][$sg][$t][$m] += $tmp1["planValues"][$sg][$b][$m];
                            $mtx["case1"]["totalValueTier"][$sg][$t] += $tmp1["values"][$sg][$b][$m];
                            $mtx["case1"]["totalPlanValueTier"][$sg][$t] += $tmp1["planValues"][$sg][$b][$m];
                        }elseif($mtx["tier"][$t] == "T2"){
                            $tmp2["values"][$sg][$t][$m] += $tmp1["values"][$sg][$b][$m];
                            $tmp2["planValues"][$sg][$t][$m] += $tmp1["planValues"][$sg][$b][$m];
                            $mtx["case1"]["totalValueTier"][$sg][$t] += $tmp1["values"][$sg][$b][$m];
                            $mtx["case1"]["totalPlanValueTier"][$sg][$t] += $tmp1["planValues"][$sg][$b][$m];
                        }
                    }
                }
            }
        }
        //terminou

        $mtx["case3"] = $tmp2;

        //Começou a agrupar mes
        for ($sg=0; $sg <sizeof($salesGroup) ; $sg++) { 
            for ($t=0; $t <sizeof($mtx["tier"]) ; $t++) { 
                for ($q=0; $q <sizeof($mtx["quarters"]) ; $q++) { 
                    $mtx["case1"]["value"][$sg][$t][$q] = 0;
                    $mtx["case1"]["planValue"][$sg][$t][$q] = 0;
                }
            }
        }

        for ($sg=0; $sg <sizeof($salesGroup) ; $sg++) { 
            for ($t=0; $t <sizeof($mtx["tier"]) ; $t++) { 
                for ($q=0; $q <sizeof($mtx["quarters"]) ; $q++) { 
                    for ($m=0; $m <sizeof($month) ; $m++) { 
                        if(($month[$m] == '1' || $month[$m] == '2'|| $month[$m] == '3' ) && $mtx["quarters"][$q] == "Q1"){
                            $mtx["case1"]["value"][$sg][$t][$q] += $tmp2["values"][$sg][$t][$m];
                            $mtx["case1"]["planValue"][$sg][$t][$q] += $tmp2["planValues"][$sg][$t][$m];
                        }elseif(($month[$m] == '4' || $month[$m] == '5' || $month[$m] == '6') && $mtx["quarters"][$q] == "Q2") {
                            $mtx["case1"]["value"][$sg][$t][$q] += $tmp2["values"][$sg][$t][$m];
                            $mtx["case1"]["planValue"][$sg][$t][$q] += $tmp2["planValues"][$sg][$t][$m];
                        }elseif(($month[$m] == '7' || $month[$m] == '8' || $month[$m] == '9' ) && $mtx["quarters"][$q] == "Q3") {
                            $mtx["case1"]["value"][$sg][$t][$q] += $tmp2["values"][$sg][$t][$m];
                            $mtx["case1"]["planValue"][$sg][$t][$q] += $tmp2["planValues"][$sg][$t][$m];
                        }elseif(($month[$m] == '10' || $month[$m] == '11' || $month[$m] == '12' ) && $mtx["quarters"][$q] == "Q4"){
                            $mtx["case1"]["value"][$sg][$t][$q] += $tmp2["values"][$sg][$t][$m];
                            $mtx["case1"]["planValue"][$sg][$t][$q] += $tmp2["planValues"][$sg][$t][$m];
                        }

                    } 
                }
            }
        }
        //terminou

        for ($sg=0; $sg <sizeof($mtx["case1"]["value"]); $sg++) { 
            for ($t=0; $t <sizeof($mtx["case1"]["value"][$sg]); $t++) { 
                for ($q=0; $q <sizeof($mtx["case1"]["value"][$sg][$t]); $q++) { 
                    $mtx["case1"]["varAbs"][$sg][$t][$q] = $mtx["case1"]["value"][$sg][$t][$q] - $mtx["case1"]["planValue"][$sg][$t][$q]; 
                    if ($mtx["case1"]["planValue"][$sg][$t][$q] != 0) {
                        $mtx["case1"]["varPrc"][$sg][$t][$q] = $mtx["case1"]["value"][$sg][$t][$q] / $mtx["case1"]["planValue"][$sg][$t][$q];
                    }else{
                        $mtx["case1"]["varPrc"][$sg][$t][$q] = 0 ;
                    }
                }
            }
        }

        for ($sg=0; $sg <sizeof($mtx["case1"]["value"]); $sg++) { 
            for ($t=0; $t <sizeof($mtx["case1"]["value"][$sg]); $t++) { 
                $mtx["case1"]["totalVarAbs"][$sg][$t] = $mtx["case1"]["totalValueTier"][$sg][$t] - $mtx["case1"]["totalPlanValueTier"][$sg][$t];
                if ($mtx["case1"]["totalPlanValueTier"][$sg][$t] != 0) {
                    $mtx["case1"]["totalVarPrc"][$sg][$t] = $mtx["case1"]["totalValueTier"][$sg][$t] / $mtx["case1"]["totalPlanValueTier"][$sg][$t];
                }else{
                    $mtx["case1"]["totalVarPrc"][$sg][$t] = 0;
                }
            }
        }

        for ($sg=0; $sg <sizeof($mtx["case1"]["value"]) ; $sg++) { 
            for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
                $mtx["case1"]["totalSG"][$sg][$q] = 0;
                $mtx["case1"]["totalPlanSG"][$sg][$q] = 0;
            }
        }

        for ($sg=0; $sg <sizeof($mtx["case1"]["value"]) ; $sg++) { 
            for ($t=0; $t <sizeof($mtx["case1"]["value"][$sg]); $t++) { 
                for ($q=0; $q <sizeof($mtx["case1"]["value"][$sg][$t]); $q++) { 
                    $mtx["case1"]["totalSG"][$sg][$q] += $mtx["case1"]["value"][$sg][$t][$q];
                    $mtx["case1"]["totalPlanSG"][$sg][$q] += $mtx["case1"]["planValue"][$sg][$t][$q];                   
                }
            }
        }

        for ($sg=0; $sg <sizeof($mtx["case1"]["value"]) ; $sg++) { 
            for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
                $mtx["case1"]["totalSGVarAbs"][$sg][$q] = $mtx["case1"]["totalSG"][$sg][$q] - $mtx["case1"]["totalPlanSG"][$sg][$q];
                if ($mtx["case1"]["totalPlanSG"][$sg][$q] != 0) {
                    $mtx["case1"]["totalSGVarPrc"][$sg][$q] = $mtx["case1"]["totalSG"][$sg][$q] - $mtx["case1"]["totalPlanSG"][$sg][$q];
                }else{
                    $mtx["case1"]["totalSGVarPrc"][$sg][$q] = 0;
                }
            }
        }

        for ($sg=0; $sg <sizeof($mtx["case1"]["value"]) ; $sg++) { 
            $mtx["case1"]["totalTotalSG"][$sg] = 0;
            $mtx["case1"]["totalPlanTotalSG"][$sg] = 0;
        }

        for ($sg=0; $sg <sizeof($mtx["case1"]["value"]) ; $sg++) { 
            for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
                $mtx["case1"]["totalTotalSG"][$sg] += $mtx["case1"]["totalSG"][$sg][$q];
                $mtx["case1"]["totalPlanTotalSG"][$sg] += $mtx["case1"]["totalPlanSG"][$sg][$q];
            }
        }

        for ($sg=0; $sg <sizeof($mtx["case1"]["value"]) ; $sg++) { 
            $mtx["case1"]["totalTotalSGVarAbs"][$sg] = $mtx["case1"]["totalTotalSG"][$sg] - $mtx["case1"]["totalPlanTotalSG"][$sg];

            if ($mtx["case1"]["totalPlanTotalSG"][$sg] != 0) {
                $mtx["case1"]["totalTotalSGVarPrc"][$sg] = $mtx["case1"]["totalTotalSG"][$sg] / $mtx["case1"]["totalPlanTotalSG"][$sg];
            }else{
                $mtx["case1"]["totalTotalSGVarPrc"][$sg] = 0;
            }
        }

        $tmp3 = array();

        for ($sg=0; $sg <sizeof($mtx["salesGroup"]); $sg++) { 
            for ($b=0; $b <sizeof($mtx["brand"]); $b++) {
                for ($q=0; $q <sizeof($mtx["quarters"]) ; $q++) { 
                    $tmp3["value"][$sg][$b][$q] = 0;
                    $tmp3["planValue"][$sg][$b][$q] = 0;
                }
            }
        }

        for ($sg=0; $sg <sizeof($mtx["salesGroup"]) ; $sg++) { 
            for ($b=0; $b <sizeof($mtx["brand"]); $b++) {
                for ($q=0; $q <sizeof($mtx["quarters"]) ; $q++) { 
                    for ($m=0; $m <sizeof($mtx["month"]) ; $m++) { 
                        if(($month[$m] == '1' || $month[$m] == '2'|| $month[$m] == '3' ) && $mtx["quarters"][$q] == "Q1"){
                            $tmp3["value"][$sg][$b][$q] += $tmp1["values"][$sg][$b][$m];
                            $tmp3["planValue"][$sg][$b][$q] += $tmp1["planValues"][$sg][$b][$m];
                        }elseif(($month[$m] == '4' || $month[$m] == '5' || $month[$m] == '6') && $mtx["quarters"][$q] == "Q2") {
                            $tmp3["value"][$sg][$b][$q] += $tmp1["values"][$sg][$b][$m];
                            $tmp3["planValue"][$sg][$b][$q] += $tmp1["planValues"][$sg][$b][$m];
                        }elseif(($month[$m] == '7' || $month[$m] == '8' || $month[$m] == '9' ) && $mtx["quarters"][$q] == "Q3") {
                            $tmp3["value"][$sg][$b][$q] += $tmp1["values"][$sg][$b][$m];
                            $tmp3["planValue"][$sg][$b][$q] += $tmp1["planValues"][$sg][$b][$m];
                        }elseif(($month[$m] == '10' || $month[$m] == '11' || $month[$m] == '12' ) && $mtx["quarters"][$q] == "Q4"){
                            $tmp3["value"][$sg][$b][$q] += $tmp1["values"][$sg][$b][$m];
                            $tmp3["planValue"][$sg][$b][$q] += $tmp1["planValues"][$sg][$b][$m];
                        }
                    }
                }
            }
        }

        $mtx["case2"] = $tmp3;
        // Agrupamentos Case 2
        

        for ($sg=0; $sg <sizeof($mtx["salesGroup"]) ; $sg++) { 
            for ($b=0; $b <sizeof($mtx["brand"]); $b++) {
                for ($q=0; $q <sizeof($mtx["quarters"]) ; $q++) { 
                	$mtx["case2"]["varAbs"][$sg][$b][$q] = $mtx["case2"]["value"][$sg][$b][$q] - $mtx["case2"]["planValue"][$sg][$b][$q];
                	if ($mtx["case2"]["planValue"][$sg][$b][$q] != 0) {
	                	$mtx["case2"]["varPrc"][$sg][$b][$q] = $mtx["case2"]["value"][$sg][$b][$q] / $mtx["case2"]["planValue"][$sg][$b][$q];
                	}else{
                		$mtx["case2"]["varPrc"][$sg][$b][$q] = 0;
                	}
        		}
        	}
        }

        for ($sg=0; $sg <sizeof($mtx["salesGroup"]) ; $sg++) { 
            for ($b=0; $b <sizeof($mtx["brand"]); $b++) {
                $mtx["case2"]["totalPlanValueBrand"][$sg][$b] = 0;
                $mtx["case2"]["totalValueBrand"][$sg][$b] = 0;
                $mtx["case2"]["totalVarAbs"][$sg][$b] = 0;
                $mtx["case2"]["totalVarPrc"][$sg][$b] = 0;
                for ($q=0; $q <sizeof($mtx["quarters"]) ; $q++) { 
                	$mtx["case2"]["totalPlanValueBrand"][$sg][$b] += $mtx["case2"]["planValue"][$sg][$b][$q];
                	$mtx["case2"]["totalValueBrand"][$sg][$b] += $mtx["case2"]["value"][$sg][$b][$q];
        			$mtx["case2"]["totalVarPrc"][$sg][$b] += $mtx["case2"]["varPrc"][$sg][$b][$q];
        			$mtx["case2"]["totalVarAbs"][$sg][$b] += $mtx["case2"]["varAbs"][$sg][$b][$q];
        		}
        	}
        }

        //Agrupamentos Case 3

        for ($sg=0; $sg <sizeof($mtx["salesGroup"]) ; $sg++) { 
        	for ($t=0; $t <sizeof($mtx["tier"]) ; $t++) { 
        		for ($m=0; $m <sizeof($mtx["month"]); $m++) { 
        			$mtx["case3"]["varAbs"][$sg][$t][$m] = $mtx["case3"]["values"][$sg][$t][$m] - $mtx["case3"]["planValues"][$sg][$t][$m];
        			if ($mtx["case3"]["planValues"][$sg][$t][$m] != 0) {
	        			$mtx["case3"]["varPrc"][$sg][$t][$m] = $mtx["case3"]["values"][$sg][$t][$m] / $mtx["case3"]["planValues"][$sg][$t][$m];
        			}else{
        				$mtx["case3"]["varPrc"][$sg][$t][$m] = 0;
        			}
        		}
        	}
        }

        for ($sg=0; $sg <sizeof($mtx["salesGroup"]) ; $sg++) { 
        	for ($t=0; $t <sizeof($mtx["tier"]) ; $t++) { 
        		$mtx["case3"]["totalValueTier"][$sg][$t] = 0;
    			$mtx["case3"]["totalPlanValueTier"][$sg][$t] = 0;
    			$mtx["case3"]["totalVarAbs"][$sg][$t] = 0;
    			$mtx["case3"]["totalVarPrc"][$sg][$t] = 0;
        		for ($m=0; $m <sizeof($mtx["month"]); $m++) { 
        			$mtx["case3"]["totalValueTier"][$sg][$t] += $mtx["case3"]["values"][$sg][$t][$m];
        			$mtx["case3"]["totalPlanValueTier"][$sg][$t] += $mtx["case3"]["planValues"][$sg][$t][$m];
        			$mtx["case3"]["totalVarAbs"][$sg][$t] += $mtx["case3"]["varAbs"][$sg][$t][$m];
        			$mtx["case3"]["totalVarPrc"][$sg][$t] += $mtx["case3"]["varPrc"][$sg][$t][$m];
        		}
        	}
        }


        //Agrupamento caso 4

        for ($sg=0; $sg <sizeof($mtx["salesGroup"]) ; $sg++) { 
        	for ($b=0; $b <sizeof($mtx["brand"]) ; $b++) { 
        		for ($m=0; $m <sizeof($mtx["month"]) ; $m++) { 
        			$mtx["case4"]["varAbs"][$sg][$b][$m] = $mtx["case4"]["values"][$sg][$t][$m] - $mtx["case4"]["planValues"][$sg][$t][$m];
        			if ($mtx["case4"]["planValues"][$sg][$t][$m] != 0) {
        				$mtx["case4"]["varPrc"][$sg][$b][$m] = $mtx["case4"]["values"][$sg][$t][$m] / $mtx["case4"]["planValues"][$sg][$t][$m];
        			}else{
        				$mtx["case4"]["varPrc"][$sg][$b][$m] = 0;
        			}
        		}
        	}
        }


        return $mtx;
    }

}
