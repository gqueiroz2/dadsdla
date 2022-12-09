<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\sql;

class DailyResults extends Model{

    // == Essa função é usada para consultar a tabela 'CMAPS' ou 'YTD' de acordo com a região == //
    public function ytd($con, $sql, Int $region, Float $pRate, Float $brlPRate, String $value, String $day, String $month, String $realMonth, String $year, String $brands, Int $currencyID){
        if ($region == 1) {
            // == Caso a região seja "Brazil (1)", ele leva em consideração a base do CMAPS e o valor do log diario == //
            $regionYtd = "daily_results";
            $month = $month + 0;
            $realMonth = $realMonth + 0;
            $date = date("$year-$realMonth-$day");

            switch ($brands){
                case "total":
                    $querryTV = "SELECT SUM(real_dsc_tv + real_spt_tv + real_wm_tv) AS $value FROM $regionYtd WHERE real_date = '$date' AND month = '$month'";
                    //var_dump($querryTV);
                    $querryONL = "SELECT SUM(real_dsc_onl + real_spt_onl + real_wm_onl) AS $value FROM $regionYtd WHERE real_date = '$date' AND month = '$month'";
                    //var_dump($querryONL);
                    break;
                case "discovery":
                    $querryTV = "SELECT SUM(real_dsc_tv) AS $value FROM $regionYtd WHERE real_date = '$date' AND month = '$month'";
                    //var_dump($querryTV);
                    $querryONL = "SELECT SUM(real_dsc_onl) AS $value FROM $regionYtd WHERE real_date = '$date' AND month = '$month'";
                    //var_dump($querryONL);
                    break;
                case "sony":
                    $querryTV = "SELECT SUM(real_spt_tv) AS $value FROM $regionYtd WHERE real_date = '$date' AND month = '$month'";
                    //var_dump($querryTV);
                    $querryONL = "SELECT SUM(real_spt_onl) AS $value FROM $regionYtd WHERE real_date = '$date' AND month = '$month'";
                    //var_dump($querryONL);
                    break;
                case "wm":
                    $querryTV = "SELECT SUM(real_wm_tv) AS $value FROM $regionYtd WHERE real_date = '$date' AND month = '$month'";
                    //var_dump($querryTV);
                    $querryONL = "SELECT SUM(real_wm_onl) AS $value FROM $regionYtd WHERE real_date = '$date' AND month = '$month'";
                    //var_dump($querryONL);
                    break;
            }
        } else {
            // == Caso a região não seja "Brazil (1)", ele leva em consideração a base do YTD e ignora o valor do log diario (levando só em consideração o mês e o ano) == //
            $regionYtd = "YTD";
            $month = $month + 0;
            $realMonth = $realMonth + 0;

            // == Alteração na $value para usar como parametro de consulta, de acordo como esta no banco == //
            if ($value == "gross") {
                $value = "gross_revenue";
            } else {
                $value = "net_revenue";
            }

            switch ($brands){
                case "total":
                    $querryTV = "SELECT SUM($value) AS $value FROM $regionYtd WHERE sales_representant_office_id = $region AND brand_id IN (1,2,3,4,5,6,7,8,11,12,18,19,20,22,23,24,28,30,31,32,33) AND year = $year AND month = $month";
                    //var_dump($querryTV);
                    $querryONL = "SELECT SUM($value) AS $value FROM $regionYtd WHERE sales_representant_office_id = $region AND brand_id IN (9,10,13,14,15,16,25,26) AND year = $year AND month = $month";
                    //var_dump($querryONL);
                    break;
                case "discovery":
                    $querryTV = "SELECT SUM($value) AS $value FROM $regionYtd WHERE sales_representant_office_id = $region AND brand_id IN (1,2,3,4,5,6,7,8,11,12,18,19,20,24,28,30,31,32,33) AND year = $year AND month = $month";
                    //var_dump($querryTV);
                    $querryONL = "SELECT SUM($value) AS $value FROM $regionYtd WHERE sales_representant_office_id = $region AND brand_id IN (9,10,13,14,15,16) AND year = $year AND month = $month";
                    //var_dump($querryONL);
                    break;
                case "sony":
                    $querryTV = "SELECT SUM($value) AS $value FROM $regionYtd WHERE sales_representant_office_id = $region AND brand_id NOT IN IN (22, 23) AND year = $year AND month = $month";
                    //var_dump($querryTV);
                    $querryONL = "SELECT SUM($value) AS $value FROM $regionYtd WHERE sales_representant_office_id = $region AND brand_id IN (25, 26) AND year = $year AND month = $month";
                    //var_dump($querryONL);
                    break;
                case "wm":
                    $querryTV = "SELECT SUM($value) AS $value FROM $regionYtd WHERE sales_representant_office_id = $region AND brand_id NOT IN IN (22, 23) AND year = $year AND month = $month";
                    //var_dump($querryTV);
                    $querryONL = "SELECT SUM($value) AS $value FROM $regionYtd WHERE sales_representant_office_id = $region AND brand_id IN (25, 26) AND year = $year AND month = $month";
                    //var_dump($querryONL);
                    break;
            }
            
        }

        $resultTV = $con->query($querryTV);
        $valueTV = $sql->fetchSUM($resultTV, $value);

        $resultONL = $con->query($querryONL);
        $valueONL = $sql->fetchSUM($resultONL, $value);
    
        if ($region == 1 && $currencyID == 1 && $value == 'net') {
            $monthValues = array(($valueTV[$value]) * 0.8, ($valueONL[$value]) * 0.8, (($valueTV[$value] + $valueONL[$value])) * 0.8);
        }elseif($region == 1 && $currencyID == 1 && $value == 'gross'){
            $monthValues = array(($valueTV[$value]), ($valueONL[$value]), (($valueTV[$value] + $valueONL[$value])));
        }elseif($region == 1 && $currencyID != 1 && $value == 'net'){
            $monthValues = array(($valueTV[$value] / $brlPRate) * 0.8, ($valueONL[$value] / $brlPRate) * 0.8, (($valueTV[$value] + $valueONL[$value] / $brlPRate) * 0.8));
        }elseif($region == 1 && $currencyID != 1 && $value == 'gross'){
            $monthValues = array($valueTV[$value] / $brlPRate, $valueONL[$value] / $brlPRate, ($valueTV[$value] + $valueONL[$value]) / $brlPRate);
        }elseif($value == 'gross_revenue' || $value == 'net_revenue'){
            $monthValues = array($valueTV[$value] * $pRate, $valueONL[$value] * $pRate, ($valueTV[$value] + $valueONL[$value]) * $pRate);
        }
       
        return $monthValues;
    }

    public function ssRead($con, $sql, Int $region, Float $pRate, Float $brlPRate, String $value, String $day, String $month, String $realMonth, String $year, String $brands, Int $currencyID){
        
        $regionYtd = "daily_results";
        $month = $month + 0;
        $date = date("$year-$month-$day");
        $realMonth = $realMonth + 0;
        $date = date("$year-$realMonth-$day");

        switch ($brands){
            case "total":
                $querryTV = "SELECT SUM(read_dsc_tv + read_spt_tv + read_wm_tv) AS $value FROM $regionYtd WHERE real_date = '$date' AND month = '$month'";
                //var_dump($querryTV);
                $querryONL = "SELECT SUM(read_dsc_onl + read_spt_onl + read_wm_onl) AS $value FROM $regionYtd WHERE real_date = '$date' AND month = '$month'";
                //var_dump($querryONL);
                break;
            case "discovery":
                $querryTV = "SELECT SUM(read_dsc_tv) AS $value FROM $regionYtd WHERE real_date = '$date' AND month = '$month'";
                //var_dump($querryTV);
                $querryONL = "SELECT SUM(read_dsc_onl) AS $value FROM $regionYtd WHERE real_date = '$date' AND month = '$month'";
                //var_dump($querryONL);
                break;
            case "sony":
                $querryTV = "SELECT SUM(read_spt_tv) AS $value FROM $regionYtd WHERE real_date = '$date' AND month = '$month'";
                //var_dump($querryTV);
                $querryONL = "SELECT SUM(read_spt_onl) AS $value FROM $regionYtd WHERE real_date = '$date' AND month = '$month'";
                //var_dump($querryONL);
                break;
            case "wm":
                $querryTV = "SELECT SUM(read_wm_tv) AS $value FROM $regionYtd WHERE real_date = '$date' AND month = '$month'";
                //var_dump($querryTV);
                $querryONL = "SELECT SUM(read_wm_onl) AS $value FROM $regionYtd WHERE real_date = '$date' AND month = '$month'";
                //var_dump($querryONL);
                break;
        }
        
        $resultTV = $con->query($querryTV);
        $valueTV = $sql->fetchSUM($resultTV, $value);

        $resultONL = $con->query($querryONL);
        $valueONL = $sql->fetchSUM($resultONL, $value);

        if ($region == 1 && $currencyID == 1 && $value == 'net') {
            $monthValues = array(($valueTV[$value]) * 0.8, ($valueONL[$value]) * 0.8, (($valueTV[$value] + $valueONL[$value])) * 0.8);
        }elseif($region == 1 && $currencyID == 1 && $value == 'gross'){
            $monthValues = array(($valueTV[$value]), ($valueONL[$value]), (($valueTV[$value] + $valueONL[$value])));
        }elseif($region == 1 && $currencyID != 1 && $value == 'net'){
            $monthValues = array(($valueTV[$value] / $brlPRate) * 0.8, ($valueONL[$value] / $brlPRate) * 0.8, (($valueTV[$value] + $valueONL[$value] / $brlPRate) * 0.8));
        }elseif($region == 1 && $currencyID != 1 && $value == 'gross'){
            $monthValues = array($valueTV[$value] / $brlPRate, $valueONL[$value] / $brlPRate, ($valueTV[$value] + $valueONL[$value]) / $brlPRate);
        }elseif($region != 1 && $value == 'net'){
            $monthValues = array(($valueTV[$value] * $pRate) * 0.8, ($valueONL[$value] * $pRate) * 0.8, (($valueTV[$value] + $valueONL[$value]) * $pRate) * 0.8);
        }elseif($region != 1 && $value == 'gross'){
            $monthValues = array($valueTV[$value] * $pRate, $valueONL[$value] * $pRate, ($valueTV[$value] + $valueONL[$value]) * $pRate);
        }
       
        return $monthValues;
    }

    // == Essa função é usada para consultar a tabela 'plan_by_brand' == //
    public function plan($con, $sql, Int $region, Float $pRate, String $value, String $source, String $month, String $year, String $brands){
        switch ($brands){
            case "total":
                $querryTV = "SELECT SUM(revenue) AS $value FROM plan_by_brand INNER JOIN brand b on brand_id = b.ID WHERE sales_office_id = $region AND b.brand_group_id IN (1,2,3) AND source = '$source' AND b.type = 'Linear' AND year = $year AND month = $month AND type_of_revenue = '$value'";
                //var_dump($querryTV);
                $querryONL = "SELECT SUM(revenue) AS $value FROM plan_by_brand INNER JOIN brand b on brand_id = b.ID WHERE sales_office_id = $region AND b.brand_group_id IN (1,2,3) AND source = '$source' AND b.type = 'Non-Linear' AND year = $year AND month = $month AND type_of_revenue = '$value'";
                //var_dump($querryONL);
                break;
            case "discovery":
                $querryTV = "SELECT SUM(revenue) AS $value FROM plan_by_brand INNER JOIN brand b on brand_id = b.ID WHERE sales_office_id = $region AND b.brand_group_id IN (1) AND source = '$source' AND b.type = 'Linear' AND year = $year AND month = $month AND type_of_revenue = '$value'";
                //var_dump($querryTV);
                $querryONL = "SELECT SUM(revenue) AS $value FROM plan_by_brand INNER JOIN brand b on brand_id = b.ID WHERE sales_office_id = $region AND b.brand_group_id IN (1) AND source = '$source' AND b.type = 'Non-Linear' AND year = $year AND month = $month AND type_of_revenue = '$value'";
                //var_dump($querryONL);
                break;
            case "sony":
                $querryTV = "SELECT SUM(revenue) AS $value FROM plan_by_brand INNER JOIN brand b on brand_id = b.ID WHERE sales_office_id = $region AND b.brand_group_id IN (2) AND source = '$source' AND b.type = 'Linear' AND year = $year AND month = $month AND type_of_revenue = '$value'";
                //var_dump($querryTV);
                $querryONL = "SELECT SUM(revenue) AS $value FROM plan_by_brand INNER JOIN brand b on brand_id = b.ID WHERE sales_office_id = $region AND b.brand_group_id IN (2) AND source = '$source' AND b.type = 'Non-Linear' AND year = $year AND month = $month AND type_of_revenue = '$value'";
                //var_dump($querryONL);
                break;
            case "wm":
                $querryTV = "SELECT SUM(revenue) AS $value FROM plan_by_brand INNER JOIN brand b on brand_id = b.ID WHERE sales_office_id = $region AND b.brand_group_id IN (3) AND source = '$source' AND b.type = 'Linear' AND year = $year AND month = $month AND type_of_revenue = '$value'";
                //var_dump($querryTV);
                $querryONL = "SELECT SUM(revenue) AS $value FROM plan_by_brand INNER JOIN brand b on brand_id = b.ID WHERE sales_office_id = $region AND b.brand_group_id IN (3) AND source = '$source' AND b.type = 'Non-Linear' AND year = $year AND month = $month AND type_of_revenue = '$value'";
                //var_dump($querryONL);
                break;
        }

        $resultTV = $con->query($querryTV);
        $valueTV = $sql->fetchSUM($resultTV, $value);

        $resultONL = $con->query($querryONL);
        $valueONL = $sql->fetchSUM($resultONL, $value);

        $monthValues = array($valueTV[$value] * $pRate, $valueONL[$value] * $pRate, ($valueTV[$value] + $valueONL[$value]) * $pRate);

        //var_dump($monthValues);
        return $monthValues;
    }

    // == Função para calculo de porcentagem == //
    public function percentageCalculator(Float $value1, Float $value2){
        if ($value1 > 0 && $value2 > 0){
            $result = ($value1 / $value2) * 100;
            return $result;
        } else {
            return $result = 0;
        }
    }

    public function getActiveMonth(){
        $actualDay = date("d") + 0;
        $actualMonth = date("m") + 0;

        if($actualDay <= 5){
            $month = $actualMonth - 1;
        }else{
            $month = $actualMonth;
        }

        return $month;
    }

    // == Função construtora de matriz, ela é a responsavel em enviar para o front-end o formato final da tabela com os calculos realizados == //
    public function tableDailyResults($con, Int $region, String $value, String $log, Float $pRate, Float $brlPRate, String $brands, Int $currencyID){
        $sql = new sql();

        //$actualDate = date("m");

        $day = date('d', strtotime($log));
        $month = $this->getActiveMonth();
        $realMonth = date('m', strtotime($log));
        $year = date('Y', strtotime($log));
        $pYear = $year - 1;
        $ppYear = $pYear - 1;

        $anualYTD = array(0, 0, 0);
        $anualPLAN = array(0, 0, 0);
        $anualFCST = array(0, 0, 0);
        $anualSAP = array(0, 0, 0);
        $anualPSAP = array(0, 0, 0);
        $anualSs = array(0, 0, 0);

        $table = array();

        if($month == 12 || $month == 11){
            $month = 10;
        }

        for ($i = 0; $i < 3; $i++){
            $monthValues = array();

            // == Calculo mensal do CMAPS/YTD == //
            $ytd = $this->ytd($con, $sql, $region, $pRate, $brlPRate, $value, $day, $month + $i, $realMonth, $year, $brands, $currencyID);
            //var_dump($ytd);

            // == Calculo mensal do CMAPS/YTD do Ano anterior == //
            if($region == 1){
                $ss = $this->ssRead($con, $sql, $region ,$pRate, $brlPRate ,$value, $day, $month + $i, $realMonth, $year, $brands, $currencyID);
            } else {
                $ss = $this->ytd($con, $sql, $region, $pRate, $brlPRate, $value, $day, $month + $i, $realMonth, $year -1, $brands, $currencyID);
            }
            
            // == Calculo mensal do PLAN(target) == //
            $source = "target";
            $plan = $this->plan($con, $sql, $region, $pRate, $value, $source, $month + $i, $year, $brands);
            //var_dump($plan);

            // == Calculo mensal do FCST(corporate) == //
            $source = "corporate";
            $fcst = $this->plan($con, $sql, $region, $pRate, $value, $source, $month + $i, $year, $brands);
            //var_dump($fcst);

            // == Calculo mensal do SAP(actual) do ano anterior == //
            $source = "actual";
            $sap = $this->plan($con, $sql, $region, $pRate, $value, $source, $month + $i, $pYear, $brands);
            //var_dump($sap);

            // == Calculo mensal do SAP(actual) do ano retrasado == //
            $source = "actual";
            $pSap = $this->plan($con, $sql, $region, $pRate, $value, $source, $month + $i, $ppYear, $brands);
            //var_dump($pSap);

            // == Visto que as funções de consulta já trazem os valores separados de TV, ONL e TOTAL, esse 'for' faz a separação correspondente ao segmento e realiza os calcúlos necessarios == //
            for ($j = 0; $j < 3; $j++) {
                $monthYTD = $ytd[$j];
                $monthPLAN = $plan[$j];
                $monthFCST = $fcst[$j];
                $monthSAP = $sap[$j];
                $monthpSAP = $pSap[$j];
                $monthSs = $ss[$j];

                $monthPerSs = $this->percentageCalculator($ytd[$j],$ss[$j]);;
                $monthPerPLAN = $this->percentageCalculator($ytd[$j],$plan[$j]);
                $monthPerFCST = $this->percentageCalculator($ytd[$j],$fcst[$j]);
                $monthPerSAP = $this->percentageCalculator($ytd[$j],$sap[$j]);
                $monthPerPSAP = $this->percentageCalculator($ytd[$j],$pSap[$j]);

                $monthCalcs = array("currentYTD" => $monthYTD, 
                                    "currentPlan" => $monthPLAN, 
                                    "currentFcst" => $monthFCST, 
                                    "previousSS" => $monthSs, 
                                    "previousSap" => $monthSAP, 
                                    "pPSap"  => $monthpSAP, 
                                    "currentPlanPercent" => $monthPerPLAN, 
                                    "currentFcstPercent" => $monthPerFCST, 
                                    "ssPercent" => $monthPerSs, 
                                    "pSapPercent" => $monthPerSAP, 
                                    "ppSapPercent" => $monthPerPSAP);
                array_push($monthValues, $monthCalcs);
                //var_dump($month + $i);
            }

            array_push($table, $monthValues);
        }

        // == Calculo do primeiro mês até o atual (com base no filtro) == //
        $month = $this->getActiveMonth();
        for ($i = 1; $i <= $month; $i++){

            $anualValues = array();

            // == Calculo mensal do CMAPS/YTD == //
            $ytd = $this->ytd($con, $sql, $region, $pRate, $brlPRate,$value, $day, $i, $realMonth ,$year, $brands, $currencyID);
            //var_dump($ytd);

            // == Calculo mensal do CMAPS/YTD do Ano anterior == //
            if($region == 1){
                $ss = $this->ssRead($con, $sql, $region, $pRate, $brlPRate, $value, $day, $i, $realMonth, $year, $brands, $currencyID);
            } else {
                $ss = $this->ytd($con, $sql, $region, $pRate, $brlPRate, $value, $day, $i, $realMonth, $year -1, $brands, $currencyID);
            }

            // == Calculo mensal do PLAN(target) == //
            $source = "target";
            $plan = $this->plan($con, $sql, $region, $pRate, $value, $source, $i, $year, $brands);
            //var_dump($plan);

            // == Calculo mensal do FCST(corporate) == //
            $source = "corporate";
            $fcst = $this->plan($con, $sql, $region, $pRate, $value, $source, $i, $year, $brands);
            //var_dump($fcst);

            // == Calculo mensal do SAP(actual) do ano anterior == //
            $source = "actual";
            $sap = $this->plan($con, $sql, $region, $pRate, $value, $source, $i, $pYear, $brands);
            //var_dump($sap);

            // == Calculo mensal do SAP(actual) do ano retrasado == //
            $source = "actual";
            $pSap = $this->plan($con, $sql, $region, $pRate, $value, $source, $i, $ppYear, $brands);
            //var_dump($pSap);

            // == Esse 'for' faz a separação do valor correspondente ao segmento somando ao valor anterior no array sendo (0 => TV, 1 => ONL e 2 => TOTAL) == //
            for ($j = 0; $j < 3; $j++) {
                $anualYTD[$j] += $ytd[$j];
                $anualPLAN[$j] += $plan[$j];
                $anualFCST[$j] += $fcst[$j];
                $anualSAP[$j] += $sap[$j];
                $anualPSAP[$j] += $pSap[$j];
                $anualSs[$j] += $ss[$j];
                
            }
        }

        // == O calcúlo é feito separado do "for" pois é necessario fazer somente uma vez, separando os segmentos == //
        for ($i = 0; $i < 3; $i++){
            $anualPerSs = $this->percentageCalculator($anualYTD[$i],$anualSs[$i]);
            $anualPerPLAN = $this->percentageCalculator($anualYTD[$i],$anualPLAN[$i]);
            $anualPerFCST = $this->percentageCalculator($anualYTD[$i],$anualFCST[$i]);
            $anualPerSAP = $this->percentageCalculator($anualYTD[$i],$anualSAP[$i]);
            $anualPerPSAP = $this->percentageCalculator($anualYTD[$i],$anualPSAP[$i]);
            $anualCalcs = array("currentYTD" => $anualYTD[$i],
                                "currentPlan" => $anualPLAN[$i], 
                                "currentFcst" => $anualFCST[$i], 
                                "previousSS" => $anualSs[$i], 
                                "previousSap" => $anualSAP[$i], 
                                "pPSap" => $anualPSAP[$i], 
                                "currentPlanPercent" => $anualPerPLAN, 
                                "currentFcstPercent" => $anualPerFCST, 
                                "ssPercent" => $anualPerSs, 
                                "pSapPercent" => $anualPerSAP, 
                                "ppSapPercent" => $anualPerPSAP);

            array_push($anualValues, $anualCalcs);
        }
       
        array_push($table, $anualValues);
        //var_dump(sizeof($table));

        for($j=0; $j < sizeof($table); $j++){
            for($i=0; $i < sizeof($table[$j]); $i++){
                //var_dump($table[$j][$i]);
            }
        }

        //var_dump($table);
        return $table;
    }

    public function getLog($con, String $log, String $region){
        $day = date('d', strtotime($log));
        $month = date('m', strtotime($log));
        $year = date('Y', strtotime($log));
        $date = date("$year-$month-$day");
        if($region == 1){
            $sql = new sql();
            $querry = "SELECT DISTINCT (log) FROM daily_results WHERE real_date = '$date'";
            $result = $con->query($querry);
            $from = array("log");
            $value = $sql->fetch($result, $from, $from);
            if ($value) {
               $realDate = $value[0]['log'];
                $day = date('d', strtotime($realDate));
                $month = date('m', strtotime($realDate));
                $formattedDate = date("$day/$month");
            }else{
                $formattedDate = null;
            }
            
            return $formattedDate;
        }else{
            $day = date('d', strtotime($log));
            $month = date('m', strtotime($log));
            return $date;
        }
        

    }

}
