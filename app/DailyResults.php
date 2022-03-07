<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\sql;

class DailyResults extends Model{

    // == Essa função é usada para consultar a tabela 'CMAPS' ou 'YTD' de acordo com a região == //
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

    // == Essa função é usada para consultar a tabela 'plan_by_brand' == //
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

    // == Função para calculo de porcentagem == //
    public function percentageCalculator(Float $value1, Float $value2){
        if ($value1 > 0 && $value2 > 0){
            $result = number_format(($value2 / $value1) * 100);
            return $result;
        } else {
            return $result = 0;
        }
    }

    // == Função construtora de matriz, ela é a responsavel em enviar para o front-end o formato final da tabela com os calculos realizados == //
    public function tableDailyResults($con, Int $region, String $value, String $log, Float $pRate){
        $sql = new sql();

        $day = date('d', strtotime($log));
        $month = date('m', strtotime($log));
        $year = date('Y', strtotime($log));

        $anualYTD = array(0, 0, 0);
        $anualPLAN = array(0, 0, 0);
        $anualFCST = array(0, 0, 0);
        $anualSAP = array(0, 0, 0);
        $anualPSAP = array(0, 0, 0);


        $table = array();

        for ($i = 0; $i < 3; $i++){
            $monthValues = array();

            // == Calculo mensal do CMAPS/YTD == //
            $ytd = $this->ytd($con, $sql, $region, $pRate, $value, $day, $month + $i, $year);
            //var_dump($ytd);

            // == Calculo mensal do PLAN(target) == //
            $source = "target";
            $plan = $this->plan($con, $sql, $region, $pRate, $value, $source, $month + $i, $year);
            //var_dump($plan);

            // == Calculo mensal do FCST(corporate) == //
            $source = "corporate";
            $fcst = $this->plan($con, $sql, $region, $pRate, $value, $source, $month + $i, $year);
            //var_dump($fcst);

            // == Calculo mensal do SAP(actual) do ano anterior == //
            $source = "actual";
            $sap = $this->plan($con, $sql, $region, $pRate, $value, $source, $month + $i, $year - 1);
            //var_dump($sap);

            // == Calculo mensal do SAP(actual) do ano retrasado == //
            $source = "actual";
            $pSap = $this->plan($con, $sql, $region, $pRate, $value, $source, $month + $i, $year - 2);
            //var_dump($pSap);

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
                $monthPerPSAP = $this->percentageCalculator($ytd[$j],$pSap[$j]);

                $monthCalcs = array($monthYTD, $monthPLAN, $monthFCST, $monthSAP, $monthYOY, $monthPerPLAN, $monthPerFCST, $monthPerSAP, $monthPerPSAP);
                array_push($monthValues, $monthCalcs);
            }

            array_push($table, $monthValues);
        }

        // == Calculo do primeiro mês até o atual (com base no filtro) == //
        for ($i = 1; $i <= $month; $i++){

            $anualValues = array();

            // == Calculo mensal do CMAPS/YTD == //
            $ytd = $this->ytd($con, $sql, $region, $pRate, $value, $day, $i, $year);
            //var_dump($ytd);

            // == Calculo mensal do PLAN(target) == //
            $source = "target";
            $plan = $this->plan($con, $sql, $region, $pRate, $value, $source, $i, $year);
            //var_dump($plan);

            // == Calculo mensal do FCST(corporate) == //
            $source = "corporate";
            $fcst = $this->plan($con, $sql, $region, $pRate, $value, $source, $i, $year);
            //var_dump($fcst);

            // == Calculo mensal do SAP(actual) do ano anterior == //
            $source = "actual";
            $sap = $this->plan($con, $sql, $region, $pRate, $value, $source, $i, $year - 1);
            //var_dump($sap);

            // == Calculo mensal do SAP(actual) do ano retrasado == //
            $source = "actual";
            $pSap = $this->plan($con, $sql, $region, $pRate, $value, $source, $i, $year - 2);
            //var_dump($pSap);

            // == Esse 'for' faz a separação correspondente ao segmento somando ao valor anterior no array sendo (0 => TV, 1 => ONL e 2 => TOTAL) == //
            for ($j = 0; $j < 3; $j++) {
                $anualYTD[$j] += $ytd[$j];
                $anualPLAN[$j] += $plan[$j];
                $anualFCST[$j] += $fcst[$j];
                $anualSAP[$j] += $sap[$j];
                $anualPSAP[$j] += $pSap[$j];
            }
        }

        // == O calcúlo é feito separado pois é necessario fazer somente uma vez, separando os segmentos == //
        for ($i = 0; $i < 3; $i++){
            $anualYOY = $anualSAP[$i] - $anualYTD[$i];
            $anualPerPLAN = $this->percentageCalculator($anualYTD[$i],$anualPLAN[$i]);
            $anualPerFCST = $this->percentageCalculator($anualYTD[$i],$anualFCST[$i]);
            $anualPerSAP = $this->percentageCalculator($anualYTD[$i],$anualSAP[$i]);
            $anualPerPSAP = $this->percentageCalculator($anualYTD[$i],$anualPSAP[$i]);
            $anualCalcs = array($anualYTD[$i], $anualPLAN[$i], $anualFCST[$i], $anualSAP[$i], $anualYOY, $anualPerPLAN, $anualPerFCST, $anualPerSAP, $anualPerPSAP);
            array_push($anualValues, $anualCalcs);
        }
       
        array_push($table, $anualValues);

        var_dump($table);
        return $table;
    }
}
