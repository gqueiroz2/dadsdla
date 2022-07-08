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
        $rollingFCSTSony = array();

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

        $test = $this->listClientsBrazil($con,$sql,$salesRepID,$cYear,$pYear,$regionID);
        
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
        //var_dump($splitted);

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

        //for ($t=0; $t <=$clientRevenueCYearDisc; $t++) { 
            //var_dump($clientRevenueCYearDisc);
            /*if ($clientRevenueCYearDisc[$t][16] > 0) {
                var_dump($listOfClients);
            }*/
        //}

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
        $executiveRevenueCYearDisc = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        for ($s=0; $s < sizeof($clientRevenueCYearDisc); $s++) {
            for ($x=0; $x < sizeof($clientRevenueCYearDisc[$s]); $x++) { 
                $executiveRevenueCYearDisc[$x] += ($clientRevenueCYearDisc[$s][$x]);            
            }
            
        }
        //var_dump($clientRevenueCYearDisc);
        $executiveRevenuePYearDisc = $this->consolidateAEFcst($clientRevenuePYearDisc,$splitted);
        /*$tmpDisc = $this->getBookingExecutive($con,$sql,$salesRepID[0],$month,$regionID,$cYear,$value,$currency,$pr,$discoveryBrands);
        $executiveRevenueCYearDisc = $this->addQuartersAndTotal($tmpDisc);
        */        

        /* --------------- VERIFICAR --------------- */
        $executiveRevenueCYearSony = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        for ($s=0; $s < sizeof($clientRevenueCYearSony); $s++) {
            for ($x=0; $x < sizeof($clientRevenueCYearSony[$s]); $x++) { 
                $executiveRevenueCYearSony[$x] += ($clientRevenueCYearSony[$s][$x]);            
            }
            
        }

        $executiveRevenuePYearSony = $this->consolidateAEFcst($clientRevenuePYearSony,$splitted);

        /*$tmpSony = $this->getBookingExecutive($con,$sql,$salesRepID[0],$month,$regionID,$cYear,$value,$currency,$pr,$sonyBrands);
        $executiveRevenueCYearSony = $this->addQuartersAndTotal($tmpSony);        
        */


        /* --------------- TOTAL EXECUTIVE --------------- */
        $executiveRevenueCYear = $this->sumNetworks($executiveRevenueCYearDisc,$executiveRevenueCYearSony);
        $executiveRevenuePYear = $this->sumNetworks($executiveRevenuePYearDisc,$executiveRevenuePYearSony);

        

        if ($save){
            if($submitted == 1){ $sourceSave = "LAST SUBMITTED"; }else{ $sourceSave = "LAST SAVED"; }
            
            $select = array();
            $result = array();
            $from = "value";
            
            if ($regionID == "1") {
                $from2 = array("sales_reps");
                $splitFrom = array("split","ownerID","splitterID");

                for ($c=0; $c <sizeof($listOfClients); $c++) { 

                    //VERIFICANDO SE A CONTA É COMPARTILHADA E QUEM É O DONO

                    $split[$c] = "SELECT DISTINCT is_split AS split, sales_rep_owner_id AS ownerID, sales_rep_splitter_id AS splitterID
                              FROM sf_pr 
                              WHERE (client_id = \"".$listOfClients[$c]['clientID']."\")
                              AND stage != '6'
                              AND stage != '7'";
                    $querySplit[$c] = $con->query($split[$c]);
                    $splitResult[$c] = $sql->fetch($querySplit[$c],$splitFrom,$splitFrom);
                    //var_dump($split[$c]);

                    //TERMINA VERIFICAÇÃO
                    
                    if ($splitResult[$c] != false) {
                        for ($x=0; $x <sizeof($splitResult[$c]); $x++) { 
                    
                            if ($splitResult[$c][$x]['split'] == 1) {
                                if ($splitResult[$c][$x]['ownerID'] == $salesRepID[0]) {

                                    $select2[$c] = "SELECT DISTINCT sales_rep_owner_id AS sales_reps 
                                                    FROM sf_pr 
                                                    WHERE (sales_rep_owner_id = \"".$salesRepID[0]."\" )
                                                    AND client_id = \"".$listOfClients[$c]["clientID"]."\" 
                                                    AND stage != '6'                                
                                                    AND stage != '7'";

                               }else{
                                     $select2[$c] = "SELECT DISTINCT sales_rep_owner_id AS sales_reps 
                                                FROM sf_pr 
                                                WHERE (sales_rep_splitter_id = \"".$salesRepID[0]."\" )
                                                AND client_id = \"".$listOfClients[$c]["clientID"]."\" 
                                                AND stage != '6' 
                                                AND stage != '7'"; 
                               }
                            }else{
                                $select2[$c] = "SELECT DISTINCT sales_rep_owner_id AS sales_reps 
                                                FROM sf_pr 
                                                WHERE (sales_rep_owner_id = \"".$salesRepID[0]."\" ) OR (sales_rep_splitter_id = \"".$salesRepID[0]."\" )
                                                AND client_id = \"".$listOfClients[$c]["clientID"]."\" 
                                                AND stage != '6' 
                                                AND stage != '7'";    
                            }                                   
                        }

                        $result2[$c] = $con->query($select2[$c]);
                        $salesReps[$c] = $sql->fetch($result2[$c],$from2,$from2);

                    }elseif($splitResult[$c] == false){
                        $salesReps[$c] =  array(array('sales_reps' => $salesRepID[0]));

                    }
                    
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

                    //var_dump($salesRepsOR);
                }
            }else{
                $salesRepsOR = "sales_rep_id = \"".$salesRepID[0]."\"";
            }

            $auxYear = date('Y');
            $cMonth = date(('n'));
            for ($c=0; $c < sizeof($listOfClients); $c++) {
                if ($splitted) {
                    if ($splitted[$c]["splitted"]) {
                        $mul = 1;
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
                        //var_dump($salesRepsOR);
                        $selectDisc[$c][$m] .= " AND read_q = \"".$week."\" AND ".$salesRepsOR[$c]." ";
                    }else{
                        $selectDisc[$c][$m] .= " AND ".$salesRepsOR." ";
                    }

                    $resultDisc[$c][$m] = $con->query($selectDisc[$c][$m]);
                    $saidaDisc[$c][$m] = $sql->fetchSum($resultDisc[$c][$m],$from);

                    //var_dump($selectDisc);
                    //echo "<pre>".$selectDisc[$c][$m]."</pre>";

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
                //var_dump($rollingFCSTDisc);

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
            $closedFCSTDisc = $this->closedByClientAndAE($con,$sql,$base,$pr,$regionID,$cYear,$month,$discoveryBrands,$currency,$currencyID,$value,$listOfClients,$salesRepID[0],$splitted);
            //var_dump($closedFCSTDisc);
            /* BTS meses Fechado para Sony */
            $rollingFCSTSony = $this->rollingFCSTByClientAndAE($con,$sql,$base,$pr,$regionID,$cYear,$month,$sonyBrands,$currency,$currencyID,$value,$listOfClients,$salesRepID[0],$splitted);//Ibms meses fechados e fw total

            
            $fcstDisc = $this->calculateForecast($con,$sql,$base,$pr,$regionID,$cYear,$month,$discoveryBrands,$currency,$currencyID,$value,$listOfClients,$salesRepID[0],$rollingFCSTDisc,$splitted,$clientRevenuePYearDisc,$executiveRevenuePYearDisc,$lastYearDisc);
            //$closedFcstDisc = $this->calculateClosedForecast($con,$sql,$base,$pr,$regionID,$cYear,$month,$discoveryBrands,$currency,$currencyID,$value,$listOfClients,$salesRepID[0],$rollingFCSTDisc,$splitted,$clientRevenuePYearDisc,$executiveRevenuePYearDisc,$lastYearDisc);
            //$closedAmountDisc = $closedFcstDisc['fcstAmountByStage'];
            $fcstAmountByStageDisc = $fcstDisc['fcstAmountByStage'];
            $toRollingFCSTDisc = $fcstDisc['fcstAmount'];
            $rollingFCSTDisc = $this->addQuartersAndTotalOnArray($rollingFCSTDisc);
            $rollingFCSTDisc = $this->addFcstWithBooking($clientRevenueCYearDisc,$toRollingFCSTDisc);//Meses fechados e abertos
            $rollingFCSTDisc = $this->adjustFCST($rollingFCSTDisc);
            //var_dump($rollingFCSTDisc);
            $fcstAmountByStageDisc = $this->addClosed($fcstAmountByStageDisc,$closedFCSTDisc);//Adding Closed to fcstByStage
            $emptyCheckDisc = $this->checkEmpty($toRollingFCSTDisc);
            $lastRollingFCSTDisc = $rollingFCSTDisc;
            //var_dump($lastRollingFCSTDisc);
            

            $fcstSony = $this->calculateForecast($con,$sql,$base,$pr,$regionID,$cYear,$month,$sonyBrands,$currency,$currencyID,$value,$listOfClients,$salesRepID[0],$rollingFCSTSony,$splitted,$clientRevenuePYearSony,$executiveRevenuePYearSony,$lastYearDisc);

            $fcstAmountByStageSony = $fcstSony['fcstAmountByStage'];
            $toRollingFCSTSony = $fcstSony['fcstAmount'];
            $rollingFCSTSony = $this->addQuartersAndTotalOnArray($rollingFCSTSony);
            $rollingFCSTSony = $this->addFcstWithBooking($clientRevenueCYearSony,$toRollingFCSTSony);//Meses fechados e abertos
            $rollingFCSTSony = $this->adjustFCST($rollingFCSTSony);
            $fcstAmountByStageSony = $this->addClosed($fcstAmountByStageSony,$rollingFCSTSony);//Adding Closed to fcstByStage
            $emptyCheckSony = $this->checkEmpty($toRollingFCSTSony);
            $lastRollingFCSTSony = $rollingFCSTSony;
        }
        
        //$fcstAmountByStageDisc = $this->addLost($con,$listOfClients,$fcstAmountByStageDisc,$value,$div);
        $fcstAmountByStageExDisc = $this->makeFcstAmountByStageEx($fcstAmountByStageDisc,$splitted);
        
        //$fcstAmountByStageSony = $this->addLost($con,$listOfClients,$fcstAmountByStageSony,$value,$div);
        $fcstAmountByStageExSony = $this->makeFcstAmountByStageEx($fcstAmountByStageSony,$splitted);
        //var_dump($fcstAmountByStageExSony);
        $fcstAmountByStage = $this->sumDiscAndSonyTA($fcstAmountByStageDisc,$fcstAmountByStageSony);
        $fcstAmountByStageEx = $this->sumDiscAndSonyMA($fcstAmountByStageExDisc,$fcstAmountByStageExSony);

        $rollingFCST = $this->sumDiscAndSonyMA($rollingFCSTDisc,$rollingFCSTSony);
        $executiveRevenueCYear = $this->sumDiscAndSonyA($executiveRevenueCYearDisc,$executiveRevenueCYearSony);
        $targetValues = $this->sumDiscAndSonyA($targetValuesDiscovery,$targetValuesSony);
        //var_dump($rollingFCST);
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

        // == Se mudarem de ideia sobre o RF do Brasil ser diferente da soma dos clientes, comenta esse bloco de código == //
        if ($regionID == 1){
            if($value == 'gross') {$queryValue = 'gross_revenue';}
            elseif($value == 'net') {$queryValue = 'net_revenue';}
            $from = array($queryValue,'from_date','to_date','year_from','year_to','stage','oppid','salesRepOwner');
            $to = array("sumValue",'fromDate','toDate','yearFrom','yearTo','stage','oppid','salesRepOwner');
            $select = "         SELECT $queryValue, oppid, from_date , to_date, year_from, year_to, stage , is_split , sales_rep_owner_id AS 'salesRepOwner'
                                FROM sf_pr
                                WHERE ( (sales_rep_splitter_id = \"".$salesRepID[0]."\") OR (sales_rep_owner_id = \"".$salesRepID[0]."\") )
                                AND (is_split = 1)
                                AND (stage != '5')
                                AND (stage != '6')
                                AND (stage != '7')
                                AND (year_from = $cYear)";
            //var_dump($select);
            $res = $con->query($select);
            $rev = $sql->fetch($res,$from,$to);

            if ($rev) {
                for ($r=0; $r <sizeof($rev); $r++) { 
                    $rev[$r]["sumValue"] = doubleval($rev[$r]["sumValue"])*$div;
                }
            }

            /*
                AJUSTE DAS PREVISÕES QUE POSSUEM MAIS DE 1 ANO DE PREVISÃO
            */
            if($rev){
                for ($r=0; $r < sizeof($rev); $r++) { 
                    if($rev[$r]['yearFrom'] != $rev[$r]['yearTo']){
                        $fromArray = $this->makeMonths("from",$rev[$r]['fromDate']);
                        $toArray = $this->makeMonths("to",$rev[$r]['toDate']);
                        $fromShare = $this->calculateRespectiveShare($con,$sql,$regionID,$value,$rev[$r]['yearFrom'],$fromArray);
                        $toShare = $this->calculateRespectiveShare($con,$sql,$regionID,$value,$rev[$r]['yearTo'],$toArray);
                        $shareFromCYear = $this->aggregateShare($fromShare,$toShare);

                        $rev[$r]['sumValue'] = $rev[$r]['sumValue']*$shareFromCYear;
                        //var_dump($rev[$r]['sumValue']);
                    }                
                }
            }

            //var_dump($rev);

            if($rev){
                for ($o=0; $o < sizeof($rev); $o++){                 
                    $period[$o] = $this->monthOPP($rev[$o], $cYear);       
                }
            }else{
                $period = false;
            }

            if($period){
                $shareSalesRep = $this->salesRepShareOnPeriod(null ,$executiveRevenuePYearDisc , null, $period, $rev);
                $fcst = $this->fillFCST($rev, $period, $shareSalesRep,$salesRepID[0], null);
            }else{
                $shareSalesRep = false;
                $fcst = false;
            }

            if($fcst){
                $fcst = $this->adjustValues($fcst);
                $sharedFcstByStage = $this->fcstAmountByStage($fcst, $period);
                $sharedFcst = $this->fcstAmount($fcst, $period, null, $salesRepID[0]);
                $sharedFcst = $this->adjustValuesForecastAmount($sharedFcst);
            }else{
                $sharedFcstByStage = false;
                $sharedFcst = false;
            }  

            //$testValue = $this->addFcstWithBooking($executiveRevenueCYear,$executiveRF);
            //$testResult = $this->addQuartersAndTotal($sharedFcst);
            //var_dump($sharedFcst);

            /*for ($i = 0; $i < sizeof($executiveRF); $i++){
                $executiveRF[$i] = $executiveRF[$i] - ($sharedFcst[$i] / 2);
            }*/
            //var_dump($executiveRF);
        }

        // == Comenta até aqui == //
        
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
