public function bonusAssembler(){
        
        setlocale(LC_ALL, "en_US.utf8");
        $uN = iconv("utf-8", "ascii//TRANSLIT", Request::session()->get('userName'));
        $db = new dataBase();
        $con = $db->openConnection("DLA");
        $sql = new sql();

        $base = new base();

        $tmp1["values"] = array();
        $tmp1["planValues"] = array();
        $tmp2["values"] = array();
        $tmp2["planValues"] = array();

        for ($b=0; $b < sizeof($brand); $b++) { 
            for ($m=0; $m < sizeof($month); $m++) { 
                for ($s=0; $s < sizeof($salesRep); $s++) { 
                    $tmp[$s][$b][$m] = 0; 
                    $tmp_2[$s][$b][$m] = 0; 
                }
            }
        }

        for ($b=0; $b < sizeof($brand); $b++) {
            if($brand[$b][1] == 'ONL' || $brand[$b][1] == 'VIX'){
                for ($m=0; $m < sizeof($month); $m++) { 
                    for ($s=0; $s < sizeof($salesRep); $s++) { 
                        $tmp[$s][$b][$m] = $values[$b][$m][$s]*$divDig; 
                        $tmp_2[$s][$b][$m] = $planValues[$b][$m][$s]*$divDig; 
                    }
                }
            }else{
                for ($m=0; $m < sizeof($month); $m++) { 
                    for ($s=0; $s < sizeof($salesRep); $s++) { 
                        $tmp[$s][$b][$m] = $values[$b][$m][$s]*$div; 
                        $tmp_2[$s][$b][$m] = $planValues[$b][$m][$s]*$div; 
                    }
                }
            }    
        }

        $valueFix = strtolower($valueView);
        $from = array('revenue');

        for ($s=0; $s < sizeof($salesRep); $s++) { 
            for ($b=0; $b < sizeof($brand); $b++) {
                for ($m=0; $m < sizeof($month); $m++) { 
                    if( ($salesRep[$s]['id'] == 131) && ($m > 5) && ($brand[$b][1] == 'ONL' || $brand[$b][1] == 'VIX') ){
                        if( $brand[$b][1] == "ONL"){
                            $select[$b][$m] = "SELECT SUM(".$valueFix."_revenue) AS revenue FROM fw_digital WHERE (region_id = 1) AND(year = '$year') AND (brand_id != '10') AND (month = '".($m+1)."')";
                        }elseif($brand[$b][1] == "VIX"){
                            $select[$b][$m] = "SELECT SUM(".$valueFix."_revenue) AS revenue FROM fw_digital WHERE (region_id = 1) AND(year = '$year') AND (brand_id = '10') AND (month = '".($m+1)."')";
                        }
                        $result[$b][$m] = $con->query($select[$b][$m]);
                        $kaplau = doubleval($sql->fetch($result[$b][$m],$from,$from)[0]['revenue'])*$divDig;
                        $tmp[$s][$b][$m] = $kaplau;
                    }                    
                }                
            }
        }

        $values = $tmp;
        $planValues = $tmp_2;

        $mtx["valueView"] = $valueView;
        $mtx["currency"] = $currency;
        $mtx["region"] = $region;
        $mtx["year"] = $year;
        $mtx["salesRep"] = $salesRep;
        $mtx["brand"] = $brand;
        $mtx["tier"] = $tier;
        $mtx["quarters"] = $base->monthToQuarter($month);
        $mtx["month"] = $base->intToMonth($month);

        //Começou a agrupar por tier
        for ($s=0; $s < sizeof($salesRep); $s++) { 
            for ($t=0; $t < sizeof($mtx["tier"]); $t++) { 
                for ($m=0; $m < sizeof($month); $m++) { 
                    $tmp2["values"][$s][$t][$m] = 0;
                    $tmp2["planValues"][$s][$t][$m] = 0;
                }
                $mtx["case1"]["totalValueTier"][$s][$t] = 0;
                $mtx["case1"]["totalPlanValueTier"][$s][$t] = 0;
            }
        }

        for ($s=0; $s < sizeof($salesRep); $s++) { 
            for ($t=0; $t < sizeof($mtx["tier"]); $t++) { 
                for ($m=0; $m < sizeof($month); $m++) { 
                    for ($b=0; $b < sizeof($brand); $b++) { 
                        if (($brand[$b][1] == 'DC' || $brand[$b][1] == 'HH' || $brand[$b][1] == 'DK') && $mtx["tier"][$t] == "T1") {
                            $tmp2["planValues"][$s][$t][$m] += 
                            $mtx["case1"]["totalPlanValueTier"][$s][$t] += 
                            $tmp2["values"][$s][$t][$m] += $values[$s][$b][$m];
                            $mtx["case1"]["totalValueTier"][$s][$t] += $values[$s][$b][$m];                            
                        }elseif($brand[$b][1] == 'OTH' && $mtx["tier"][$t] == "TOTH"){
                            $tmp2["planValues"][$s][$t][$m] += 
                            $mtx["case1"]["totalPlanValueTier"][$s][$t] += 
                            $tmp2["values"][$s][$t][$m] += $values[$s][$b][$m];
                            $mtx["case1"]["totalValueTier"][$s][$t] += $values[$s][$b][$m];                            
                        }elseif($mtx["tier"][$t] == "T2" && ($brand[$b][1] == 'AP' || $brand[$b][1] == 'TLC' || $brand[$b][1] == 'ID' || $brand[$b][1] == 'DT' || $brand[$b][1] == 'FN' || $brand[$b][1] == 'ONL' || $brand[$b][1] == 'VIX' || $brand[$b][1] == 'HGTV')){
                            $tmp2["planValues"][$s][$t][$m] += 
                            $mtx["case1"]["totalPlanValueTier"][$s][$t] += $mtx["c           
                            $tmp2["values"][$s][$t][$m] += $values[$s][$b][$m];
                            $mtx["case1"]["totalValueTier"][$s][$t] += $values[$s][$b][$m];

                        }
                    }
                }
            }
        }
        //terminou

        //Começou a agrupar mes
        for ($s=0; $s < sizeof($salesRep); $s++) { 
            for ($t=0; $t < sizeof($mtx["tier"]); $t++) { 
                for ($q=0; $q < sizeof($mtx["quarters"]); $q++) { 
                    $mtx["case1"]["value"][$s][$t][$q] = 0;
                    $mtx["case1"]["planValue"][$s][$t][$q] = 0;
                }
            }
        }

        for ($s=0; $s < sizeof($salesRep); $s++) { 
            for ($t=0; $t < sizeof($mtx["tier"]); $t++) { 
                for ($q=0; $q < sizeof($mtx["quarters"]); $q++) { 
                    for ($m=0; $m < sizeof($month); $m++) { 
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

        for ($s=0; $s < sizeof($mtx["case1"]["value"]); $s++) { 
            for ($t=0; $t < sizeof($mtx["case1"]["value"][$s]); $t++) { 
                for ($q=0; $q < sizeof($mtx["case1"]["value"][$s][$t]); $q++) { 
                    $mtx["case1"]["varAbs"][$s][$t][$q] = $mtx["case1"]["value"][$s][$t][$q] - $mtx["case1"]["planValue"][$s][$t][$q]; 
                    if ($mtx["case1"]["planValue"][$s][$t][$q] != 0) {
                        $mtx["case1"]["varPrc"][$s][$t][$q] = ($mtx["case1"]["value"][$s][$t][$q]/$mtx["case1"]["planValue"][$s][$t][$q])*100;
                    }else{
                        $mtx["case1"]["varPrc"][$s][$t][$q] = 0;
                    }
                }
            }
        }

        for ($s=0; $s < sizeof($mtx["case1"]["value"]); $s++) { 
            for ($t=0; $t < sizeof($mtx["case1"]["value"][$s]); $t++) { 
                $mtx["case1"]["totalVarAbs"][$s][$t] = $mtx["case1"]["totalValueTier"][$s][$t] - $mtx["case1"]["totalPlanValueTier"][$s][$t];
                if ($mtx["case1"]["totalPlanValueTier"][$s][$t] != 0) {
                    $mtx["case1"]["totalVarPrc"][$s][$t] = ($mtx["case1"]["totalValueTier"][$s][$t] / $mtx["case1"]["totalPlanValueTier"][$s][$t])*100;
                }else{
                    $mtx["case1"]["totalVarPrc"][$s][$t] = 0;
                }
            }
        }

        for ($s=0; $s < sizeof($mtx["case1"]["value"]); $s++) { 
            for ($q=0; $q < sizeof($mtx["quarters"]); $q++) { 
                $mtx["case1"]["totalSG"][$s][$q] = 0;
                $mtx["case1"]["totalPlanSG"][$s][$q] = 0;
            }
        }

        for ($s=0; $s < sizeof($mtx["case1"]["value"]); $s++) { 
            for ($t=0; $t < sizeof($mtx["case1"]["value"][$s]); $t++) { 
                for ($q=0; $q < sizeof($mtx["case1"]["value"][$s][$t]); $q++) { 
                    $mtx["case1"]["totalSG"][$s][$q] += $mtx["case1"]["value"][$s][$t][$q];
                    $mtx["case1"]["totalPlanSG"][$s][$q] += $mtx["case1"]["planValue"][$s][$t][$q];                   
                }
            }
        }

        for ($s=0; $s < sizeof($mtx["case1"]["value"]); $s++) { 
            for ($q=0; $q < sizeof($mtx["quarters"]); $q++) { 
                $mtx["case1"]["totalSGVarAbs"][$s][$q] = $mtx["case1"]["totalSG"][$s][$q] - $mtx["case1"]["totalPlanSG"][$s][$q];
                if ($mtx["case1"]["totalPlanSG"][$s][$q] != 0) {
                    $mtx["case1"]["totalSGVarPrc"][$s][$q] = ($mtx["case1"]["totalSG"][$s][$q] / $mtx["case1"]["totalPlanSG"][$s][$q])*100;
                }else{
                    $mtx["case1"]["totalSGVarPrc"][$s][$q] = 0;
                }
            }
        }

        for ($s=0; $s < sizeof($mtx["case1"]["value"]); $s++) { 
            $mtx["case1"]["totalTotalSG"][$s] = 0;
            $mtx["case1"]["totalPlanTotalSG"][$s] = 0;
        }

        for ($s=0; $s < sizeof($mtx["case1"]["value"]); $s++) { 
            for ($q=0; $q < sizeof($mtx["quarters"]); $q++) { 
                $mtx["case1"]["totalTotalSG"][$s] += $mtx["case1"]["totalSG"][$s][$q];
                $mtx["case1"]["totalPlanTotalSG"][$s] += $mtx["case1"]["totalPlanSG"][$s][$q];
            }
        }

        for ($s=0; $s < sizeof($mtx["case1"]["value"]); $s++) { 
            $mtx["case1"]["totalTotalSGVarAbs"][$s] = $mtx["case1"]["totalTotalSG"][$s] - $mtx["case1"]["totalPlanTotalSG"][$s];

            if ($mtx["case1"]["totalPlanTotalSG"][$s] != 0) {
                $mtx["case1"]["totalTotalSGVarPrc"][$s] = ($mtx["case1"]["totalTotalSG"][$s] / $mtx["case1"]["totalPlanTotalSG"][$s])*100;
            }else{
                $mtx["case1"]["totalTotalSGVarPrc"][$s] = 0;
            }
        }

        $tmp3 = array();

        for ($s=0; $s < sizeof($mtx["salesRep"]); $s++) { 
            for ($b=0; $b < sizeof($mtx["brand"]); $b++) {
                for ($q=0; $q < sizeof($mtx["quarters"]); $q++) { 
                    $tmp3["value"][$s][$b][$q] = 0;
                    $tmp3["planValue"][$s][$b][$q] = 0;
                }
            }
        }

        for ($s=0; $s < sizeof($mtx["salesRep"]); $s++) { 
            for ($b=0; $b < sizeof($mtx["brand"]); $b++) {
                for ($q=0; $q < sizeof($mtx["quarters"]); $q++) { 
                    for ($m=0; $m < sizeof($mtx["month"]); $m++) { 
                        if(($month[$m] == '1' || $month[$m] == '2'|| $month[$m] == '3' ) && $mtx["quarters"][$q] == "Q1"){
                            $tmp3["planValue"][$s][$b][$q] += 
                            $tmp3["value"][$s][$b][$q] += $values[$s][$b][$m];
                        }elseif(($month[$m] == '4' || $month[$m] == '5' || $month[$m] == '6') && $mtx["quarters"][$q] == "Q2") {
                            $tmp3["planValue"][$s][$b][$q] += 
                            $tmp3["value"][$s][$b][$q] += $values[$s][$b][$m];                            
                        }elseif(($month[$m] == '7' || $month[$m] == '8' || $month[$m] == '9' ) && $mtx["quarters"][$q] == "Q3") {
                            $tmp3["planValue"][$s][$b][$q] += 
                            $tmp3["value"][$s][$b][$q] += $values[$s][$b][$m];
                        }elseif(($month[$m] == '10' || $month[$m] == '11' || $month[$m] == '12' ) && $mtx["quarters"][$q] == "Q4"){
                            $tmp3["planValue"][$s][$b][$q] += 
                            $tmp3["value"][$s][$b][$q] += $values[$s][$b][$m];                             
                        }
                    }
                }
            }
        }

        //total

        for ($t=0; $t < sizeof($mtx["tier"]); $t++) { 
            for ($m=0; $m < sizeof($mtx["quarters"]); $m++) { 
                $mtx["total"]["case1"]["values"][$t][$m] = 0;
                $mtx["total"]["case1"]["planValues"][$t][$m] = 0;
            }
        }

        for ($sg=0; $sg < sizeof($mtx["salesRep"]); $sg++) {
            if ($salesRep[$sg]['id'] != 131 || $uN == "Joao Romano") {
                for ($t=0; $t < sizeof($mtx["tier"]); $t++) { 
                    for ($q=0; $q < sizeof($mtx["quarters"]); $q++) { 
                        $mtx["total"]["case1"]["values"][$t][$q] += $mtx["case1"]["value"][$sg][$t][$q];
                        $mtx["total"]["case1"]["planValues"][$t][$q] += $mtx["case1"]["planValue"][$sg][$t][$q];
                    }
                }
            }
        }

        for ($t=0; $t < sizeof($mtx["tier"]); $t++) { 
            for ($q=0; $q < sizeof($mtx["quarters"]); $q++) { 
                $mtx["total"]["case1"]["varAbs"][$t][$q] = $mtx["total"]["case1"]["values"][$t][$q] - $mtx["total"]["case1"]["planValues"][$t][$q];
                if (  $mtx["total"]["case1"]["planValues"][$t][$q] == 0) {
                    $mtx["total"]["case1"]["varPrc"][$t][$q] = 0;
                }else{
                    $mtx["total"]["case1"]["varPrc"][$t][$q] = $mtx["total"]["case1"]["values"][$t][$q] / $mtx["total"]["case1"]["planValues"][$t][$q]*100;
                }
            }
        }

        //Case 1
        for ($q=0; $q < sizeof($mtx["quarters"]); $q++) {
            $mtx["total"]["case1"]["dnPlanValue"][$q] = 0;
            $mtx["total"]["case1"]["dnValue"][$q] = 0;
            for ($sg=0; $sg < sizeof($mtx["salesRep"]); $sg++) { 
                if ($salesRep[$sg]['id'] != 131 || $uN == "Joao Romano") {
                    $mtx["total"]["case1"]["dnPlanValue"][$q] += $mtx["case1"]["totalPlanSG"][$sg][$q];
                    $mtx["total"]["case1"]["dnValue"][$q] += $mtx["case1"]["totalSG"][$sg][$q];
                }
            }
            $mtx["total"]["case1"]["dnVarAbs"][$q] = $mtx["total"]["case1"]["dnValue"][$q] - $mtx["total"]["case1"]["dnPlanValue"][$q];
            if ($mtx["total"]["case1"]["dnPlanValue"][$q] == 0) {
                $mtx["total"]["case1"]["dnVarPrc"][$q] = 0;
            }else{
                $mtx["total"]["case1"]["dnVarPrc"][$q] = $mtx["total"]["case1"]["dnValue"][$q] / $mtx["total"]["case1"]["dnPlanValue"][$q]*100;
            }
        }

        for ($t=0; $t < sizeof($mtx["tier"]); $t++) { 
            $mtx["total"]["case1"]["totalValueTier"][$t] = 0;
            $mtx["total"]["case1"]["totalPlanValueTier"][$t] = 0;
            for ($q=0; $q < sizeof($mtx["quarters"]); $q++) { 
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

        for ($t=0; $t < sizeof($mtx["tier"]); $t++) { 
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