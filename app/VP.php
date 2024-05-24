<?php

namespace App;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use App\pAndR;
use App\brand;
use App\salesRep;
use App\base;
use App\sql;

class VP extends pAndR{
    
    public function managerTable(Object $con,Array $manager,String $month, Int $year, Int $pYear,$repsTable){
        $sales = new salesRep();
        $pr = new pRate();

        $managerId = $sales->getGroupIdByName($con,$manager);
        $company = array('1','2','3');
        $currencyID = 1;
        $region = 1;
        //var_dump($manager);
        $totalPayTvForecast = 0;
        $totalDigitalForecast = 0;
        $totalForecast = 0;
        $totalBookings = 0;
        $totalPending = 0;
        $totalPayTvBookings = 0;
        $totalDigitalBookings = 0;
        $totalPreviousBookings = 0;
        $totalTarget = 0;
        
        for ($c=0; $c <sizeof($company) ; $c++) { 
            $payTvForecast[$c] = 0;
            $digitalForecast[$c] = 0;
            $forecast[$c] = 0;
            $bookings[$c] = 0;
            $pending[$c] = 0;
            $digitalBookings[$c] = 0;
            $payTvBookings[$c] = 0;
            $previousBookings[$c] = 0;
            $currentTarget[$c] = 0;
        
            for ($m=0; $m <sizeof($repsTable['repInfo']); $m++) { 

                $payTvForecast[$c] += $repsTable['repValues'][$m][$c]['payTvForecast'];
                $digitalForecast[$c] += $repsTable['repValues'][$m][$c]['digitalForecast'];
                $forecast[$c] += $repsTable['repValues'][$m][$c]['forecast'];
                $bookings[$c] += $repsTable['repValues'][$m][$c]['bookings'];
                $pending[$c] = $forecast[$c] - $bookings[$c];

                if($pending[$c] < 0){
                    $pending[$c] = 0;
                }

                $digitalBookings[$c] += $repsTable['repValues'][$m][$c]['digitalBookings'];
                $payTvBookings[$c] += $repsTable['repValues'][$m][$c]['payTvBookings'];
                $previousBookings[$c] += $repsTable['repValues'][$m][$c]['previousBookings'];
                
                if ($manager[0] == 'REGIONAIS') {
                    if($currencyID == 1 ){
                         $pRate = $pr->getPRateByRegionAndYear($con,array($region), array($year)); 

                        $currentTarget[$c] = floatval(($this->getValuesByMonth($con,'137,9',$month,$year,'target',$company[$c],null,null,null))['revenue'])*$pRate;   
                    }else{
                        $pRate = 1;

                        $currentTarget[$c] = floatval(($this->getValuesByMonth($con,'137,9',$month,$year,'target',$company[$c],null,null,null))['revenue'])*$pRate;      
                    }
                }elseif ($manager[0] == 'VV') {
                    if($currencyID == 1 ){
                         $pRate = $pr->getPRateByRegionAndYear($con,array($region), array($year)); 

                        $currentTarget[$c] = floatval(($this->getValuesByMonth($con,'137',$month,$year,'target',$company[$c],null,null,null))['revenue'])*$pRate;   
                    }else{
                        $pRate = 1;

                        $currentTarget[$c] = floatval(($this->getValuesByMonth($con,'137',$month,$year,'target',$company[$c],null,null,null))['revenue'])*$pRate;      
                    }
                }elseif($manager[0] == 'RA'){
                    if($currencyID == 1 ){
                         $pRate = $pr->getPRateByRegionAndYear($con,array($region), array($year)); 

                        $currentTarget[$c] = floatval(($this->getValuesByMonth($con,'9',$month,$year,'target',$company[$c],null,null,null))['revenue'])*$pRate;   
                    }else{
                        $pRate = 1;

                        $currentTarget[$c] = floatval(($this->getValuesByMonth($con,'9',$month,$year,'target',$company[$c],null,null,null))['revenue'])*$pRate;      
                    }
                }else{
                    $currentTarget[$c]  += $repsTable['repValues'][$m][$c]['currentTarget'];
                }                

                

                $pivot[$c] = array('payTvForecast' => ($payTvForecast[$c]), 'digitalForecast' => ($digitalForecast[$c]), 'payTvForecastC' => $payTvForecast[$c], 'digitalForecastC' => $digitalForecast[$c],'forecast' => ($forecast[$c]), 'bookings' => ($bookings[$c]), 'pending' => ($pending[$c]), 'digitalBookings' => $digitalBookings[$c], 'payTvBookings' => $payTvBookings[$c], 'previousBookings' => $previousBookings[$c],'currentTarget' => $currentTarget[$c]);
            }
            
        }  

        $totalTarget =  $currentTarget[0] + $currentTarget[1] + $currentTarget[2];

        for ($m=0; $m <sizeof($repsTable['repInfo']); $m++) { 
            $totalPayTvForecast += $repsTable['total'][$m]['payTvForecast'];
            $totalDigitalForecast += $repsTable['total'][$m]['digitalForecast'];
            $totalForecast += $repsTable['total'][$m]['forecast'];
            $totalBookings += $repsTable['total'][$m]['bookings'];
            $totalPending = $totalForecast - $totalBookings;
            if ($totalPending < 0) {
                $totalPending = 0;
            }

            $totalPayTvBookings += $repsTable['total'][$m]['payTvBookings'];
            $totalDigitalBookings += $repsTable['total'][$m]['digitalBookings'];
            $totalPreviousBookings += $repsTable['total'][$m]['previousBookings'];
           
        }

        $totalPivot = array('payTvForecast' => ($totalPayTvForecast), 'digitalForecast' => ($totalDigitalForecast),'forecast' => ($totalForecast), 'bookings' => ($totalBookings),'pending' => ($totalPending),'payTvBookings' => $totalPayTvBookings, 'digitalBookings' => $totalDigitalBookings, 'previousBookings' => $totalPreviousBookings, 'currentTarget' => $totalTarget);

        //var_dump($totalPivot);
        
        $table = array('managerValues' => $pivot, 'total' => $totalPivot);

        return $table;
    }

    public function repTable(Object $con,Array $manager, $month, Int $year, Int $pYear){
        $sR = new salesRep();
        $pr = new pRate();

        if ($manager[0] == 'VV') {
            $managerId[0]['id'] = '5';
            $managerId[0]['name'] = 'Victor Vasconcelos';
        }elseif ($manager[0] == 'REGIONAIS') {
             $managerId[0]['id'] = '5';
            $managerId[0]['name'] = 'Regionais';
        }else{
            $managerId = $sR->getGroupIdByName($con,$manager);    
        }
        $currencyID = 1;
        $region = 1;
        

        $company = array('1','2','3');
        $companyView = array('wm','dc','spt');
        $rep = $this->getRepByGroup($con,$managerId,$year); 
        for ($r=0; $r <sizeof($rep); $r++) { 
            $clients[$r] = $this->getClientByRep($con, $rep[$r]['id'], 1, $year, $pYear,$month);
            $clientsNew[$r] = $this->getClientByRepNew($con, $rep[$r]['id'], 1, $year, $pYear,$month);
            for ($c=0; $c <sizeof($company) ; $c++) { 
                $payTvForecast[$r][$c] = 0;
                $digitalForecast[$r][$c] = 0;

                $payTvForecastNew[$r][$c] = 0;
                $digitalForecastNew[$r][$c] = 0;
                //
                //var_dump($clients);
                if ($clients[$r] == 'THERE IS NO INFORMATION TO THIS REP') {
                    $payTvForecastNew[$r][$c] = 0;
                    $digitalForecastNew[$r][$c] = 0;
                    $payTvBookings[$r][$c] = 0;
                    $digitalBookings[$r][$c] = 0;
                    $previousBookings[$r][$c] = 0;
                }else{
                    for ($a=0; $a <sizeof($clients[$r]); $a++) { 
                        $probability[$r][$a] = $this->getProbability($con,$clients[$r][$a]['clientID'], $clients[$r][$a]['agencyID'], $rep[$r]['id'],$month)[0]['probability'];
                        
                        $tempPayTv[$r][$a][$c] = $this->getValueByClient($con,$rep[$r]['id'],$month,$year,'forecast',$company[$c], 'pay tv', $clients[$r][$a]['agencyID'],$clients[$r][$a]['clientID'])['revenue']*($probability[$r][$a]/100);

                        $payTvForecast[$r][$c] += $tempPayTv[$r][$a][$c];

                        $tempDigital[$r][$a][$c] = $this->getValueByClient($con,$rep[$r]['id'],$month,$year,'forecast',$company[$c], 'digital', $clients[$r][$a]['agencyID'],$clients[$r][$a]['clientID'])['revenue']*($probability[$r][$a]/100);

                        $digitalForecast[$r][$c] += $tempDigital[$r][$a][$c];

                         $payTvBookings[$r][$c] = ($this->getValuesByMonth($con,$rep[$r]['id'],$month,$year,'bookings',$company[$c],'pay tv',null,null))['revenue'];
                
                        $digitalBookings[$r][$c] = ($this->getValuesByMonth($con,$rep[$r]['id'],$month,$year,'bookings',$company[$c],'digital',null,null))['revenue'];
                        $previousBookings[$r][$c] = ($this->getValuesByMonth($con,$rep[$r]['id'],$month,$pYear,'bookings',$company[$c],null))['revenue'];
                    }
                }

                if ($clientsNew[$r] != false) {                
                    for ($n=0; $n <sizeof($clientsNew[$r]); $n++) { 
                        $probabilityNew[$r][$n] = $this->getProbabilityNewClient($con,$clientsNew[$r][$n]['clientID'], $clientsNew[$r][$n]['agencyID'], $rep[$r]['id'],$month)[0]['probability'];

                        $tempPayTvForecastNew[$r][$n][$c] = ($this->getValuesByMonth($con,$rep[$r]['id'],$month,$year,'forecastNew',$companyView[$c],'pay tv',$clientsNew[$r][$n]['clientID'], $clientsNew[$r][$n]['agencyID']))['revenue']*($probabilityNew[$r][$n]/100);

                        $payTvForecastNew[$r][$c] +=  $tempPayTvForecastNew[$r][$n][$c];

                        $tempDigitalForecastNew[$r][$n][$c] = ($this->getValuesByMonth($con,$rep[$r]['id'],$month,$year,'forecastNew',$companyView[$c],'digital',$clientsNew[$r][$n]['clientID'], $clientsNew[$r][$n]['agencyID']))['revenue']*($probabilityNew[$r][$n]/100);

                        $digitalForecastNew[$r][$c] += $tempDigitalForecastNew[$r][$n][$c];

                    }
                }
                if($currencyID == 1 ){
                     $pRate = $pr->getPRateByRegionAndYear($con,array($region), array($year)); 

                    $currentTarget[$r][$c] = floatval(($this->getValuesByMonth($con,$rep[$r]['id'],$month,$year,'target',$company[$c],null))['revenue'])*$pRate;   
                }else{
                    $pRate = 1;

                    $currentTarget[$r][$c] = floatval(($this->getValuesByMonth($con,$rep[$r]['id'],$month,$year,'target',$company[$c],null))['revenue'])*$pRate;      
                }
               // $payTvForecast[$r][$c] = ($this->getValuesByMonth($con,$rep[$r]['id'],$month,$year,'forecast',$company[$c],'pay tv'))['revenue'];
                //$payTvForecastNew[$r][$c] = ($this->getValuesByMonth($con,$rep[$r]['id'],$month,$year,'forecastNew',$companyView[$c],'pay tv',null,null))['revenue'];
                $payTvForecast[$r][$c] = ($payTvForecast[$r][$c] + $payTvForecastNew[$r][$c]);
                
                //$digitalForecast[$r][$c] = ($this->getValuesByMonth($con,$rep[$r]['id'],$month,$year,'forecast',$company[$c],'digital'))['revenue'];
                //$digitalForecastNew[$r][$c] = ($this->getValuesByMonth($con,$rep[$r]['id'],$month,$year,'forecastNew',$companyView[$c],'digital',null,null))['revenue'];
                $digitalForecast[$r][$c] = ($digitalForecast[$r][$c] + $digitalForecastNew[$r][$c]);

                $forecast[$r][$c] = $payTvForecast[$r][$c] + $digitalForecast[$r][$c];
               

                $bookings[$r][$c] = ($payTvBookings[$r][$c] + $digitalBookings[$r][$c]);

                
                $pending[$r][$c] = $forecast[$r][$c] - $bookings[$r][$c];
                if ($pending[$r][$c] < 0) {
                    $pending[$r][$c] = 0;
                }                

                $pivot[$r][$c] = array('payTvForecast' => ($payTvForecast[$r][$c]), 'digitalForecast' => ($digitalForecast[$r][$c]), 'payTvForecastC' => $payTvForecast[$r][$c], 'digitalForecastC' => $digitalForecast[$r][$c],'forecast' => ($forecast[$r][$c]), 'bookings' => ($bookings[$r][$c]), 'pending' => ($pending[$r][$c]), 'digitalBookings' => $digitalBookings[$r][$c], 'payTvBookings' => $payTvBookings[$r][$c], 'previousBookings' => $previousBookings[$r][$c], 'currentTarget' => $currentTarget[$r][$c]);
                
            }

            $totalPayTvForecast[$r] = $payTvForecast[$r][0] + $payTvForecast[$r][1] + $payTvForecast[$r][2]; 

            $totalDigitalForecast[$r] = $digitalForecast[$r][0] + $digitalForecast[$r][1] + $digitalForecast[$r][2]; 

            //$totalPayTvForecast[$r] = ($this->getValuesByMonth($con,$rep[$r]['id'],$month,$year,'forecast','1,2,3','pay tv'))['revenue'];

            //$totalDigitalForecast[$r] = ($this->getValuesByMonth($con,$rep[$r]['id'],$month,$year,'forecast','1,2,3','digital'))['revenue'];

            $totalForecast[$r] = $totalPayTvForecast[$r] + $totalDigitalForecast[$r];

            $totalPayTvBookings[$r] = ($this->getValuesByMonth($con,$rep[$r]['id'],$month,$year,'bookings','1,2,3','pay tv',null,null))['revenue'];
            
            $totalDigitalBookings[$r] = ($this->getValuesByMonth($con,$rep[$r]['id'],$month,$year,'bookings','1,2,3','digital',null,null))['revenue'];

            $totalBookings[$r] = $totalPayTvBookings[$r] + $totalDigitalBookings[$r];

            
            $totalPending[$r] = $totalForecast[$r] - $totalBookings[$r];
            if ($totalPending[$r] < 0) {
                $totalPending[$r] = 0;
            }
            

            $totalPreviousBookings[$r] = ($this->getValuesByMonth($con,$rep[$r]['id'],$month,$pYear,'bookings','1,2,3',null,null,null))['revenue'];
            if($currencyID == 1 ){
                 $pRate = $pr->getPRateByRegionAndYear($con,array($region), array($year)); 

                $totalTarget[$r] = floatval(($this->getValuesByMonth($con,$rep[$r]['id'],$month,$year,'target','1,2,3',null,null,null))['revenue'])*$pRate;   
            }else{
                $pRate = 1;

                $totalTarget[$r] = floatval(($this->getValuesByMonth($con,$rep[$r]['id'],$month,$year,'target','1,2,3',null,null,null))['revenue'])*$pRate;      
            }

            
            $totalPivot[$r] = array('payTvForecast' => ($totalPayTvForecast[$r]), 'digitalForecast' => ($totalDigitalForecast[$r]),'forecast' => ($totalForecast[$r]), 'bookings' => ($totalBookings[$r]),'pending' => ($totalPending[$r]),'payTvBookings' => $totalPayTvBookings[$r], 'digitalBookings' => $totalDigitalBookings[$r], 'previousBookings' => $totalPreviousBookings[$r], 'currentTarget' => $totalTarget[$r]);
           
            $repInfo[$r] = array('salesRep' => $rep[$r]['name'], 'repID' => $rep[$r]['id']);
        }
        
        $table = array('repInfo' => $repInfo,'repValues' => $pivot,'total' => $totalPivot);
        //var_dump($table['repValues']);
        return $table;
    }

    public function getValueByClient(Object $con,String $user,String $month,Int $year,String $table,String $company, $platform=null, $agency,$client){
        $sql = new sql();

        $selectAE = "SELECT SUM(f.revenue) as revenue
                    FROM monthly_forecast f
                    LEFT JOIN client c ON c.ID = f.client_id
                    LEFT JOIN agency a ON a.ID = f.agency_id
                    WHERE f.sales_rep_id IN ($user)
                    AND f.year = $year
                    AND f.month = $month
                    AND f.value = 'gross'
                    AND c.id = $client
                    AND a.id = $agency
                    AND (f.company_id IN ($company)) 
                    AND (f.platform = '$platform')
                    ";
            //var_dump($select);
        $query = $con->query($selectAE);
        $from = 'revenue';
        $resultAE = $sql->fetchSUM($query,$from);
        //echo "<pre>$selectAE</pre>";
        if ($resultAE['revenue'] == null) {
            $resultAE['revenue'] = 0.0;
        }

        return $resultAE;


    }

    public function getValuesByMonth(Object $con,String $user,String $month,Int $year,String $table,String $company, $platform=null,$client=null,$agency=null){
        $sql = new sql();
        $value = 'GROSS';

        switch ($table) {
            case 'forecast':
               $selectAE = "SELECT SUM(f.revenue) as revenue
                            FROM monthly_forecast f                                
                            WHERE f.sales_rep_id = $user
                            AND f.year = $year
                            AND f.month = $month
                            AND f.value = 'gross'
                            AND (f.company_id IN ($company))
                            AND (f.platform = '$platform')
                            ";
                    //var_dump($selectAE);
                $query = $con->query($selectAE);
                $from = 'revenue';
                $resultAE = $sql->fetchSUM($query,$from);

                break;
            case 'bookings':
                if ($platform != null) {
                    if ($platform == 'pay tv') {
                        $selectAE = "SELECT sum(gross_value) as revenue
                            FROM wbd w
                            LEFT JOIN brand b on b.id = w.brand_id
                            WHERE w.current_sales_rep_id = $user
                            AND w.year = $year
                            AND w.month = $month
                            AND (b.brand_group_id IN ($company))
                            AND b.type = 'Linear'                                
                            "; 
                            //var_dump($select);
                    }else{
                        $selectAE = "SELECT sum(gross_value) as revenue
                            FROM wbd w
                            LEFT JOIN brand b on b.id = w.brand_id
                            WHERE w.current_sales_rep_id = $user
                            AND w.year = $year
                            AND w.month = $month
                            AND (b.brand_group_id IN ($company))
                            AND b.type = 'Non-Linear'
                            "; 
                            //var_dump($select);
                    }                   
                }else{
                    $selectAE = "SELECT sum(gross_value) as revenue
                            FROM wbd w
                            LEFT JOIN brand b on b.id = w.brand_id
                            WHERE w.current_sales_rep_id = $user
                            AND w.year = $year
                            AND w.month = $month
                            AND (b.brand_group_id IN ($company))
                            ";
                }

                $query = $con->query($selectAE);
                $from = 'revenue';
                $resultAE = $sql->fetchSUM($query,$from);
                break;
            case 'forecastNew':

                if ($client != null) { 
                    
                    $selectAE = "SELECT SUM(f.$company) as revenue
                    FROM new_clients_fcst f 
                    LEFT JOIN client c ON c.ID = f.client_id
                    LEFT JOIN agency a ON a.ID = f.agency_id
                    WHERE f.sales_rep_id IN ($user)
                    AND f.client_id = '$client'
                    AND f.agency_id = '$agency'
                    AND (f.platform = '$platform')
                    AND f.month = $month
                    AND $company != 0";
                }else{
                    $selectAE = "SELECT SUM(f.$company) as revenue
                                FROM new_clients_fcst f                                
                                WHERE f.sales_rep_id = $user
                                AND (f.platform = '$platform')
                                AND f.month = $month
                                ";
                }
                
                    //var_dump($selectAE);

                    $query = $con->query($selectAE);
                    $from = 'revenue';
                    $resultAE = $sql->fetchSUM($query,$from);
                break;
            case 'target':
                 $select = "SELECT sum(pbs.value) as revenue
                            FROM plan_by_sales pbs
                            LEFT JOIN brand b on b.id = pbs.brand_id
                            WHERE pbs.sales_rep_id IN ($user)
                            AND pbs.year = $year
                            AND pbs.month = $month
                            AND pbs.type_of_revenue = '$value'
                            AND pbs.region_id = 1
                            AND (b.brand_group_id IN ($company))
                            ";
                //var_dump($select);
                $query = $con->query($select);
                $from = 'revenue';
                $resultAE = $sql->fetchSUM($query,$from);
                
                if ($resultAE['revenue'] == null) {
                    $resultAE['revenue'] = 0.0;
                }

            default:
                // code...
                break;
        }
        return $resultAE;
    }

    public function getRepByGroup(Object $con, Array $managerId, Int $year){
        $sql = new sql();
        //var_dump($managerId);

        if ($managerId[0]['name'] == 'Victor Vasconcelos') {
            $temp = $managerId[0]['id'];
            $select = "SELECT s.id as id, s.name as name
                        FROM sales_rep s
                        LEFT JOIN sales_rep_status sr ON s.id = sr.sales_rep_id
                        WHERE s.sales_group_id IN ('$temp')
                        AND sr.status = 1
                        AND sr.year = $year
                        AND s.id IN (268,271,273,262,272,275,274,267)
            ";
        }elseif ($managerId[0]['name'] == 'Ricardo Alves') {
            $temp = $managerId[0]['id'];
            $select = "SELECT s.id as id, s.name as name
                        FROM sales_rep s
                        LEFT JOIN sales_rep_status sr ON s.id = sr.sales_rep_id
                        WHERE s.sales_group_id IN ('$temp')
                        AND sr.status = 1
                        AND sr.year = $year
                        AND s.id IN (270,264,265,266,269)
            ";
        }else{
            $temp = $managerId[0]['id'];
            $select = "SELECT s.id as id, s.name as name
                        FROM sales_rep s
                        LEFT JOIN sales_rep_status sr ON s.id = sr.sales_rep_id
                        WHERE s.sales_group_id IN ('$temp')
                        AND sr.status = 1
                        AND sr.year = $year
            ";
        }

        $from = array('id','name');
        $result = $con->query($select);
        $salesRep = $sql->fetch($result,$from,$from);

        //var_dump($salesRep);
        return $salesRep;
    }

    public function getClientByRep(Object $con,int $salesRep, int $region, int $year, int $pYear, $month){
        $sql = new sql();

        $selectWBD = "SELECT DISTINCT c.name as clientName, c.ID as clientID, a.name as agencyName, a.ID as agencyID
                    FROM wbd w
                    LEFT JOIN client c ON c.ID = w.client_id
                    LEFT JOIN agency a ON a.ID = w.agency_id
                    WHERE (w.current_sales_rep_id IN (\"$salesRep\" ))
                    AND w.year IN (\"$year\",\"$pYear\")
                    AND w.month = $month
                    AND gross_value > 0.0                
                    ORDER BY c.name ASC
                    ";
        $queryWBD = $con->query($selectWBD);
        $fromWBD = array('clientName', 'clientID','agencyName','agencyID');
        $resultWBD = $sql->fetch($queryWBD,$fromWBD,$fromWBD);
        
       
        $selectForecast = "SELECT DISTINCT c.name as clientName, c.ID as clientID, a.name as agencyName, a.ID as agencyID
                    FROM forecast w
                    LEFT JOIN client c ON c.ID = w.client_id
                    LEFT JOIN agency a ON a.ID = w.agency_id
                    WHERE (w.sales_rep_id IN (\"$salesRep\" ))
                    AND $month > 0.0
                    ORDER BY c.name ASC
                    ";
        $queryForecast = $con->query($selectForecast);
        $fromForecast = array('clientName', 'clientID','agencyName','agencyID');
        $resultForecast = $sql->fetch($queryForecast,$fromForecast,$fromForecast);
        $resultForecast = false;
        //var_dump($resultForecast);
        if ($resultForecast != false) {
            $result = array_merge($resultWBD,$resultForecast);
            $result = array_unique($result, SORT_REGULAR);
            $result = array_values($result);
        }else{
            if ($resultWBD == false || $resultWBD == NULL) {
                $result = 'THERE IS NO INFORMATION TO THIS REP';
            }else{
                 $result = array_values($resultWBD);     
            }           
            
        }
               // var_dump($result);
        return $result;
    }

    public function getClientByRepNew(Object $con,int $salesRep, int $region, int $year, int $pYear, $month){
        $sql = new sql();

        $selectWBD = "SELECT DISTINCT c.name as clientName, c.ID as clientID, a.name as agencyName, a.ID as agencyID
                    FROM new_clients_fcst w
                    LEFT JOIN client c ON c.ID = w.client_id
                    LEFT JOIN agency a ON a.ID = w.agency_id
                    WHERE (w.sales_rep_id IN (\"$salesRep\" ))
                    AND w.month = $month            
                    ORDER BY c.name ASC
                    ";
        $queryWBD = $con->query($selectWBD);
        $fromWBD = array('clientName', 'clientID','agencyName','agencyID');
        $resultWBD = $sql->fetch($queryWBD,$fromWBD,$fromWBD);
       // var_dump($resultWBD);
              
        return $resultWBD;
    }

     public function getProbability(Object $con, int $client, int $agency, int $salesRep,$month){
        $sql = new sql();

        $selectQuery = "SELECT DISTINCT success_probability AS probability
                        FROM monthly_forecast
                        WHERE sales_rep_id IN ($salesRep)
                        AND client_id = $client
                        AND agency_id = $agency
                        AND month = $month";
       //var_dump($selectQuery);
        $from = array('probability');
        $selectResultQuery = $con->query($selectQuery);
        $resultSelect = $sql->fetch($selectResultQuery, $from, $from);

        if ($resultSelect == false) {
            $resultSelect[0]['probability'] = '100';
        }

        return $resultSelect;
    }


    public function getProbabilityNewClient(Object $con, String $client, String $agency, int $salesRep,$month){
        $sql = new sql();

        $selectQuery = "SELECT DISTINCT probability AS probability
                        FROM new_clients_fcst
                        WHERE sales_rep_id IN ($salesRep)
                        AND client_id = $client
                        AND agency_id = $agency
                        AND month = $month";
       //var_dump($selectQuery);
        $from = array('probability');
        $selectResultQuery = $con->query($selectQuery);
        $resultSelect = $sql->fetch($selectResultQuery, $from, $from);
        //var_dump($resultSelect);
        if ($resultSelect == false) {
            $resultSelect = 0;
        }

        return $resultSelect;
    }

}
