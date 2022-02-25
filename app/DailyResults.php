<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\sql;

class DailyResults extends Model{

    // === Essa função é usada para consultar a tabela 'CMAPS' ou 'YTD' de acordo com a região === //
    public function ytd($con, $sql, Int $region, Float $pRate, String $value, String $day, String $month, String $year){
        if ($region == 1) {
            // == Caso a região seja "Brazil (1)", ele leva em consideração a base do CMAPS e o valor do log diario (ainda a ser implementado) == //
            $regionYtd = "CMAPS";

            $querryTV = "SELECT SUM($value) AS $value FROM $regionYtd WHERE brand_id NOT IN (9, 13, 14, 15) AND year = $year AND month = $month";
            //var_dump($querryTV);

            $querryONL = "SELECT SUM($value) AS $value FROM $regionYtd WHERE brand_id IN (9, 13, 14, 15) AND year = $year AND month = $month";
            //var_dump($querryONL);
        } else {
            // == Caso a região não seja "Brazil (1)", ele leva em consideração a base do YTD e ignora o valor do log diario (levando só em consideração o mês e o ano) == //
            $regionYtd = "YTD";

            // == Alteração na $value para usar como parametro de consulta, de acordo como esta no banco == //
            if ($value == "gross") {
                $value = "gross_revenue";
            } else {
                $value = "net_revenue";
            }

            $querryTV = "SELECT SUM($value) AS $value FROM $regionYtd WHERE sales_representant_office_id = $region AND brand_id NOT IN (9, 13, 14, 15) AND year = $year AND month = $month";
            //var_dump($querryTV);

            $querryONL = "SELECT SUM($value) AS $value FROM $regionYtd WHERE sales_representant_office_id = $region AND brand_id IN (9, 13, 14, 15) AND year = $year AND month = $month";
            //var_dump($querryONL);
        }

        $resultTV = $con->query($querryTV);
        $valueTV = $sql->fetchSUM($resultTV, $value);

        $resultONL = $con->query($querryONL);
        $valueONL = $sql->fetchSUM($resultONL, $value);

        $monthValues = array($valueTV[$value] * $pRate, $valueONL[$value] * $pRate, ($valueTV[$value] + $valueONL[$value]) * $pRate);

        //var_dump($monthValues);
        return $monthValues;
    }

    // === Essa função é usada para consultar a tabela 'plan_by_brand' === //
    public function plan($con, $sql, Int $region, Float $pRate, String $value, String $source, String $month, String $year){
        $querryTV = "SELECT SUM(revenue) AS $value FROM plan_by_brand WHERE sales_office_id = $region AND brand_id NOT IN (9, 13, 14, 15) AND source = '$source' AND year = $year AND month = $month AND type_of_revenue = '$value'";
        //var_dump($querryTV);

        $querryONL = "SELECT SUM(revenue) AS $value FROM plan_by_brand WHERE sales_office_id = $region AND brand_id IN (9, 13, 14, 15) AND source = '$source' AND year = $year AND month = $month AND type_of_revenue = '$value'";
        //var_dump($querryONL);

        $resultTV = $con->query($querryTV);
        $valueTV = $sql->fetchSUM($resultTV, $value);

        $resultONL = $con->query($querryONL);
        $valueONL = $sql->fetchSUM($resultONL, $value);

        $monthValues = array($valueTV[$value] * $pRate, $valueONL[$value] * $pRate, ($valueTV[$value] + $valueONL[$value]) * $pRate);

        //var_dump($monthValues);
        return $monthValues;
    }

    public function percentageCalculator(Float $value1, Float $value2){
        if ($value1 > 0 && $value2 > 0){
            $result = number_format(($value2 / $value1) * 100);
            return $result;
        } else {
            return $result = 0;
        }
    }


    public function tableDailyResults($con, Int $region, String $value, String $log, Float $pRate){
        $sql = new sql();

        $day = date('d', strtotime($log));
        $month = date('m', strtotime($log));
        $year = date('Y', strtotime($log));

        $table = array();

        for ($i = 0; $i < 3; $i++){
            $monthValues = array();

            // == Calculo mensal do CMAPS/YTD == //
            $ytd = $this->ytd($con, $sql, $region, $pRate, $value, $day, $month, $year);
            //var_dump($ytd);

            // == Calculo mensal do PLAN(target) == //
            $source = "target";
            $plan = $this->plan($con, $sql, $region, $pRate, $value, $source, $month, $year);
            //var_dump($ytd);

            // == Calculo mensal do FCST(corporate) == //
            $source = "corporate";
            $fcst = $this->plan($con, $sql, $region, $pRate, $value, $source, $month, $year);
            //var_dump($ytd);

            // == Calculo mensal do SAP(actual) do ano anterior == //
            $source = "actual";
            $sap = $this->plan($con, $sql, $region, $pRate, $value, $source, $month, $year - 1);
            //var_dump($ytd);

            // == Visto que as funções de consulta já trazem os valores separados de TV, ONL e TOTAL, esse 'for' faz a separação correspondente ao segmento e realiza os calcúlos necessarios == //
            for ($j = 0; $j < 3; $j++) {
                $monthYTD = $ytd[$j];
                $monthPLAN = $plan[$j];
                $monthFCST = $fcst[$j];
                $monthSAP = $sap[$j];

                $monthYOY = $sap[$j] - $ytd[$j];

                $monthPerPLAN = $this->percentageCalculator($ytd[$j],$plan[$j]);
                $monthPerFCST = $this->percentageCalculator($ytd[$j],$fcst[$j]);
                $monthPerSAP = $this->percentageCalculator($ytd[$j],$sap[$j]);

                $monthCalcs = array($monthYTD, $monthPLAN, $monthFCST, $monthSAP, $monthYOY, $monthPerPLAN, $monthPerFCST, $monthPerSAP);
                array_push($monthValues, $monthCalcs);
            }

            array_push($table, $monthValues);
        }
        var_dump($table);
    }
}
