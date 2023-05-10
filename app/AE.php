<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use App\pAndR;
use App\brand;
use App\salesRep;
use App\base;
use App\sql;
use App\pRate;

class AE extends pAndR{
    
    //THIS FUNCTION MAKE THE TOTAL FOR THE SALES REP MERGING THE COMPANIES AND CLIENTS
    public function makeRepTable(Object $con, int $salesRep, Object $pr, int $year, int $pYear, int $region, int $currencyID, string $value){
        $company = array('3','1','2');
        $month = array('1','2','3','4','5','6','7','8','9','10','11','12');
        
        if($currencyID == 1){
            $pRate = 1;
        }else{
            $pRate = $pr->getPRateByRegionAndYear($con,array($region), array($year));    
        }        
        
        for ($c=0; $c <sizeof($company); $c++) { //this for is to make the interactons for the 3 companies
            for ($m=0; $m <sizeof($month) ; $m++) {  //this one is to the months

                $currentBookings[$c][$m] = floatval($this->getValueByMonth($con,$salesRep,$year,$value,$month[$m],'bookings',null,null, $region,null,$company[$c])[0]['revenue'])/$pRate;                
                $previousBookings[$c][$m] = floatval($this->getValueByMonth($con,$salesRep,$pYear,$value,$month[$m],'bookings',null, null, $region,null,$company[$c])[0]['revenue'])/$pRate;
                $currentTarget[$c][$m] = floatval($this->getValueByMonth($con,$salesRep,$year,$value,$month[$m],'target',null, null, $region,null,$company[$c])[0]['revenue'])/$pRate;
                $payTvForecast[$c][$m] = floatval($this->getValueByMonth($con,$salesRep,$year,$value,$month[$m],'forecast',null, null, $region,'pay tv', $company[$c])[0]['revenue'])/$pRate;
                $digitalForecast[$c][$m] = floatval($this->getValueByMonth($con,$salesRep,$year,$value,$month[$m],'forecast',null, null, $region,'digital',$company[$c])[0]['revenue'])/$pRate;
                $currentPayTvBookings[$c][$m] = floatval($this->getValueByMonth($con,$salesRep,$year,$value,$month[$m],'bookings',null,null, $region,'pay tv',$company[$c])[0]['revenue'])/$pRate;
                $currentDigitalBookings[$c][$m] = floatval($this->getValueByMonth($con,$salesRep,$year,$value,$month[$m],'bookings',null,null, $region,'digital',$company[$c])[0]['revenue'])/$pRate;
                //var_dump($currentPayTvBookings);
                $totalCurrentBookings[$m] = floatval($this->getValueByMonth($con,$salesRep,$year,$value,$month[$m],'bookings',null,null, $region,null,'1,2,3')[0]['revenue'])/$pRate;           
                $totalPreviousBookings[$m] = floatval($this->getValueByMonth($con,$salesRep,$pYear,$value,$month[$m],'bookings',null, null, $region,null,'1,2,3')[0]['revenue'])/$pRate;
                $totalCurrentTarget[$m] = floatval($this->getValueByMonth($con,$salesRep,$year,$value,$month[$m],'target',null, null, $region,null,'1,2,3')[0]['revenue'])/$pRate;
                $totalPayTvForecast[$m] = floatval($this->getValueByMonth($con,$salesRep,$year,$value,$month[$m],'forecast',null, null, $region,'pay tv', '1,2,3')[0]['revenue'])/$pRate;
                $totalDigitalForecast[$m] = floatval($this->getValueByMonth($con,$salesRep,$year,$value,$month[$m],'forecast',null, null, $region,'digital','1,2,3')[0]['revenue'])/$pRate;
                $totalCurrentPayTvBookings[$m] = floatval($this->getValueByMonth($con,$salesRep,$year,$value,$month[$m],'bookings',null,null, $region,'pay tv','1,2,3')[0]['revenue'])/$pRate;           
                $totalCurrentDigitalBookings[$m] = floatval($this->getValueByMonth($con,$salesRep,$year,$value,$month[$m],'bookings',null,null, $region,'digital','1,2,3')[0]['revenue'])/$pRate;           
                $payTvForecast[$c] = $this->addFcstWithBooking($currentPayTvBookings[$c],$payTvForecast[$c]);
                $digitalForecast[$c] = $this->addFcstWithBooking($currentDigitalBookings[$c],$digitalForecast[$c]);    

                
                
            }
            
            $pivot[$c] = array('currentBookings' => $this->addQuartersAndTotal($currentBookings[$c]),'previousBookings' => $this->addQuartersAndTotal($previousBookings[$c]), 'currentTarget' => $this->addQuartersAndTotal($currentTarget[$c]), 'payTvForecast' => $this->addQuartersAndTotal($payTvForecast[$c]), 'digitalForecast' => $this->addQuartersAndTotal($digitalForecast[$c])); 

            
            
        }

        $totalPayTvForecast = $this->addFcstWithBooking($totalCurrentPayTvBookings,$totalPayTvForecast);

        $totalDigitalForecast = $this->addFcstWithBooking($totalCurrentDigitalBookings,$totalDigitalForecast);

        $pivotTotal = array('currentBookings' => $this->addQuartersAndTotal($totalCurrentBookings),'previousBookings' => $this->addQuartersAndTotal($totalPreviousBookings), 'currentTarget' => $this->addQuartersAndTotal($totalCurrentTarget), 'payTvForecast' => $this->addQuartersAndTotal($totalPayTvForecast), 'digitalForecast' => $this->addQuartersAndTotal($totalDigitalForecast));  
        
       

        $table = array('companyValues' => $pivot, 'total' => $pivotTotal);

       // var_dump($table['total']);
        return $table;
    }

    //THIS FUNCTION MAKE ALL THE CLIENTS TABLE TO PASS TO FRONT
    public function makeClientsTable(Object $con, int $salesRep, Object $pr, int $year, int $pYear, int $region, int $currencyID, string $value){
        $month = 0;
        $company = array('3','1','2');
        $month = array('1','2','3','4','5','6','7','8','9','10','11','12');
        
        if($currencyID == 1){
            $pRate = 1;
        }else{
            $pRate = $pr->getPRateByRegionAndYear($con,array($region), array($year));    
        }
        
        $clients = $this->getClientByRep($con, $salesRep, $region, $year, $pYear);

        //var_dump($clients);
        for ($a=0; $a <sizeof($clients) ; $a++) { 
            for ($c=0; $c <sizeof($company); $c++) { //this for is to make the interactons for the 3 companies
                for ($m=0; $m <sizeof($month) ; $m++) {  //this one is to the months
                    //var_dump($month);
                    $currentBookings[$a][$c][$m] = floatval($this->getValueByMonth($con,$salesRep,$year,$value,$month[$m],'bookings',$clients[$a]['clientID'],$clients[$a]['agencyID'], $region,null,$company[$c])[0]['revenue'])/$pRate;  
                    $previousBookings[$a][$c][$m] = floatval($this->getValueByMonth($con,$salesRep,$pYear,$value,$month[$m],'bookings',$clients[$a]['clientID'],$clients[$a]['agencyID'], $region,null,$company[$c])[0]['revenue'])/$pRate;        
                    $payTvForecast[$a][$c][$m] = floatval($this->getValueByMonth($con,$salesRep,$year,$value,$month[$m],'forecast',$clients[$a]['clientID'],$clients[$a]['agencyID'], $region,'pay tv', $company[$c])[0]['revenue'])/$pRate;
                    $digitalForecast[$a][$c][$m] = floatval($this->getValueByMonth($con,$salesRep,$year,$value,$month[$m],'forecast',$clients[$a]['clientID'],$clients[$a]['agencyID'], $region,'diigital',$company[$c])[0]['revenue'])/$pRate;
                    $currentPayTvBookings[$a][$c][$m] = floatval($this->getValueByMonth($con,$salesRep,$year,$value,$month[$m],'bookings',$clients[$a]['clientID'],$clients[$a]['agencyID'], $region,'pay tv',$company[$c])[0]['revenue'])/$pRate;
                    $currentDigitalBookings[$a][$c][$m] = floatval($this->getValueByMonth($con,$salesRep,$year,$value,$month[$m],'bookings',$clients[$a]['clientID'],$clients[$a]['agencyID'], $region,'digital',$company[$c])[0]['revenue'])/$pRate;
                    $currentTarget[$a][$c][$m] = 0;

                    
               
                }

                $payTvForecast[$a][$c] = $this->addFcstWithBooking($currentPayTvBookings[$a][$c],$payTvForecast[$a][$c]);
                $digitalForecast[$a][$c] = $this->addFcstWithBooking($currentDigitalBookings[$a][$c],$digitalForecast[$a][$c]);

                $pivot[$a][$c] = array('currentBookings' => $this->addQuartersAndTotal($currentBookings[$a][$c]),'previousBookings' => $this->addQuartersAndTotal($previousBookings[$a][$c]), 'payTvForecast' => $this->addQuartersAndTotal($payTvForecast[$a][$c]), 'digitalForecast' => $this->addQuartersAndTotal($digitalForecast[$a][$c]), 'currentTarget' => $this->addQuartersAndTotal($currentTarget[$a][$c]));
                
            } 

            for ($m=0; $m <sizeof($month) ; $m++) { 
            
                $totalCurrentBookings[$a][$m] = floatval($this->getValueByMonth($con,$salesRep,$year,$value,$month[$m],'bookings',$clients[$a]['clientID'],$clients[$a]['agencyID'], $region,null,'1,2,3')[0]['revenue'])/$pRate;           
                $totalPreviousBookings[$a][$m] = floatval($this->getValueByMonth($con,$salesRep,$pYear,$value,$month[$m],'bookings',$clients[$a]['clientID'],$clients[$a]['agencyID'], $region,null,'1,2,3')[0]['revenue'])/$pRate;
                $totalCurrentTarget[$a][$m] = 0;
                $totalPayTvForecast[$a][$m] = floatval($this->getValueByMonth($con,$salesRep,$year,$value,$month[$m],'forecast',$clients[$a]['clientID'],$clients[$a]['agencyID'], $region,'pay tv', '1,2,3')[0]['revenue'])/$pRate;
                $totalDigitalForecast[$a][$m] = floatval($this->getValueByMonth($con,$salesRep,$year,$value,$month[$m],'forecast',$clients[$a]['clientID'],$clients[$a]['agencyID'], $region,'diigital','1,2,3')[0]['revenue'])/$pRate;
                $totalCurrentPayTvBookings[$a][$m] = floatval($this->getValueByMonth($con,$salesRep,$year,$value,$month[$m],'bookings',$clients[$a]['clientID'],$clients[$a]['agencyID'], $region,'pay tv','1,2,3')[0]['revenue'])/$pRate;           
                $totalCurrentDigitalBookings[$a][$m] = floatval($this->getValueByMonth($con,$salesRep,$year,$value,$month[$m],'bookings',$clients[$a]['clientID'],$clients[$a]['agencyID'], $region,'digital','1,2,3')[0]['revenue'])/$pRate;
                 
            }              
            
            $totalPayTvForecast[$a] = $this->addFcstWithBooking($totalCurrentPayTvBookings[$a],$totalPayTvForecast[$a]);
            $totalDigitalForecast[$a] = $this->addFcstWithBooking($totalCurrentDigitalBookings[$a],$totalDigitalForecast[$a]);

            $totalPivot[$a] = array('currentBookings' => $this->addQuartersAndTotal($totalCurrentBookings[$a]),'previousBookings' => $this->addQuartersAndTotal($totalPreviousBookings[$a]), 'currentTarget' => $this->addQuartersAndTotal($totalCurrentTarget[$a]), 'payTvForecast' => $this->addQuartersAndTotal($totalPayTvForecast[$a]), 'digitalForecast' => $this->addQuartersAndTotal($totalDigitalForecast[$a]));
       
            $clientInfo[$a] = array('clientName' => $clients[$a]['clientName'], 'clientID' => $clients[$a]['clientID'],'agencyName' => $clients[$a]['agencyName'],'agencyID' => $clients[$a]['agencyID']);
        }

       
        $table = array('clientInfo' => $clientInfo,'companyValues' => $pivot,'total' => $totalPivot);

        //var_dump($table);
       
       // var_dump($table['clientInfo']);
       return $table;
    }

    //THIS FUNCTION SAVE OR UPDATE THE FORECAST MADE BY THE SALES REP
    public function saveForecast(Object $con, int $client, int $agency, int $year, String $value, String $company, String $month, int $salesRep, String $platform, string $forecastValue, int $currency){
        $sql = new sql();
        //var_dump($forecastValue);
        $selectQuery = "SELECT agency_id AS agency, client_id AS client, salesRep as salesRep
                        FROM ae_forecast
                        WHERE sales_rep_id = $salesRep
                        AND client_id = $client
                        AND agency_id = $agency
                        AND currency_id = $currency
                        AND value = '$value'
                        AND platform = $platform 
                        AND company = $company
                        AND month = $month
                        AND year = $year";
        //var_dump($selectQuery);
        $from = array('agency', 'client','salesRep');
        $selectResultQuery = $con->query($selectQuery);
        $resultSelect = $sql->fetch($selectResultQuery, $from, $from);

        if ($resultSelect != false){
            $updateQuery = "UPDATE ae_forecast 
                        SET revenue = $forecastValue                        
                        WHERE sales_rep_id = $salesRep
                        AND client_id = $client
                        AND agency_id = $agency
                        AND month = $month
                        AND company_id = $company
                        AND currency = $currency
                        AND value = '$value'
                        AND year = $year 
                        AND platform = $platform";

            $resultQuery = $con->query($updateQuery);
        }else{
            $insertQuery = "INSERT INTO  ae_forecast
                        SET revenue = $forecastValue,
                        sales_rep_id = $salesRep,
                        client_id = $client,
                        agency_id = $agency,
                        month = $month,
                        company_id = $company,
                        currency = $currency,
                        value = '$value',
                        year = $year,
                        platform = '$platform'";

            $resultInsertQuery = $con->query($insertQuery);
            //var_dump($insertQuery);
        }
    }

    //THIS FUNCTION GET THE VALUE BY MONTH AND COMPANY BY CLIENT AND REP
    public function getValueByMonth(Object $con, int $salesRep, int $year, string $value, int $month, string $table, int $client=null, int $agency=null, int $regionID, string $platform=null, string $company=null){
        $sql = new sql();

        if ($table == 'bookings') {
            $value .= "_value";    
        }elseif ($table == 'target') {
            strtoupper($value);
        }
        
        switch($table){
            case 'bookings':

                if ($client == null && $agency == null) {
                    if ($platform != null) {
                        if ($platform == 'pay tv') {
                            $select = "SELECT sum($value) as revenue
                                FROM wbd w
                                LEFT JOIN brand b on b.id = w.brand_id
                                WHERE w.current_sales_rep_id = $salesRep
                                AND w.year = $year
                                AND w.month = $month
                                AND (b.brand_group_id IN ($company))
                                AND w.feed_type = 'Pay TV'
                                "; 
                                //var_dump($select);
                        }else{
                            $select = "SELECT sum($value) as revenue
                                FROM wbd w
                                LEFT JOIN brand b on b.id = w.brand_id
                                WHERE w.current_sales_rep_id = $salesRep
                                AND w.year = $year
                                AND w.month = $month
                                AND (b.brand_group_id IN ($company))
                                AND w.feed_type = 'Digital'
                                "; 
                        }
                    }else{
                        $select = "SELECT sum($value) as revenue
                            FROM wbd w
                            LEFT JOIN brand b on b.id = w.brand_id
                            WHERE w.current_sales_rep_id = $salesRep
                            AND w.year = $year
                            AND w.month = $month
                            AND (b.brand_group_id IN ($company))
                            ";
                    }
                }else{
                    if ($platform != null) {
                        if ($platform == 'pay tv') {
                            $select = "SELECT sum($value) as revenue
                                FROM wbd w
                                LEFT JOIN brand b on b.id = w.brand_id
                                LEFT JOIN client c ON c.ID = w.client_id
                                LEFT JOIN agency a ON a.ID = w.agency_id
                                WHERE w.year = $year
                                AND w.month = $month
                                AND c.ID = $client
                                AND a.ID = $agency
                                AND (b.brand_group_id IN ($company))
                                AND w.feed_type = 'Pay TV'
                                "; 
                        }else{
                            $select = "SELECT sum($value) as revenue
                                FROM wbd w
                                LEFT JOIN brand b on b.id = w.brand_id
                                LEFT JOIN client c ON c.ID = w.client_id
                                LEFT JOIN agency a ON a.ID = w.agency_id
                                WHERE w.year = $year
                                AND w.month = $month
                                AND c.ID = $client
                                AND a.ID = $agency
                                AND (b.brand_group_id IN ($company))
                                AND w.feed_type = 'Digital'
                                "; 
                        }
                    }else{
                        $select = "SELECT sum($value) as revenue
                            FROM wbd w
                            LEFT JOIN brand b on b.id = w.brand_id
                            LEFT JOIN client c ON c.ID = w.client_id
                            LEFT JOIN agency a ON a.ID = w.agency_id
                            WHERE w.year = $year
                            AND w.month = $month
                            AND c.ID = $client
                            AND a.ID = $agency
                            AND (b.brand_group_id IN ($company))
                            ";    
                    }                   

                }
              
              //echo "<pre>$select</pre>";

                $query = $con->query($select);
                $from = array('revenue');
                $result = $sql->fetch($query,$from,$from);
                
                if ($result[0]['revenue'] == null) {
                    $result[0]['revenue'] = 0.0;
                }
                //var_dump($result);
                break;
            case 'target':
                $select = "SELECT sum(pbs.value) as revenue
                            FROM plan_by_sales pbs
                            LEFT JOIN brand b on b.id = pbs.brand_id
                            WHERE pbs.sales_rep_id = $salesRep
                            AND pbs.year = $year
                            AND pbs.month = $month
                            AND pbs.type_of_revenue = '$value'
                            AND pbs.region_id = $regionID
                            AND (b.brand_group_id IN ($company))
                            ";
                //var_dump($select);
                $query = $con->query($select);
                $from = array('revenue');
                $result = $sql->fetch($query,$from,$from);
                
                if ($result[0]['revenue'] == null) {
                    $result[0]['revenue'] = 0.0;
                }
                
                break;
            case 'forecast':
                $select = "SELECT sum(f.revenue) as revenue
                            FROM ae_forecast f
                            WHERE f.sales_rep_id = $salesRep
                            AND f.year = $year
                            AND f.month = $month
                            AND f.value = '$value'
                            AND (f.company_id IN ($company)) 
                            AND (f.platform = '$platform')
                            ";
                    //var_dump($select);
                $query = $con->query($select);
                $from = array('revenue');
                $result = $sql->fetch($query,$from,$from);
                
                if ($result[0]['revenue'] == null) {
                    $result[0]['revenue'] = 0.0;
                }
                
                break;
        }

        return $result;
    }

    //THIS FUNCTION GET ALL THE CLIENTS FOR THE SELECTED SALES REP
    public function getClientByRep(Object $con,int $salesRep, int $region, int $year, int $pYear){
        $sql = new sql();

        $select = "SELECT DISTINCT c.name as clientName, c.ID as clientID, a.name as agencyName, a.ID as agencyID
                    FROM wbd w
                    LEFT JOIN client c ON c.ID = w.client_id
                    LEFT JOIN agency a ON a.ID = w.agency_id
                    WHERE (w.current_sales_rep_id = \"$salesRep\" )
                    AND w.year IN (\"$year\", \"$pYear\")                    
                    ORDER BY 1
                    ";
        $query = $con->query($select);
        $from = array('clientName', 'clientID','agencyName','agencyID');
        $result = $sql->fetch($query,$from,$from);
        //var_dump($result);
        return $result;
    }

    //THIS FUNCTION ADD THE QUARTERS AND THE TOTAL TO THE VARIABLE
    public function addQuartersAndTotal( Array $tgt){
        //JAN,FEB,MAR
        $tgtWQ[0] = $tgt[0];
        $tgtWQ[1] = $tgt[1];
        $tgtWQ[2] = $tgt[2];

        // Q1
        $tgtWQ[3] = $tgtWQ[0] + $tgtWQ[1] + $tgtWQ[2];

        //APR,MAI,JUN
        $tgtWQ[4] = $tgt[3];
        $tgtWQ[5] = $tgt[4];
        $tgtWQ[6] = $tgt[5];

        // Q2
        $tgtWQ[7] = $tgtWQ[4] + $tgtWQ[5] + $tgtWQ[6];

        //JUL,AUG,SEP
        $tgtWQ[8] = $tgt[6];
        $tgtWQ[9] = $tgt[7];
        $tgtWQ[10] = $tgt[8];

        // Q3
        $tgtWQ[11] = $tgtWQ[8] + $tgtWQ[9] + $tgtWQ[10];

        //OCT,NOV,DEC
        $tgtWQ[12] = $tgt[9];
        $tgtWQ[13] = $tgt[10];
        $tgtWQ[14] = $tgt[11];

        // Q4
        $tgtWQ[15] = $tgtWQ[12] + $tgtWQ[13] + $tgtWQ[14];

        $tgtWQ[16] = $tgtWQ[3] + $tgtWQ[7] + $tgtWQ[11] + $tgtWQ[15];

        return $tgtWQ;
    }

    //THIS FUNCTION PLACE THE BOOKINGS VALUES TO CLOSED MONTHS IN THE FORECAST ARRAY
    public function addFcstWithBooking(Array $booking, Array $fcst){

        $date = date('n')-2;

        if ($date < 3) {
        }elseif ($date < 6) {
            $date ++;
        }elseif ($date < 9) {
            $date += 2;
        }else{
            $date += 3;
        }
        
        //var_dump($booking);
       
        for ($c=0; $c < sizeof($booking); $c++) { 
            if ($c<$date) {
                $sum[$c] = $booking[$c];
            }else{
                $sum[$c] = $fcst[$c];
            }
        }       

        return $sum;
    }

   
}
