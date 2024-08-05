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
use App\CheckElements;

class forecast extends pAndR{
    
    //THIS FUNCTION MAKE THE TOTAL FOR THE SALES REP MERGING THE COMPANIES AND CLIENTS
    public function makeRepTable(Object $con, $salesRep, Object $pr, int $year, int $pYear, int $region, int $currencyID, string $value, String $salesRepName,int $month,$newClientsTable,$clientsTable){
        $company = array('1','2','3');

        $companyName = array('dc','spt','wm');
        //$month = date('m');
       // var_dump($month);
        if($currencyID == 1 ){
            $pRate = 1;
        }else{
            $pRate = $pr->getPRateByRegionAndYear($con,array($region), array($year));    
        }

        for ($c=0; $c <sizeof($company); $c++) { //this for is to make the interactons for the 3 companie
            
            $currentBookings[$c] = ($this->getValueByMonth($con,$salesRep,$year,$value,$month,'bookings',null,null, $region,null,$company[$c])['revenue']);                
            $previousBookings[$c] = ($this->getValueByMonth($con,$salesRep,$pYear,$value,$month,'bookings',null, null, $region,null,$company[$c])['revenue']);
            
            if($currencyID == 1 ){
                 $pRate = $pr->getPRateByRegionAndYear($con,array($region), array($year)); 

                $currentTarget[$c] = floatval($this->getValueByMonth($con,$salesRep,$year,$value,$month,'target',null, null, $region,null,$company[$c])['revenue'])*$pRate;   
            }else{
                $pRate = 1;

                $currentTarget[$c] = floatval($this->getValueByMonth($con,$salesRep,$year,$value,$month,'target',null, null, $region,null,$company[$c])['revenue'])*$pRate;   
            }    

            //var_dump($newClientsTable['companyValues']);
            $tempPayTvForecast[$c] = 0;
            $tempDigitalForecast[$c] = 0;
            $payTvForecastNew[$c] = 0;
            $digitalForecastNew[$c] = 0;
            $digitalForecast[$c] = 0;
            $payTvForecast[$c] = 0;

            if ($clientsTable != 'THERE IS NO INFORMATION TO THIS REP') {
                for ($p=0; $p <sizeof($clientsTable['clientInfo']); $p++) { 
                   // var_dump(($clientsTable['clientInfo'][$p]['probability'][0]['probability']/100));
                    $tempPayTvForecast[$c] += (($clientsTable['companyValues'][$p][$c]['payTvForecast'])*($clientsTable['clientInfo'][$p]['probability'][0]['probability']/100));
                    //var_dump($tempPayTvForecast);
                    $tempDigitalForecast[$c] += ($clientsTable['companyValues'][$p][$c]['digitalForecast']*($clientsTable['clientInfo'][$p]['probability'][0]['probability']/100));
                   
                }
            }
            //var_dump($tempPayTvForecast);
            if ($newClientsTable != null) {
                for ($pp=0; $pp <sizeof($newClientsTable['clientInfo']) ; $pp++) { 
                    $payTvForecastNew[$c] += ($newClientsTable['companyValues'][$pp][$c]['payTvForecast']*($newClientsTable['clientInfo'][$pp]['probability'][0]['probability']/100));
                        //var_dump($payTvForecastNew);
                    
                    $digitalForecastNew[$c] += ($newClientsTable['companyValues'][$pp][$c]['digitalForecast']*($newClientsTable['clientInfo'][$pp]['probability'][0]['probability']/100));       
                }
            }
            

            $payTvForecast[$c] = ($tempPayTvForecast[$c] + $payTvForecastNew[$c]);
            //var_dump($payTvForecast);
            $digitalForecast[$c] = $digitalForecastNew[$c] + $tempDigitalForecast[$c];
                
            $currentPayTvBookings[$c] = ($this->getValueByMonth($con,$salesRep,$year,$value,$month,'bookings',null,null, $region,'pay tv',$company[$c])['revenue']);
            $currentDigitalBookings[$c] = ($this->getValueByMonth($con,$salesRep,$year,$value,$month,'bookings',null,null, $region,'digital',$company[$c])['revenue']);
            $forecastBookings[$c] = $payTvForecast[$c] + $digitalForecast[$c] + $currentDigitalBookings[$c] + $currentPayTvBookings[$c];

            $forecast[$c] = ($payTvForecast[$c] + $digitalForecast[$c]);
                
            $bookings[$c] = ($currentPayTvBookings[$c] + $currentDigitalBookings[$c]);

            $pending[$c] = ($forecast[$c]) - ($bookings[$c]);
            if ($pending[$c] < 0) {
                $pending[$c] = 0;
            }

            $totalCurrentBookings = ($this->getValueByMonth($con,$salesRep,$year,$value,$month,'bookings',null,null, $region,null,'1,2,3')['revenue']);           
            $totalPreviousBookings = ($this->getValueByMonth($con,$salesRep,$pYear,$value,$month,'bookings',null, null, $region,null,'1,2,3')['revenue']);

            if($currencyID == 1 ){
                 $pRate = $pr->getPRateByRegionAndYear($con,array($region), array($year)); 

                 $totalCurrentTarget = floatval($this->getValueByMonth($con,$salesRep,$year,$value,$month,'target',null, null, $region,null,'1,2,3')['revenue'])*$pRate;
            }else{
                $pRate = 1;

                 $totalCurrentTarget = floatval($this->getValueByMonth($con,$salesRep,$year,$value,$month,'target',null, null, $region,null,'1,2,3')['revenue'])*$pRate;
            }   
      
           /* $payTvForecast[$c] = ($payTvForecast[$c] - $currentPayTvBookings[$c]);
            if ($payTvForecast[$c] < 0) {
                $payTvForecast[$c] = 0;
            }

            $digitalForecast[$c] = ($digitalForecast[$c] - $currentDigitalBookings[$c]);
            if ($digitalForecast[$c] < 0) {
                $digitalForecast = 0;
            }*/

           $pivot[$c] = array('currentBookings' => ($currentBookings[$c]),'previousBookings' => ($previousBookings[$c]), 'currentTarget' => ($currentTarget[$c]), 'payTvForecast' => ($payTvForecast[$c]), 'digitalForecast' => ($digitalForecast[$c]), 'currentDigitalBookings' => ($currentDigitalBookings[$c]), 'currentPayTvBookings' => ($currentPayTvBookings[$c]), 'forecast'=> ($forecast[$c]),'bookings' => ($bookings[$c]), 'pending' => ($pending[$c]));
        
            
        }
        //var_dump($payTvForecast);
        $totalPayTvForecast = $payTvForecast[0] + $payTvForecast[1] + $payTvForecast[2];
        //var_dump($totalPayTvForecast);

        $totalDigitalForecast = $digitalForecast[0] + $digitalForecast[1] + $digitalForecast[2];     
       // var_dump($totalPayTvForecast);
        $totalCurrentPayTvBookings = ($this->getValueByMonth($con,$salesRep,$year,$value,$month,'bookings',null,null, $region,'pay tv','1,2,3')['revenue']);           
        
        $totalCurrentDigitalBookings = ($this->getValueByMonth($con,$salesRep,$year,$value,$month,'bookings',null,null, $region,'digital','1,2,3')['revenue']);  

        $totalForecast = ($totalPayTvForecast + $totalDigitalForecast);
                
        $totalBookings = ($totalCurrentPayTvBookings + $totalCurrentDigitalBookings);

        $totalPending = ($totalForecast) - ($totalBookings);
        if ($totalPending < 0) {
            $totalPending = 0;
        }

        $totalForecastBookings = $totalDigitalForecast + $totalPayTvForecast + $totalCurrentPayTvBookings + $totalCurrentDigitalBookings;

        $pivotTotal = array('currentBookings' => ($totalCurrentBookings),'previousBookings' => ($totalPreviousBookings), 'currentTarget' => ($totalCurrentTarget), 'payTvForecast' => ($totalPayTvForecast), 'digitalForecast' => ($totalDigitalForecast), 'currentDigitalBookings' => ($totalCurrentDigitalBookings), 'currentPayTvBookings' => ($totalCurrentPayTvBookings), 'forecast'=> ($totalForecast),'bookings' => ($totalBookings), 'pending' => ($totalPending));  
        
       
        //var_dump($payTvForecast);
        $table = array('companyValues' => $pivot, 'total' => $pivotTotal);

       // var_dump($table['total']);
       return $table;
   
    }


    //THIS FUNCTION MAKE ALL THE CLIENTS TABLE TO PASS TO FRONT
    public function makeClientsTable(Object $con, $salesRep, Object $pr, int $year, int $pYear, int $region, int $currencyID, string $value, String $salesRepName,int $month){
        $sql = new sql();
        //$month = 0;
        $company = array('1','2','3');
        //$month = date('m');
        
        if($currencyID == 1){
            $pRate = 1;
        }else{
            $pRate = $pr->getPRateByRegionAndYear($con,array($region), array($year));    
        }

        $clients = $this->getClientByRep($con, $salesRep, $region, $year, $pYear,$month);

        $newClients = $this->getSalesRepByClient($salesRep,$con, $sql,$salesRepName,$month);
        
        //var_dump($clients);
        /*if ($clients != 'THERE IS NO INFORMATION TO THIS REP') {
            for ($a=0; $a <sizeof($clients) ; $a++) { 
                for ($aa=0; $aa <sizeof($newClients) ; $aa++) { 
                    if ($clients[$a]['clientID'] == $newClients[$aa]['clientID'] && $clients[$a]['agencyID'] == $newClients[$aa]['agencyID']) {
                        unset($clients[$a]);
                        //var_dump($clients[$a]);
                       // var_dump($newClients[$aa]);
                        $clients = array_values($clients);
                      var_dump($clients);      
                    }
                    
                           
                }
            }  
        }*/

        if ($clients != 'THERE IS NO INFORMATION TO THIS REP') {
            for ($a=0; $a <sizeof($clients) ; $a++) { //this for is to make the interactons for all clients of this rep 
                 $check = $this->checkForecast($con, $salesRep,$month,$clients[$a]['clientID'],$clients[$a]['agencyID']);//check if exists forecast for this rep in database
                for ($c=0; $c <sizeof($company); $c++) { //this for is to make the interactons for the 3 companies
                    //var_dump($month);
                    $currentBookings[$a][$c] = ($this->getValueByMonth($con,$salesRep,$year,$value,$month,'bookings',$clients[$a]['clientID'],$clients[$a]['agencyID'], $region,null,$company[$c])['revenue']);  
                    $previousBookings[$a][$c] = ($this->getValueByMonth($con,$salesRep,$pYear,$value,$month,'bookings',$clients[$a]['clientID'],$clients[$a]['agencyID'], $region,null,$company[$c])['revenue']);
                   // if($check != false){
                        $payTvForecast[$a][$c] = ($this->getValueByMonth($con,$salesRep,$year,$value,$month,'forecast',$clients[$a]['clientID'],$clients[$a]['agencyID'], $region,'pay tv', $company[$c])['revenue']);
                        $digitalForecast[$a][$c] = ($this->getValueByMonth($con,$salesRep,$year,$value,$month,'forecast',$clients[$a]['clientID'],$clients[$a]['agencyID'], $region,'digital',$company[$c])['revenue']);
                    /*}else{
                        $payTvForecast[$a][$c] = ($this->getValueByMonth($con,$salesRep,$year,$value,$month,'tRex',$clients[$a]['clientID'],$clients[$a]['agencyID'], $region,'pay tv', $company[$c])['revenue']);    
                        $digitalForecast[$a][$c] = ($this->getValueByMonth($con,$salesRep,$year,$value,$month,'tRex',$clients[$a]['clientID'],$clients[$a]['agencyID'], $region,'digital',$company[$c])['revenue']);
                    }*/                  
                    $currentPayTvBookings[$a][$c] = ($this->getValueByMonth($con,$salesRep,$year,$value,$month,'bookings',$clients[$a]['clientID'],$clients[$a]['agencyID'], $region,'pay tv',$company[$c])['revenue']);
                    $currentDigitalBookings[$a][$c] = ($this->getValueByMonth($con,$salesRep,$year,$value,$month,'bookings',$clients[$a]['clientID'],$clients[$a]['agencyID'], $region,'digital',$company[$c])['revenue']);
                    $currentTarget[$a][$c] = 0;

                    //this variable is the total of forecast by company and client
                    $forecast[$a][$c] = ($payTvForecast[$a][$c] + $digitalForecast[$a][$c]);                    

                    $bookings[$a][$c] = ( $currentPayTvBookings[$a][$c] + $currentDigitalBookings[$a][$c]);
                   
                    $pendingBookings[$a][$c] = ($forecast[$a][$c]) - ($bookings[$a][$c]);
                    if ($pendingBookings[$a][$c] < 0) {
                        $pendingBookings[$a][$c] = 0;
                    }

                    $pivot[$a][$c] = array('currentBookings' => ($currentBookings[$a][$c]),'previousBookings' => ($previousBookings[$a][$c]), 'payTvForecast' => ($payTvForecast[$a][$c]), 'digitalForecast' => ($digitalForecast[$a][$c]), 'currentTarget' => ($currentTarget[$a][$c]), 'currentDigitalBookings' => $currentDigitalBookings[$a][$c], 'currentPayTvBookings' => $currentPayTvBookings[$a][$c], 'payTvForecastC' => $payTvForecast[$a][$c], 'digitalForecastC' => $digitalForecast[$a][$c], 'currentDigitalBookings' => ($currentDigitalBookings[$a][$c]), 'currentPayTvBookings' => ($currentPayTvBookings[$a][$c]),'forecast'=> ($forecast[$a][$c]),'bookings' => ($bookings[$a][$c]), 'pending' => ($pendingBookings[$a][$c]));
                    
                } 

                
                $totalCurrentBookings[$a] = ($this->getValueByMonth($con,$salesRep,$year,$value,$month,'bookings',$clients[$a]['clientID'],$clients[$a]['agencyID'], $region,null,'1,2,3')['revenue']);           
                $totalPreviousBookings[$a] = ($this->getValueByMonth($con,$salesRep,$pYear,$value,$month,'bookings',$clients[$a]['clientID'],$clients[$a]['agencyID'], $region,null,'1,2,3')['revenue']);
                $totalCurrentTarget[$a] = 0;
                //if($check != false){ 
                    $totalPayTvForecast[$a] = ($this->getValueByMonth($con,$salesRep,$year,$value,$month,'forecast',$clients[$a]['clientID'],$clients[$a]['agencyID'], $region,'pay tv', '1,2,3')['revenue']);
                    $totalDigitalForecast[$a] = ($this->getValueByMonth($con,$salesRep,$year,$value,$month,'forecast',$clients[$a]['clientID'],$clients[$a]['agencyID'], $region,'digital','1,2,3')['revenue']);

                   
                /*}else{
                    $totalPayTvForecast[$a] = ($this->getValueByMonth($con,$salesRep,$year,$value,$month,'tRex',$clients[$a]['clientID'],$clients[$a]['agencyID'], $region,'pay tv', '1,2,3')['revenue']);
                    $totalDigitalForecast[$a] = ($this->getValueByMonth($con,$salesRep,$year,$value,$month,'tRex',$clients[$a]['clientID'],$clients[$a]['agencyID'], $region,'digital','1,2,3')['revenue']);
                } */  
                
                $totalCurrentPayTvBookings[$a] = ($this->getValueByMonth($con,$salesRep,$year,$value,$month,'bookings',$clients[$a]['clientID'],$clients[$a]['agencyID'], $region,'pay tv','1,2,3')['revenue']);           
                $totalCurrentDigitalBookings[$a] = ($this->getValueByMonth($con,$salesRep,$year,$value,$month,'bookings',$clients[$a]['clientID'],$clients[$a]['agencyID'], $region,'digital','1,2,3')['revenue']);
                 
                $probability[$a] = $this->getProbability($con,$clients[$a]['clientID'],$clients[$a]['agencyID'],$salesRep,$month);
                //var_dump($probability[$a]);
                $totalForecast[$a] = ($totalPayTvForecast[$a] + $totalDigitalForecast[$a]);
                
                $totalBookings[$a] = ($totalCurrentPayTvBookings[$a] + $totalCurrentDigitalBookings[$a]);

                $totalPending[$a] = ($totalForecast[$a]) - ($totalBookings[$a]);
                if ($totalPending[$a] < 0) {
                    $totalPending[$a] = 0;
                }


               $totalPivot[$a] = array('currentBookings' => ($totalCurrentBookings[$a]),'previousBookings' => ($totalPreviousBookings[$a]), 'currentTarget' => ($totalCurrentTarget[$a]), 'payTvForecast' => ($totalPayTvForecast[$a]), 'digitalForecast' => ($totalDigitalForecast[$a]), 'currentDigitalBookings' => ($totalCurrentDigitalBookings[$a]), 'currentPayTvBookings' => ($totalCurrentPayTvBookings[$a]), 'forecast'=> ($totalForecast[$a]),'bookings' => ($totalBookings[$a]), 'pending' => ($totalPending[$a]));
           
                $clientInfo[$a] = array('clientName' => $clients[$a]['clientName'], 'clientID' => $clients[$a]['clientID'],'agencyName' => $clients[$a]['agencyName'],'agencyID' => $clients[$a]['agencyID'], 'probability' => $probability[$a]);
            }
       
            $table = array('clientInfo' => $clientInfo,'companyValues' => $pivot,'total' => $totalPivot);
       }else{

            $table = $clients;
       }      
       
       // var_dump($table['clientInfo']);
        return $table;
    }

     //THIS FUNCTION MAKE ALL THE CLIENTS TABLE TO PASS TO FRONT
    public function makeNewClientsTable(Object $con, $salesRep, Object $pr, int $year, int $pYear, int $region, int $currencyID, string $value, String $salesRepName,int $month){
        $sql = new sql();
       // $month = 0;
        $company = array('wm','dc','spt');
        $companyNum =  array(1,2,3);
        //$month = date('m');
        $totalPayTvForecast = 0;
        $totalDigitalForecast = 0;

        if($currencyID == 1){
            $pRate = 1;
        }else{
            $pRate = $pr->getPRateByRegionAndYear($con,array($region), array($year));    
        }
        
        
        $clients = $this->getSalesRepByClient($salesRep,$con, $sql,$salesRepName,$month);
       // var_dump($clients);
        if ($clients == null) {
            $table = 0;
        }else{
           //check if exists forecast for this rep in database
           
           // var_dump($clients);
            for ($a=0; $a <sizeof($clients) ; $a++) { //this for is to make the interactons for all clients of this rep 
                 $this->checkDuplicates($con, $salesRep,$month,$clients[$a]['clientID'],$clients[$a]['agencyID'], 'pay tv');
                 $this->checkDuplicates($con, $salesRep,$month,$clients[$a]['clientID'],$clients[$a]['agencyID'], 'digital');

                for ($c=0; $c <sizeof($company); $c++) { //this for is to make the interactons for the 3 companies
                    //var_dump($salesRep,$year,$value,$month,'forecast',$clients[$a]['clientName'],$clients[$a]['agencyName'], $region,'pay tv', $company[$c]);
                   // if($check != false){
                        $payTvForecast[$a][$c] = ($this->getValueByClient($con,$salesRep,$year,$value,$month,'forecast',$clients[$a]['clientID'],$clients[$a]['agencyID'], $region,'pay tv', $company[$c])['revenue']);

                        $digitalForecast[$a][$c] = ($this->getValueByClient($con,$salesRep,$year,$value,$month,'forecast',$clients[$a]['clientID'],$clients[$a]['agencyID'], $region,'digital',$company[$c])['revenue']);
                    /*}else{
                        $payTvForecast[$a][$c] = ($this->getValueByClient($con,$salesRep,$year,$value,$month,'tRex',$clients[$a]['clientID'],$clients[$a]['agencyID'], $region,'pay tv', $company[$c])['revenue']);    
                        $digitalForecast[$a][$c] = ($this->getValueByClient($con,$salesRep,$year,$value,$month,'tRex',$clients[$a]['clientID'],$clients[$a]['agencyID'], $region,'digital',$company[$c])['revenue']);
                    }*/


                    $currentPayTvBookings[$a][$c] = ($this->getValueByMonth($con,$salesRep,$year,$value,$month,'bookings',$clients[$a]['clientID'],$clients[$a]['agencyID'], $region,'pay tv',$companyNum[$c])['revenue']);
                    //var_dump($currentPayTvBookings);
                    $currentDigitalBookings[$a][$c] = ($this->getValueByMonth($con,$salesRep,$year,$value,$month,'bookings',$clients[$a]['clientID'],$clients[$a]['agencyID'], $region,'digital',$companyNum[$c])['revenue']);
                    $currentTarget[$a][$c] = 0;

                    //this variable is the total of forecast by company and client
                    $forecast[$a][$c] = ($payTvForecast[$a][$c] + $digitalForecast[$a][$c]);                    

                    $bookings[$a][$c] = ( $currentPayTvBookings[$a][$c] + $currentDigitalBookings[$a][$c]);
                   
                    $pendingBookings[$a][$c] = ($forecast[$a][$c]) - ($bookings[$a][$c]);
                    if ($pendingBookings[$a][$c] < 0) {
                        $pendingBookings[$a][$c] = 0;
                    }

                    $pivot[$a][$c] = array('payTvForecast' => ($payTvForecast[$a][$c]), 'digitalForecast' => ($digitalForecast[$a][$c]), 'payTvForecastC' => $payTvForecast[$a][$c], 'digitalForecastC' => $digitalForecast[$a][$c],'forecast' => ($forecast[$a][$c]), 'bookings' => ($bookings[$a][$c]), 'pending' => ($pendingBookings[$a][$c]), 'currentDigitalBookings' => $currentDigitalBookings[$a][$c], 'currentPayTvBookings' => $currentPayTvBookings[$a][$c]);
                    
                } 

                //if($check != false){ 
                    $totalPayTvForecast = array();
                    $totalDigitalForecast = array();

                    $totalPayTvForecast[$a] = ($this->getValueByClient($con,$salesRep,$year,$value,$month,'forecast',$clients[$a]['clientID'],$clients[$a]['agencyID'], $region,'pay tv', 'dc')['revenue']) + ($this->getValueByClient($con,$salesRep,$year,$value,$month,'forecast',$clients[$a]['clientID'],$clients[$a]['agencyID'], $region,'pay tv', 'spt')['revenue']) + ($this->getValueByClient($con,$salesRep,$year,$value,$month,'forecast',$clients[$a]['clientID'],$clients[$a]['agencyID'], $region,'pay tv', 'wm')['revenue']);

                    $totalDigitalForecast[$a] = ($this->getValueByClient($con,$salesRep,$year,$value,$month,'forecast',$clients[$a]['clientID'],$clients[$a]['agencyID'], $region,'digital', 'dc')['revenue']) + ($this->getValueByClient($con,$salesRep,$year,$value,$month,'forecast',$clients[$a]['clientID'],$clients[$a]['agencyID'], $region,'digital', 'spt')['revenue']) + ($this->getValueByClient($con,$salesRep,$year,$value,$month,'forecast',$clients[$a]['clientID'],$clients[$a]['agencyID'], $region,'digital', 'wm')['revenue']);
                    //var_dump($totalPayTvForecast);
                /*}else{
                    $totalPayTvForecast = array();
                    $totalDigitalForecast = array();
                    $totalPayTvForecast[$a] = ($this->getValueByClient($con,$salesRep,$year,$value,$month,'tRex',$clients[$a]['clientID'],$clients[$a]['agencyID'], $region,'pay tv', '1,2,3')['revenue']);
                    $totalDigitalForecast[$a] = ($this->getValueByClient($con,$salesRep,$year,$value,$month,'tRex',$clients[$a]['clientID'],$clients[$a]['agencyID'], $region,'digital','1,2,3')['revenue']);
                }   */
                
                $totalCurrentPayTvBookings[$a] = ($this->getValueByMonth($con,$salesRep,$year,$value,$month,'bookings',$clients[$a]['clientID'],$clients[$a]['agencyID'], $region,'pay tv','1,2,3')['revenue']);           
                $totalCurrentDigitalBookings[$a] = ($this->getValueByMonth($con,$salesRep,$year,$value,$month,'bookings',$clients[$a]['clientID'],$clients[$a]['agencyID'], $region,'digital','1,2,3')['revenue']);
                 
                $totalForecast[$a] = ($totalPayTvForecast[$a] + $totalDigitalForecast[$a]);
                //var_dump($totalCurrentDigitalBookings);
                $totalBookings[$a] = ($totalCurrentPayTvBookings[$a] + $totalCurrentDigitalBookings[$a]);

                $totalPending[$a] = ($totalForecast[$a]) - ($totalBookings[$a]);
                if ($totalPending[$a] < 0) {
                    $totalPending[$a] = 0;
                }
                
                $probability[$a] = $this->getProbabilityNewClient($con,$clients[$a]['clientID'],$clients[$a]['agencyID'],$salesRep,$month);
                

                $totalPivot[$a] = array('payTvForecast' => ($totalPayTvForecast[$a]), 'digitalForecast' => ($totalDigitalForecast[$a]),'forecast' => ($totalForecast[$a]), 'bookings' => ($totalBookings[$a]),'pending' => ($totalPending[$a]),'currentPayTvBookings' => $totalCurrentPayTvBookings[$a], 'currentDigitalBookings' => $totalCurrentDigitalBookings[$a]);
           
                $clientInfo[$a] = array('clientName' => $clients[$a]['clientName'], 'clientID' => $clients[$a]['clientID'],'agencyName' => $clients[$a]['agencyName'], 'agencyID' => $clients[$a]['agencyID'], 'probability' => $probability[$a]);
            }

           
            $table = array('clientInfo' => $clientInfo,'companyValues' => $pivot,'total' => $totalPivot);
        }
        

        //var_dump($table);
       
        //var_dump($table['clientInfo']);
        return $table;
    }

   
    public function checkDuplicates($con, $salesRep,$month,$client,$agency,$platform){
        $sql =  new sql();

        $selectClient = "SELECT id
                            from new_clients_fcst f
                            WHERE (f.sales_rep_id IN ($salesRep))
                            AND f.month = $month
                            AND f.client_id = $client
                            AND f.agency_id = $agency
                            AND f.platform = '$platform'";
                    // var_dump($selectClient);
            $resultClient = $con->query($selectClient);
            $from = array('id');
            $forecastID = $sql->fetch($resultClient, $from, $from);

            if (sizeof($forecastID) > 1) {         
                $tmp = $forecastID[0]['id'];      
                $delete =  "DELETE FROM new_clients_fcst f
                            WHERE (f.sales_rep_id IN ($salesRep))
                            AND f.month = $month
                            AND f.client_id = $client
                            AND f.agency_id = $agency
                            AND f.platform = '$platform'
                            AND f.id != '$tmp'
                            ";
                $query = $con->query($delete);
               // var_dump($delete);
            }

             //var_dump($forecastID);
    }

     //make a list of clients for the front-end button to add a new client basis on existing clients
    public function listOFAgencies(Object $con){
        $sql = new sql();
        $year = (int)date("Y");
        $pYear = $year-1;
        $ppYear = $year-2;

        $select = "SELECT DISTINCT  a.name as agency, a.ID as aID
                    FROM agency a                    
                    left join agency_group ag on ag.ID = a.agency_group_id
                    WHERE ag.region_id = 1
                    AND a.ID != 2831
                    ORDER BY a.name ASC";
        //var_dump($select);
        $from = array('aID','agency');
        $selectQuery = $con->query($select);
        $client = $sql->fetch($selectQuery, $from, $from);
        $client = $client;
        return $client;
    }

     //make a list of clients for the front-end button to add a new client basis on existing clients
    public function listOFClients(Object $con){
        $sql = new sql();
        $year = (int)date("Y");
        $pYear = $year-1;
        $ppYear = $year-2;

        $select = "SELECT DISTINCT c.name as client, c.ID AS clientId 
                    FROM client c                   
                    WHERE c.client_group_id = 1
                    AND c.id != 9552
                    ORDER BY c.name ASC";
        //var_dump($select);
        $from = array('clientId','client');
        $selectQuery = $con->query($select);
        $client = $sql->fetch($selectQuery, $from, $from);
        $client = $client;
        return $client;
    }

    //this function make the inclusion of new clients to make forecast
    public function newClientInclusion(Object $con, String $salesRep, String $client,String $agency,$wm,$spt,$dc,$platform,$probability,$month){
        $updateTime = date("Y-m-d");

        $insertQuery = "INSERT INTO  new_clients_fcst
                        SET created_date = '$updateTime',
                        sales_rep_id = $salesRep,
                        client_id = '$client',
                        agency_id = '$agency',
                        platform = '$platform',
                        wm = $wm,
                        spt = $spt,
                        dc = $dc,
                        month = $month,
                        probability = $probability
                        ";
        //var_dump($insertQuery);
        $resultInsertQuery = $con->query($insertQuery);
    }

    //this function make the inclusion of new clients to make forecast
    public function newClientRequest(Object $con, String $salesRep, String $client,String $agency,int $month){
        $updateTime = date("Y-m-d");

        $insertQuery = "INSERT INTO  requests
                        SET 
                        sales_rep_id = $salesRep,
                        client = '$client',
                        agency = '$agency',
                        month = $month,
                        request_date = '$updateTime'
                        ";
        //var_dump($insertQuery);
        $resultInsertQuery = $con->query($insertQuery);
    }

    public function getSalesRepByClient(String $salesRep, Object $con, Object $sql,string $repName, int $month){
        
        $year = (int)date("Y");
        $pYear = $year-1;

         $selectClient = "SELECT distinct  c.ID as clientID, c.name as clientName, a.ID as agencyID, a.name as agencyName
                            from new_clients_fcst f
                            left join sales_rep sr on sr.ID = f.sales_rep_id 
                            LEFT JOIN client c ON c.id = f.client_id
                            LEFT JOIN agency a ON a.id = f.agency_id
                            WHERE (sr.ID IN ($salesRep))
                            AND month = $month";
                    // var_dump($selectClient);
            $resultClient = $con->query($selectClient);
            $from = array('clientName','clientID','agencyName','agencyID');
            $client = $sql->fetch($resultClient, $from, $from);
            //var_dump($client);

            return $client;
            
    }

    //THIS FUNCTION SAVE OR UPDATE THE FORECAST MADE BY THE SALES REP
    public function saveForecast(Object $con, int $client, int $agency, int $year, String $value, String $company, String $month, string $salesRep, String $platform, string $forecastValue, string $currency, string $probability, bool $check){
        $sql = new sql();

         $selectQuery = "SELECT agency_id AS agency, client_id AS client
                        FROM monthly_forecast
                        WHERE sales_rep_id = $salesRep
                        AND client_id = $client
                        AND agency_id = $agency
                        AND month = $month
                        AND company_id = $company
                        AND currency = $currency
                        AND value = '$value'
                        AND year = $year 
                        AND platform = '$platform'";
        $from = array('agency', 'client');
        $selectResultQuery = $con->query($selectQuery);
        $resultSelect = $sql->fetch($selectResultQuery, $from, $from);

        //var_dump($resultSelect);
        if ($resultSelect == false){
             //var_dump($forecastValue);
            mysqli_query($con,"INSERT INTO  monthly_forecast
                        SET sales_rep_id = $salesRep,
                        client_id = $client,
                        agency_id = $agency,
                        month = $month,
                        company_id = $company,
                        currency = $currency,
                        value = '$value',
                        year = $year,
                        platform = '$platform',
                        success_probability = $probability,
                        revenue = '$forecastValue'");
                   // var_dump('aki');
           
            //$resultQuery = $con->query($insertQuery);
              
        }else{
             mysqli_query($con,"UPDATE monthly_forecast
                        SET revenue = $forecastValue, success_probability = $probability
                        WHERE sales_rep_id = $salesRep
                        AND client_id = $client
                        AND agency_id = $agency
                        AND month = $month
                        AND company_id = $company
                        AND currency = $currency
                        AND value = '$value'
                        AND year = $year 
                        AND platform = '$platform'");
           
        }
    }

     public function saveForecastNew(Object $con, string $client, string $agency, int $year, String $value, String $wm, string $dc, string $spt, String $month, int $salesRep, String $platform, int $currency, int $probability, bool $check){
        $sql = new sql();
        $updateTime = date("Y-m-d");

        $selectQuery = "SELECT agency_id AS agency, client_id AS client
                        FROM new_clients_fcst
                        WHERE sales_rep_id = $salesRep
                        AND client_id = '$client'
                        AND agency_id = '$agency'
                        AND platform = '$platform'                        
                        AND month = $month";
        $from = array('agency', 'client');
        $selectResultQuery = $con->query($selectQuery);
        $resultSelect = $sql->fetch($selectResultQuery, $from, $from);
        //var_dump($resultSelect);
        if ($resultSelect == false){
             //var_dump($forecastValue);
            mysqli_query($con,"INSERT INTO  new_clients_fcst
                        SET created_date = '$updateTime',
                        sales_rep_id = $salesRep,
                        client_id = '$client',
                        agency_id = '$agency',
                        platform = '$platform',
                        wm = $wm,
                        spt = $spt,
                        dc = $dc
                        month = $month,
                        probability = $probability");
        }else{
             mysqli_query($con,"UPDATE new_clients_fcst
                        SET  wm = $wm, spt = $spt, dc = $dc, probability = $probability
                        WHERE sales_rep_id = $salesRep
                        AND client_id = '$client'
                        AND agency_id = '$agency'
                        AND platform = '$platform'                        
                        AND month = $month
                        ");  
        }
    }

    //THIS FUNCTION GET THE VALUE BY MONTH AND COMPANY BY CLIENT AND REP
    public function getValueByMonth(Object $con, $salesRep, int $year, string $value, int $month, string $table, int $client=null, int $agency=null, int $regionID, string $platform=null, string $company=null){
        $sql = new sql();
        $base = new base();

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
                                WHERE w.current_sales_rep_id IN ($salesRep)
                                AND w.year = $year
                                AND w.month = $month
                                AND (b.brand_group_id IN ($company))
                                AND b.type = 'Linear'                                
                                "; 
                                //var_dump($select);
                        }else{
                            $select = "SELECT sum($value) as revenue
                                FROM wbd w
                                LEFT JOIN brand b on b.id = w.brand_id
                                WHERE w.current_sales_rep_id IN ($salesRep)
                                AND w.year = $year
                                AND w.month = $month
                                AND (b.brand_group_id IN ($company))
                                AND b.type = 'Non-Linear'
                                "; 
                                //var_dump($select);
                        }
                    }else{
                        $select = "SELECT sum($value) as revenue
                            FROM wbd w
                            LEFT JOIN brand b on b.id = w.brand_id
                            WHERE w.current_sales_rep_id IN ($salesRep)
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
                                AND w.current_sales_rep_id IN ($salesRep)
                                AND w.month = $month
                                AND c.ID = $client
                                AND a.ID = $agency
                                AND (b.brand_group_id IN ($company))
                                AND b.type = 'Linear'
                                AND $value != 0
                                "; 
                        }else{
                            $select = "SELECT sum($value) as revenue
                                FROM wbd w
                                LEFT JOIN brand b on b.id = w.brand_id
                                LEFT JOIN client c ON c.ID = w.client_id
                                LEFT JOIN agency a ON a.ID = w.agency_id
                                WHERE w.year = $year
                                AND w.current_sales_rep_id IN ($salesRep)
                                AND w.month = $month
                                AND c.ID = $client
                                AND a.ID = $agency
                                AND (b.brand_group_id IN ($company))
                                AND b.type = 'Non-Linear'
                                AND $value != 0
                                "; 
                        }
                    }else{
                        $select = "SELECT sum($value) as revenue
                            FROM wbd w
                            LEFT JOIN brand b on b.id = w.brand_id
                            LEFT JOIN client c ON c.ID = w.client_id
                            LEFT JOIN agency a ON a.ID = w.agency_id
                            WHERE w.year = $year
                            AND w.current_sales_rep_id IN ($salesRep)
                            AND w.month = $month
                            AND c.ID = $client
                            AND a.ID = $agency
                            AND (b.brand_group_id IN ($company))
                            AND $value != 0
                            ";    
                    }                   

                }
              
             // echo "<pre>$select</pre>";

                $query = $con->query($select);
                $from = 'revenue';
                $result = $sql->fetchSUM($query,$from);
                
                if ($result['revenue'] == null) {
                    $result['revenue'] = 0.0;
                }
                //var_dump($result);

                return $result;
            break;
            
            case 'target':
                $select = "SELECT sum(pbs.value) as revenue
                            FROM plan_by_sales pbs
                            LEFT JOIN brand b on b.id = pbs.brand_id
                            WHERE pbs.sales_rep_id IN ($salesRep)
                            AND pbs.year = $year
                            AND pbs.month = $month
                            AND pbs.type_of_revenue = '$value'
                            AND pbs.region_id = $regionID
                            AND (b.brand_group_id IN ($company))
                            ";
                //var_dump($select);
                $query = $con->query($select);
                $from = 'revenue';
                $result = $sql->fetchSUM($query,$from);
                
                if ($result['revenue'] == null) {
                    $result['revenue'] = 0.0;
                }
                 return $result;
            break;
            
            case 'forecast':
                
                if ($client == null && $agency == null) {
                    $selectAE = "SELECT SUM(f.revenue) as revenue
                                FROM monthly_forecast f                                
                                WHERE f.sales_rep_id IN ($salesRep)
                                AND f.year = $year
                                AND f.month = $month
                                AND f.value = '$value'
                                AND (f.company_id IN ($company))
                                AND (f.platform = '$platform')
                                ";
                        //var_dump($selectAE);
                    $query = $con->query($selectAE);
                    $from = 'revenue';
                    $resultAE = $sql->fetchSUM($query,$from);
                }else{
                    $selectAE = "SELECT SUM(f.revenue) as revenue
                                FROM monthly_forecast f
                                LEFT JOIN client c ON c.ID = f.client_id
                                LEFT JOIN agency a ON a.ID = f.agency_id
                                WHERE f.sales_rep_id IN ($salesRep)
                                AND f.year = $year
                                AND f.month = $month
                                AND f.value = '$value'
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
                }
                return $resultAE;
            break;

            case 'tRex':    
                $monthT = $base->intToMonth2(array($month));
                $monthT = strtolower($monthT[0]);
               if ($client == null && $agency == null) {
                    $selectForecast = "SELECT sum(f.$monthT) as revenue                                    
                                FROM forecast f
                                LEFT JOIN brand b on b.id = f.brand_id
                                WHERE f.sales_rep_id IN ($salesRep)
                                AND (b.brand_group_id IN ($company)) 
                                AND (f.platform = '$platform')
                                ";
                        //var_dump($select);
                    $query = $con->query($selectForecast);
                    $from = 'revenue';
                    $resultForecast = $sql->fetchSUM($query,$from);
                }else{
                    $selectForecast = "SELECT sum(f.$monthT) as revenue
                                FROM forecast f
                                LEFT JOIN brand b on b.id = f.brand_id
                                LEFT JOIN client c ON c.ID = f.client_id
                                LEFT JOIN agency a ON a.ID = f.agency_id
                                WHERE f.sales_rep_id IN ($salesRep)
                                AND c.id = $client
                                AND a.id = $agency
                                AND (b.brand_group_id IN ($company)) 
                                AND (f.platform = '$platform')                                
                                ";
                        //var_dump($select);
                    $query = $con->query($selectForecast);
                    $from = 'revenue';
                    $resultForecast = $sql->fetchSUM($query,$from);

                    if ($resultForecast['revenue'] == null) {
                        $resultForecast['revenue'] = 0.0;
                    }
                     
                }
                return $resultForecast;
            break;
        }

       
    }

     public function getValueByClient(Object $con, $salesRep, int $year, string $value, String $month, string $table, String $client=null, string $agency=null, int $regionID, string $platform=null, string $company=null){
        $sql = new sql();
        $base = new base();

        if ($table == 'bookings') {
            $value .= "_value";    
        }elseif ($table == 'target') {
            strtoupper($value);
        }
        
        switch($table){
            
            case 'forecast':                
                if ($client == null && $agency == null) {
                    $selectAE = "SELECT SUM(f.$company) as revenue
                                FROM new_clients_fcst f                                
                                WHERE f.sales_rep_id IN ($salesRep)
                                AND (f.platform = '$platform')
                                AND f.month = $month
                                ";
                    //var_dump($selectAE);

                    $query = $con->query($selectAE);
                    $from = 'revenue';
                    $resultAE = $sql->fetchSUM($query,$from);
                }else{
                    $selectAE = "SELECT SUM(f.$company) as revenue
                                FROM new_clients_fcst f 
                                LEFT JOIN client c ON c.ID = f.client_id
                                LEFT JOIN agency a ON a.ID = f.agency_id
                                WHERE f.sales_rep_id IN ($salesRep)
                                AND f.client_id = '$client'
                                AND f.agency_id = '$agency'
                                AND (f.platform = '$platform')
                                AND f.month = $month
                                AND $company != 0
                                ";
                        //var_dump($selectAE);
                    $query = $con->query($selectAE);
                    $from = 'revenue';
                    $resultAE = $sql->fetchSUM($query,$from);
                    //echo "<pre>$selectAE</pre>";
                    if ($resultAE['revenue'] == null) {
                        $resultAE['revenue'] = 0.0;
                    }
                }
                return $resultAE;
            break;

            case 'tRex':    
               if ($client == null && $agency == null) {
                    $selectForecast = "SELECT sum(f.$month) as revenue                                    
                                FROM forecast f
                                LEFT JOIN brand b on b.id = f.brand_id
                                WHERE f.sales_rep_id IN ($salesRep)
                                AND (b.brand_group_id IN ($company)) 
                                AND (f.platform = '$platform')
                                ";
                        //var_dump($select);
                    $query = $con->query($selectForecast);
                    $from = 'revenue';
                    $resultForecast = $sql->fetchSUM($query,$from);
                }else{
                    $selectForecast = "SELECT sum(f.$month) as revenue
                                FROM forecast f
                                LEFT JOIN brand b on b.id = f.brand_id
                                LEFT JOIN client c ON c.ID = f.client_id
                                LEFT JOIN agency a ON a.ID = f.agency_id
                                WHERE f.sales_rep_id IN ($salesRep)
                                AND f.client_id = $client
                                AND f.client_id = $agency
                                AND (b.brand_group_id IN ($company)) 
                                AND (f.platform = '$platform') 
                                AND $month != 0                               
                                ";
                        //var_dump($select);
                    $query = $con->query($selectForecast);
                    $from = 'revenue';
                    $resultForecast = $sql->fetchSUM($query,$from);

                    if ($resultForecast['revenue'] == null) {
                        $resultForecast['revenue'] = 0.0;
                    }
                     
                }
                return $resultForecast;
            break;
        }

       
    }
    //this function check if exists forecast in database for the current rep
    public function checkForecast(Object $con, int $salesRep, int $month/*,$client,$agency*/){
        $sql = new sql();

        $selectQuery = "SELECT sales_rep_id as salesRep
                        FROM monthly_forecast
                        WHERE sales_rep_id IN ($salesRep)
                        AND month = $month
                        ";
            // echo "<pre>$selectQuery</pre>";
        $from = array('salesRep');
        $selectResultQuery = $con->query($selectQuery);
        $resultSelect = $sql->fetch($selectResultQuery, $from, $from);

        if ($resultSelect) {
            $resultSelect = true;
        }

        return $resultSelect;
    }

    //this function check if exists forecast in database for the current rep
    public function checkForecastNew(Object $con, int $salesRep){
        $sql = new sql();

        $selectQuery = "SELECT sales_rep_id as salesRep
                        FROM new_clients_fcst
                        WHERE sales_rep_id IN ($salesRep)
                        ";
            // echo "<pre>$selectQuery</pre>";
        $from = array('salesRep');
        $selectResultQuery = $con->query($selectQuery);
        $resultSelect = $sql->fetch($selectResultQuery, $from, $from);

        if ($resultSelect) {
            $resultSelect = true;
        }

        return $resultSelect;
    }

    //function to get the success probability of forecast
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
            $resultSelect[0]['probability'] = 100;
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

        if ($resultSelect == false) {
            $resultSelect[0]['probability'] = 100;
        }

        return $resultSelect;
    }
    //THIS FUNCTION GET ALL THE CLIENTS FOR THE SELECTED SALES REP
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
       // var_dump($resultWBD);

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

    //THIS FUNCTION ADD THE QUARTERS AND THE TOTAL TO THE VARIABLE
    

    public function checkNewClients($conDLA,$con,$table,$sql,$region,$salesRepName){
        $sql = new sql();
        $tableDLA = 'client_unit';

        $somethingDLA = "name";
        $something = "client";

        $fromDLA = array("name");
        $from = array("client");

        $r = new region();

        $seekRegion = $r->getRegion($conDLA,array($region))[0];

        $selectDistinctFM = "SELECT DISTINCT client FROM $table ORDER BY client";
        //var_dump($selectDistinctFM);
       
        $res = $con->query($selectDistinctFM);
        
        $resultsFM = $sql->fetch($res,array("client"),array("client"));
        //var_dump($resultsFM);

       
        if($resultsFM){
                $distinctDLA = $this->getDistinct($conDLA,$somethingDLA,$tableDLA,$sql,$fromDLA,$seekRegion['name'],"client");
               // var_dump($distinctDLA);
                $distinctFM = $this->makeDistinct($resultsFM);//$this->getDistinct($con,$something,$table,$sql,$from);
                //var_dump($distinctFM);
                $new = $this->checkDifferencesAC('client', $distinctDLA, $distinctFM, $table);  
                if ($new) {
                    /*for ($n=0; $n <sizeof($new) ; $n++) { 
                        
                       // $crete = $this->createNewClient($con,$new)
                    }*/
                    
                }else{
                    for ($d=0; $d <sizeof($distinctFM); $d++) { 
                        $select = "SELECT distinct  cu.client_id as id
                            from client_unit cu
                            left join client c on c.ID  = cu.client_id 
                            where cu.name = (\"".$distinctFM[$d]['client']."\")
                            and c.client_group_id = 1";
                            
                        $query = $conDLA->query($select);
                        $from = array('id');
                        $result = $sql->fetch($query,$from,$from);
                    
                        mysqli_query($con,'UPDATE new_clients_fcst
                        SET client_id = $result[0]["id"]
                        WHERE client = (\"".$distinctFM[$d]["client"]."\")
                        ');
                    }
                    
                    //var_dump($distinctFM);
                }
                //var_dump($new);
        }else{ 
            $new = false;
        }
        //return $new;
    }

    public function checkNewAgencies($conDLA,$con,$table,$sql,$region){
        $tableDLA = 'agency_unit';

        $somethingDLA = "name";
        $something = "agency";

        $fromDLA = array("name");
        $from = array("agency");

        $r = new region();

        $seekRegion = $r->getRegion($conDLA,array($region))[0];

        $selectDistinctFM = "SELECT DISTINCT agency FROM $table ORDER BY agency";
        

        //var_dump($selectDistinctFM);

        $res = $con->query($selectDistinctFM);
        $sql = new sql();

        $resultsFM = $sql->fetch($res,array("agency"),array("agency"));
       
        //var_dump($resultsFM);
        if($resultsFM){         
                $distinctDLA = $this->getDistinct($conDLA,$somethingDLA,$tableDLA,$sql,$fromDLA,$seekRegion['name'],"agency");
                $distinctFM = $this->makeDistinct($resultsFM);//$this->getDistinct($con,$something,$table,$sql,$from);
                $new = $this->checkDifferencesAC('agency', $distinctDLA, $distinctFM, $table);  
        }else{
            $new = false;
        }

        return $new;
    }

    public function createNewClient($con,$client){

        $query = "Ins";
    }


    public function getDistinct($con,$something,$table,$sql,$from,$region,$type){

        if($region){
            if($type == "agency" || $type == 'agency_id'){
                $join = "LEFT JOIN agency a ON t.agency_id = a.ID
                         LEFT JOIN agency_group ag ON a.agency_group_id = ag.ID
                         LEFT JOIN region r ON ag.region_id = r.ID";
            }elseif ($type == "client" || $type == 'client_id') {
                $join = "LEFT JOIN client c ON t.client_id = c.ID
                         LEFT JOIN client_group cg ON c.client_group_id = cg.ID
                         LEFT JOIN region r ON cg.region_id = r.ID";
            }

            $select = "SELECT DISTINCT t.$something FROM $table t $join WHERE(r.name = '".$region."') AND(t.$something != '') ORDER BY $something ";

        }else{
            $select = "SELECT DISTINCT $something FROM $table ORDER BY $something";
        }
        //var_dump($select);
        $res = $con->query($select);
        $tmp = $sql->fetch($res,$from,$from);

        for ($t=0; $t < sizeof($tmp); $t++) {
            for ($f=0; $f < sizeof($from); $f++) {
                $distinct[$t] = $tmp[$t][$from[$f]];
            }
        }
        //var_dump($distinct);
        return $distinct;
    }

    public function getDistinctNR($con,$something,$table,$sql,$from){

        $select = "SELECT DISTINCT $something FROM $table ORDER BY $something";

        $res = $con->query($select);
        $tmp = $sql->fetch($res,$from,$from);

        for ($t=0; $t < sizeof($tmp); $t++) {
            for ($f=0; $f < sizeof($from); $f++) {
                $distinct[$t] = $tmp[$t][$from[$f]];
            }
        }
        return $distinct;
    }

    public function checkDifferencesAC(string $type, array $dla, array $fm, string $table){

        $new = array();
        $test = array();
        $formattedName = array();
        //var_dump($fm);
        //var_dump($dla);

        for ($f = 0; $f < sizeof($fm); $f++) {
            $fmName[] = $this->remove_accents($fm[$f][$type]);
        }

        for ($d = 0; $d < sizeof($dla); $d++) {
            $dlaName[] = $this->remove_accents($dla[$d]);
        }

        //var_dump($fmName);
        //var_dump($dlaName);

        $typeName = array_udiff($fmName, $dlaName, 'strcasecmp');
        //var_dump($typeName);

        $regionID = array_keys($typeName);
        //var_dump($regionID);

        for ($j = 0; $j < sizeof($typeName); $j++) {
            $formattedName[] = $fm[$regionID[$j]][$type];
        }
        
        //var_dump($formattedName);

        //var_dump($table);

        for ($x = 0; $x < sizeof($formattedName); $x++){
            $test[$type] = $formattedName[$x];
            $test['region'] = 'BRAZIL';
            $new[$x] = $test;
        } 
        
        if(empty($new)){
            $rtr = false;
        }else{
            $rtr = $new;
        }

        //var_dump($rtr);
        return $rtr;

    }

    public function makeDistinct($array){

        $unique = array_map("unserialize", array_unique(array_map("serialize", $array)));
        //var_dump($unique);
        return $unique;

    }
    
    function remove_accents($string) {
        if ( !preg_match('/[\x80-\xff]/', $string) )
            return $string;
    
        $chars = array(
        // Decompositions for Latin-1 Supplement
        chr(195).chr(128) => 'A', chr(195).chr(129) => 'A',
        chr(195).chr(130) => 'A', chr(195).chr(131) => 'A',
        chr(195).chr(132) => 'A', chr(195).chr(133) => 'A',
        chr(195).chr(135) => 'C', chr(195).chr(136) => 'E',
        chr(195).chr(137) => 'E', chr(195).chr(138) => 'E',
        chr(195).chr(139) => 'E', chr(195).chr(140) => 'I',
        chr(195).chr(141) => 'I', chr(195).chr(142) => 'I',
        chr(195).chr(143) => 'I', chr(195).chr(145) => 'N',
        chr(195).chr(146) => 'O', chr(195).chr(147) => 'O',
        chr(195).chr(148) => 'O', chr(195).chr(149) => 'O',
        chr(195).chr(150) => 'O', chr(195).chr(153) => 'U',
        chr(195).chr(154) => 'U', chr(195).chr(155) => 'U',
        chr(195).chr(156) => 'U', chr(195).chr(157) => 'Y',
        chr(195).chr(159) => 's', chr(195).chr(160) => 'a',
        chr(195).chr(161) => 'a', chr(195).chr(162) => 'a',
        chr(195).chr(163) => 'a', chr(195).chr(164) => 'a',
        chr(195).chr(165) => 'a', chr(195).chr(167) => 'c',
        chr(195).chr(168) => 'e', chr(195).chr(169) => 'e',
        chr(195).chr(170) => 'e', chr(195).chr(171) => 'e',
        chr(195).chr(172) => 'i', chr(195).chr(173) => 'i',
        chr(195).chr(174) => 'i', chr(195).chr(175) => 'i',
        chr(195).chr(177) => 'n', chr(195).chr(178) => 'o',
        chr(195).chr(179) => 'o', chr(195).chr(180) => 'o',
        chr(195).chr(181) => 'o', chr(195).chr(182) => 'o',
        chr(195).chr(182) => 'o', chr(195).chr(185) => 'u',
        chr(195).chr(186) => 'u', chr(195).chr(187) => 'u',
        chr(195).chr(188) => 'u', chr(195).chr(189) => 'y',
        chr(195).chr(191) => 'y',
        // Decompositions for Latin Extended-A
        chr(196).chr(128) => 'A', chr(196).chr(129) => 'a',
        chr(196).chr(130) => 'A', chr(196).chr(131) => 'a',
        chr(196).chr(132) => 'A', chr(196).chr(133) => 'a',
        chr(196).chr(134) => 'C', chr(196).chr(135) => 'c',
        chr(196).chr(136) => 'C', chr(196).chr(137) => 'c',
        chr(196).chr(138) => 'C', chr(196).chr(139) => 'c',
        chr(196).chr(140) => 'C', chr(196).chr(141) => 'c',
        chr(196).chr(142) => 'D', chr(196).chr(143) => 'd',
        chr(196).chr(144) => 'D', chr(196).chr(145) => 'd',
        chr(196).chr(146) => 'E', chr(196).chr(147) => 'e',
        chr(196).chr(148) => 'E', chr(196).chr(149) => 'e',
        chr(196).chr(150) => 'E', chr(196).chr(151) => 'e',
        chr(196).chr(152) => 'E', chr(196).chr(153) => 'e',
        chr(196).chr(154) => 'E', chr(196).chr(155) => 'e',
        chr(196).chr(156) => 'G', chr(196).chr(157) => 'g',
        chr(196).chr(158) => 'G', chr(196).chr(159) => 'g',
        chr(196).chr(160) => 'G', chr(196).chr(161) => 'g',
        chr(196).chr(162) => 'G', chr(196).chr(163) => 'g',
        chr(196).chr(164) => 'H', chr(196).chr(165) => 'h',
        chr(196).chr(166) => 'H', chr(196).chr(167) => 'h',
        chr(196).chr(168) => 'I', chr(196).chr(169) => 'i',
        chr(196).chr(170) => 'I', chr(196).chr(171) => 'i',
        chr(196).chr(172) => 'I', chr(196).chr(173) => 'i',
        chr(196).chr(174) => 'I', chr(196).chr(175) => 'i',
        chr(196).chr(176) => 'I', chr(196).chr(177) => 'i',
        chr(196).chr(178) => 'IJ',chr(196).chr(179) => 'ij',
        chr(196).chr(180) => 'J', chr(196).chr(181) => 'j',
        chr(196).chr(182) => 'K', chr(196).chr(183) => 'k',
        chr(196).chr(184) => 'k', chr(196).chr(185) => 'L',
        chr(196).chr(186) => 'l', chr(196).chr(187) => 'L',
        chr(196).chr(188) => 'l', chr(196).chr(189) => 'L',
        chr(196).chr(190) => 'l', chr(196).chr(191) => 'L',
        chr(197).chr(128) => 'l', chr(197).chr(129) => 'L',
        chr(197).chr(130) => 'l', chr(197).chr(131) => 'N',
        chr(197).chr(132) => 'n', chr(197).chr(133) => 'N',
        chr(197).chr(134) => 'n', chr(197).chr(135) => 'N',
        chr(197).chr(136) => 'n', chr(197).chr(137) => 'N',
        chr(197).chr(138) => 'n', chr(197).chr(139) => 'N',
        chr(197).chr(140) => 'O', chr(197).chr(141) => 'o',
        chr(197).chr(142) => 'O', chr(197).chr(143) => 'o',
        chr(197).chr(144) => 'O', chr(197).chr(145) => 'o',
        chr(197).chr(146) => 'OE',chr(197).chr(147) => 'oe',
        chr(197).chr(148) => 'R',chr(197).chr(149) => 'r',
        chr(197).chr(150) => 'R',chr(197).chr(151) => 'r',
        chr(197).chr(152) => 'R',chr(197).chr(153) => 'r',
        chr(197).chr(154) => 'S',chr(197).chr(155) => 's',
        chr(197).chr(156) => 'S',chr(197).chr(157) => 's',
        chr(197).chr(158) => 'S',chr(197).chr(159) => 's',
        chr(197).chr(160) => 'S', chr(197).chr(161) => 's',
        chr(197).chr(162) => 'T', chr(197).chr(163) => 't',
        chr(197).chr(164) => 'T', chr(197).chr(165) => 't',
        chr(197).chr(166) => 'T', chr(197).chr(167) => 't',
        chr(197).chr(168) => 'U', chr(197).chr(169) => 'u',
        chr(197).chr(170) => 'U', chr(197).chr(171) => 'u',
        chr(197).chr(172) => 'U', chr(197).chr(173) => 'u',
        chr(197).chr(174) => 'U', chr(197).chr(175) => 'u',
        chr(197).chr(176) => 'U', chr(197).chr(177) => 'u',
        chr(197).chr(178) => 'U', chr(197).chr(179) => 'u',
        chr(197).chr(180) => 'W', chr(197).chr(181) => 'w',
        chr(197).chr(182) => 'Y', chr(197).chr(183) => 'y',
        chr(197).chr(184) => 'Y', chr(197).chr(185) => 'Z',
        chr(197).chr(186) => 'z', chr(197).chr(187) => 'Z',
        chr(197).chr(188) => 'z', chr(197).chr(189) => 'Z',
        chr(197).chr(190) => 'z', chr(197).chr(191) => 's'
        );
    
        $string = strtr($string, $chars);
    
        return $string;
    }
}
