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
    public function makeRepTable(Object $con, int $salesRep, Object $pr, int $year, int $pYear, int $region, int $currencyID, string $value,$clientsTable,$cDate,$lastMonday){
        $company = array('1','2','3');
        $month = array('1','2','3','4','5','6','7','8','9','10','11','12');

        $companyView = array('wm','dc','spt');
             
        if($currencyID == 1 ){
            $pRate = 1;
        }else{
            $pRate = $pr->getPRateByRegionAndYear($con,array($region), array($year));    
        }

        $check = $this->checkForecast($con, $salesRep);//check if exists forecast for this rep in database

        for ($c=0; $c <sizeof($company); $c++) { //this for is to make the interactons for the 3 companies
            for ($m=0; $m <sizeof($month) ; $m++) {  //this one is to the months

                $currentBookings[$c][$m] = ($this->getValueByMonth($con,$salesRep,$year,$value,$month[$m],'bookings',null,null, $region,null,$company[$c])['revenue']);                
                $previousBookings[$c][$m] = ($this->getValueByMonth($con,$salesRep,$pYear,$value,$month[$m],'bookings',null, null, $region,null,$company[$c])['revenue']);
                
                if($currencyID == 1 ){
                     $pRate = $pr->getPRateByRegionAndYear($con,array($region), array($year)); 

                    $currentTarget[$c][$m] = floatval($this->getValueByMonth($con,$salesRep,$year,$value,$month[$m],'target',null, null, $region,null,$company[$c])['revenue'])*$pRate;   
                }else{
                    $pRate = 1;

                    $currentTarget[$c][$m] = floatval($this->getValueByMonth($con,$salesRep,$year,$value,$month[$m],'target',null, null, $region,null,$company[$c])['revenue'])*$pRate;   
                }     
                
                if($currencyID == 1 ){
                    $pRate = 1;
                }else{
                    $pRate = $pr->getPRateByRegionAndYear($con,array($region), array($year));    
                }  
                $payTvForecast[$c][$m] = 0;
                $digitalForecast[$c][$m] = 0;
                $monthPayTvForecast[$c][$m] = 0;
                $monthDigitalForecast[$c][$m] = 0;
                $totalPayTvForecast[$m] = 0; 
                $totalDigitalForecast[$m] = 0;
                $totalMonthPayTvForecast[$m] = 0;
                $totalMonthDigitalForecast[$m] = 0; 

                for ($p=0; $p <sizeof($clientsTable['clientInfo']); $p++) { 
                      //var_dump($clientsTable['clientInfo'][$p]['probability'][0]['probability']);
                   // var_dump(($clientsTable['clientInfo'][$p]['probability'][0]['probability']/100));
                    $payTvForecast[$c][$m] += (($clientsTable['companyValues'][$p][$c]['payTvForecastC'][$m])*($clientsTable['clientInfo'][$p]['probability'][0]['probability']/100));
                    //var_dump($payTvForecast);
                    $digitalForecast[$c][$m] += ($clientsTable['companyValues'][$p][$c]['digitalForecastC'][$m]*($clientsTable['clientInfo'][$p]['probability'][0]['probability']/100));

                    $totalPayTvForecast[$m] += (($clientsTable['total'][$p]['payTvForecastC'][$m])*($clientsTable['clientInfo'][$p]['probability'][0]['probability']/100));
                    //var_dump($tempPayTvForecast);
                    $totalDigitalForecast[$m] += ($clientsTable['total'][$p]['digitalForecastC'][$m]*($clientsTable['clientInfo'][$p]['probability'][0]['probability']/100));

                    $monthPayTvForecast[$c][$m] += $clientsTable['companyValues'][$p][$c]['monthPayTvForecast'][$m];                 
                    $monthDigitalForecast[$c][$m] += $clientsTable['companyValues'][$p][$c]['monthDigitalForecast'][$m];

                    $totalMonthPayTvForecast[$m] += $clientsTable['total'][$p]['monthPayTvForecast'][$m];
                    //var_dump($tempPayTvForecast);
                    $totalMonthDigitalForecast[$m] += $clientsTable['total'][$p]['monthDigitalForecast'][$m];                                     
                }              
                
                $currentPayTvBookings[$c][$m] = ($this->getValueByMonth($con,$salesRep,$year,$value,$month[$m],'bookings',null,null, $region,'pay tv',$company[$c])['revenue']);
                $currentDigitalBookings[$c][$m] = ($this->getValueByMonth($con,$salesRep,$year,$value,$month[$m],'bookings',null,null, $region,'digital',$company[$c])['revenue']);

                $totalCurrentBookings[$m] = ($this->getValueByMonth($con,$salesRep,$year,$value,$month[$m],'bookings',null,null, $region,null,'1,2,3')['revenue']);           
                $totalPreviousBookings[$m] = ($this->getValueByMonth($con,$salesRep,$pYear,$value,$month[$m],'bookings',null, null, $region,null,'1,2,3')['revenue']);

                if($currencyID == 1 ){
                     $pRate = $pr->getPRateByRegionAndYear($con,array($region), array($year)); 

                     $totalCurrentTarget[$m] = floatval($this->getValueByMonth($con,$salesRep,$year,$value,$month[$m],'target',null, null, $region,null,'1,2,3')['revenue'])*$pRate;
                }else{
                    $pRate = 1;

                     $totalCurrentTarget[$m] = floatval($this->getValueByMonth($con,$salesRep,$year,$value,$month[$m],'target',null, null, $region,null,'1,2,3')['revenue'])*$pRate;
                }  

                 if($currencyID == 1 ){
                    $pRate = 1;
                }else{
                    $pRate = $pr->getPRateByRegionAndYear($con,array($region), array($year));    
                }                           
               

                $payTvForecast[$c] = $this->addFcstWithBooking($currentPayTvBookings[$c],$payTvForecast[$c],$monthPayTvForecast[$c]);
                $digitalForecast[$c] = $this->addFcstWithBooking($currentDigitalBookings[$c],$digitalForecast[$c],$monthDigitalForecast[$c]);

                $forecast[$c][$m] = $payTvForecast[$c][$m] + $digitalForecast[$c][$m];

                $pending[$c][$m] = ($forecast[$c][$m]) - ($currentBookings[$c][$m]);
                if ($pending[$c][$m] < 0) {
                    $pending[$c][$m] = 0;
                }
                
                $totalCurrentPayTvBookings[$m] = ($this->getValueByMonth($con,$salesRep,$year,$value,$month[$m],'bookings',null,null, $region,'pay tv','1,2,3')['revenue']);           
                $totalCurrentDigitalBookings[$m] = ($this->getValueByMonth($con,$salesRep,$year,$value,$month[$m],'bookings',null,null, $region,'digital','1,2,3')['revenue']);   
            }

           $pivot[$c] = array('currentBookings' => $this->addQuartersAndTotal($currentBookings[$c]),'previousBookings' => $this->addQuartersAndTotal($previousBookings[$c]), 'currentTarget' => $this->addQuartersAndTotal($currentTarget[$c]), 'payTvForecast' => $this->addQuartersAndTotal($payTvForecast[$c]), 'digitalForecast' => $this->addQuartersAndTotal($digitalForecast[$c]), 'currentDigitalBookings' => $this->addQuartersAndTotal($currentDigitalBookings[$c]), 'currentPayTvBookings' => $this->addQuartersAndTotal($currentPayTvBookings[$c]), 'forecast' => $this->addQuartersAndTotal($forecast[$c]), 'pending' => $this->addQuartersAndTotal($pending[$c])); 

        

            
        }

        $totalPayTvForecast = $this->addFcstWithBooking($totalCurrentPayTvBookings,$totalPayTvForecast,$totalMonthPayTvForecast);

        $totalDigitalForecast = $this->addFcstWithBooking($totalCurrentDigitalBookings,$totalDigitalForecast,$totalMonthDigitalForecast);
        //var_dump($totalMonthPayTvForecast);
        for ($m=0; $m <sizeof($month) ; $m++) {  //this one is to the months
            
            $totalForecast[$m] = ($totalPayTvForecast[$m] + $totalDigitalForecast[$m]);

            $totalPending[$m] = ($totalForecast[$m]) - ($totalCurrentBookings[$m]);
            if ($totalPending[$m] < 0) {
                $totalPending[$m] = 0;
            }
        }


        $pivotTotal = array('currentBookings' => $this->addQuartersAndTotal($totalCurrentBookings),'previousBookings' => $this->addQuartersAndTotal($totalPreviousBookings), 'currentTarget' => $this->addQuartersAndTotal($totalCurrentTarget), 'payTvForecast' => $this->addQuartersAndTotal($totalPayTvForecast), 'digitalForecast' => $this->addQuartersAndTotal($totalDigitalForecast), 'currentDigitalBookings' => $this->addQuartersAndTotal($totalCurrentDigitalBookings), 'currentPayTvBookings' => $this->addQuartersAndTotal($totalCurrentPayTvBookings), 'forecast' => $this->addQuartersAndTotal($totalForecast), 'pending' => $this->addQuartersAndTotal($totalPending));  
        
       

       $table = array('companyValues' => $pivot, 'total' => $pivotTotal);

       // var_dump($table['total']);
       return $table;
    }



    //THIS FUNCTION MAKE ALL THE CLIENTS TABLE TO PASS TO FRONT
    public function makeClientsTable(Object $con, int $salesRep, Object $pr, int $year, int $pYear, int $region, int $currencyID, string $value,$cDate,$lastMonday){
        $sql = new sql();
        $month = 0;
        $company = array('1','2','3');
        $month = array('1','2','3','4','5','6','7','8','9','10','11','12');

        $companyView = array('wm','dc','spt');
        
        if($currencyID == 1){
            $pRate = 1;
        }else{
            $pRate = $pr->getPRateByRegionAndYear($con,array($region), array($year));    
        }
         
        $newClient = $this->getSalesRepByClient($salesRep,$con, $sql);

        $repInfo = $this->getClientByRep($con, $salesRep, $region, $year, $pYear);

        $clientsMonthly = $this->getMonthlyClients($salesRep,$con, $sql,$cDate,$lastMonday);
        //var_dump($clientsMonthly);
        //var_dump($newClient);
        if ($clientsMonthly != null && $newClient != null) {
            //var_dump('ali');
            $temp = array_merge($clientsMonthly,$newClient);
            $clients = array_merge($repInfo,$temp);
            $clients = array_unique($clients,SORT_REGULAR);
            $clients = array_values($clients);
        
        }elseif ($clientsMonthly != null) {
       // var_dump('aki');
            $clients = array_merge($repInfo,$clientsMonthly);
            $clients = array_unique($clients,SORT_REGULAR);
            $clients = array_values($clients);
        
        }elseif ($newClient != null) {
        
            $clients = array_merge($repInfo,$newClient);
            $clients = array_unique($clients,SORT_REGULAR);
            $clients = array_values($clients);
        
        }else{
        
            $clients = $repInfo;
        
        }
        
        //var_dump($clients);
        //$clients = array_unique($clients,SORT_REGULAR);
        //$clients = array_values($clients);

        //check if exists forecast for this rep in database
        //var_dump($clients);
        for ($a=0; $a <sizeof($clients) ; $a++) { //this for is to make the interactons for all clients of this rep 
            for ($c=0; $c <sizeof($company); $c++) { //this for is to make the interactons for the 3 companies
                $check = $this->checkForecast($con, $salesRep,$clients[$a]['clientID'],$clients[$a]['agencyID'],$company[$c], 'pay tv');
                for ($m=0; $m <sizeof($month) ; $m++) {  //this one is to the months
                    //var_dump($month);
                    $currentBookings[$a][$c][$m] = ($this->getValueByMonth($con,$salesRep,$year,$value,$month[$m],'bookings',$clients[$a]['clientID'],$clients[$a]['agencyID'], $region,null,$company[$c])['revenue']);  
                    $previousBookings[$a][$c][$m] = ($this->getValueByMonth($con,$salesRep,$pYear,$value,$month[$m],'bookings',$clients[$a]['clientID'],$clients[$a]['agencyID'], $region,null,$company[$c])['revenue']);
                    if($check != false){
                        $payTvForecast[$a][$c][$m] = ($this->getValueByMonth($con,$salesRep,$year,$value,$month[$m],'forecast',$clients[$a]['clientID'],$clients[$a]['agencyID'], $region,'pay tv', $company[$c])['revenue']);
                        $digitalForecast[$a][$c][$m] = ($this->getValueByMonth($con,$salesRep,$year,$value,$month[$m],'forecast',$clients[$a]['clientID'],$clients[$a]['agencyID'], $region,'digital',$company[$c])['revenue']);
                    }else{
                        $payTvForecast[$a][$c][$m] = ($this->getValueByMonth($con,$salesRep,$year,$value,$month[$m],'tRex',$clients[$a]['clientID'],$clients[$a]['agencyID'], $region,'pay tv', $company[$c])['revenue']);    
                        $digitalForecast[$a][$c][$m] = ($this->getValueByMonth($con,$salesRep,$year,$value,$month[$m],'tRex',$clients[$a]['clientID'],$clients[$a]['agencyID'], $region,'digital',$company[$c])['revenue']);
                    }                  
                    $currentPayTvBookings[$a][$c][$m] = ($this->getValueByMonth($con,$salesRep,$year,$value,$month[$m],'bookings',$clients[$a]['clientID'],$clients[$a]['agencyID'], $region,'pay tv',$company[$c])['revenue']);
                    $currentDigitalBookings[$a][$c][$m] = ($this->getValueByMonth($con,$salesRep,$year,$value,$month[$m],'bookings',$clients[$a]['clientID'],$clients[$a]['agencyID'], $region,'digital',$company[$c])['revenue']);
                    $currentTarget[$a][$c][$m] = 0;

                    $probabilityMonth[$a][$m] = $this->getProbabilityMonthly($con,$clients[$a]['clientID'],$clients[$a]['agencyID'],$salesRep,$month[$m]);
                    $probabilityNewMonth[$a][$m] = $this->getProbabilityNewClientMonthly($con,$clients[$a]['clientID'],$clients[$a]['agencyID'],$salesRep,$month[$m]);

                    $monthPayTvForecast[$a][$c][$m] = ($this->getValueByMonth($con,$salesRep,$year,$value,$month[$m],'monthly',$clients[$a]['clientID'],$clients[$a]['agencyID'], $region,'pay tv', $company[$c])['revenue'])*($probabilityMonth[$a][$m][0]['probability']/100);                 
                    $monthDigitalForecast[$a][$c][$m] = ($this->getValueByMonth($con,$salesRep,$year,$value,$month[$m],'monthly',$clients[$a]['clientID'],$clients[$a]['agencyID'], $region,'digital',$company[$c])['revenue'])*($probabilityMonth[$a][$m][0]['probability']/100);

                    $newMonthPayTvForecast[$a][$c][$m] = ($this->getValueByMonth($con,$salesRep,$year,$value,$month[$m],'newMonthly',$clients[$a]['clientID'],$clients[$a]['agencyID'], $region,'pay tv', $companyView[$c])['revenue'])*($probabilityNewMonth[$a][$m][0]['probability']/100);                 
                    $newMonthDigitalForecast[$a][$c][$m] = ($this->getValueByMonth($con,$salesRep,$year,$value,$month[$m],'newMonthly',$clients[$a]['clientID'],$clients[$a]['agencyID'], $region,'digital',$companyView[$c])['revenue'])*($probabilityNewMonth[$a][$m][0]['probability']/100);

                    $monthPayTvForecast[$a][$c][$m] = $monthPayTvForecast[$a][$c][$m] + $newMonthPayTvForecast[$a][$c][$m];
                    $monthDigitalForecast[$a][$c][$m] = $monthDigitalForecast[$a][$c][$m] + $newMonthDigitalForecast[$a][$c][$m];
                }
                
                $payTvForecast[$a][$c] = $this->addFcstWithBooking($currentPayTvBookings[$a][$c],$payTvForecast[$a][$c],$monthPayTvForecast[$a][$c]);
                $digitalForecast[$a][$c] = $this->addFcstWithBooking($currentDigitalBookings[$a][$c],$digitalForecast[$a][$c],$monthDigitalForecast[$a][$c]);

                for ($m=0; $m <sizeof($month) ; $m++) {  //this one is to the months
                    $forecast[$a][$c][$m] = $payTvForecast[$a][$c][$m] + $digitalForecast[$a][$c][$m];

                    $pending[$a][$c][$m] = ($forecast[$a][$c][$m]) - ($currentBookings[$a][$c][$m]);
                    if ($pending[$a][$c][$m] < 0) {
                        $pending[$a][$c][$m] = 0;
                    }
                }
                $pivot[$a][$c] = array('currentBookings' => $this->addQuartersAndTotal($currentBookings[$a][$c]),'previousBookings' => $this->addQuartersAndTotal($previousBookings[$a][$c]), 'payTvForecast' => $this->addQuartersAndTotal($payTvForecast[$a][$c]), 'digitalForecast' => $this->addQuartersAndTotal($digitalForecast[$a][$c]), 'currentTarget' => $this->addQuartersAndTotal($currentTarget[$a][$c]), 'currentDigitalBookings' => $currentDigitalBookings[$a][$c], 'currentPayTvBookings' => $currentPayTvBookings[$a][$c], 'payTvForecastC' => $payTvForecast[$a][$c], 'digitalForecastC' => $digitalForecast[$a][$c], 'currentDigitalBookings' => $this->addQuartersAndTotal($currentDigitalBookings[$a][$c]), 'currentPayTvBookings' => $this->addQuartersAndTotal($currentPayTvBookings[$a][$c]), 'forecast' => $this->addQuartersAndTotal($forecast[$a][$c]), 'pending' => $this->addQuartersAndTotal($pending[$a][$c]), 'currentDigitalBookingsC' => ($currentDigitalBookings[$a][$c]),  'currentPayTvBookingsC' => ($currentPayTvBookings[$a][$c]),'monthPayTvForecast' => $monthPayTvForecast[$a][$c], 'monthDigitalForecast' => $monthDigitalForecast[$a][$c]);
                //var_dump($pivot[$a][$c]['currentDigitalBookingsC']);
            } 

            for ($m=0; $m <sizeof($month) ; $m++) { 
            
                $totalCurrentBookings[$a][$m] = ($this->getValueByMonth($con,$salesRep,$year,$value,$month[$m],'bookings',$clients[$a]['clientID'],$clients[$a]['agencyID'], $region,null,'1,2,3')['revenue']);           
                $totalPreviousBookings[$a][$m] = ($this->getValueByMonth($con,$salesRep,$pYear,$value,$month[$m],'bookings',$clients[$a]['clientID'],$clients[$a]['agencyID'], $region,null,'1,2,3')['revenue']);
                $totalCurrentTarget[$a][$m] = 0;
                if($check != false){ 
                    $totalPayTvForecast[$a][$m] = ($this->getValueByMonth($con,$salesRep,$year,$value,$month[$m],'forecast',$clients[$a]['clientID'],$clients[$a]['agencyID'], $region,'pay tv', '1,2,3')['revenue']);
                    $totalDigitalForecast[$a][$m] = ($this->getValueByMonth($con,$salesRep,$year,$value,$month[$m],'forecast',$clients[$a]['clientID'],$clients[$a]['agencyID'], $region,'digital','1,2,3')['revenue']);
                }else{
                    $totalPayTvForecast[$a][$m] = ($this->getValueByMonth($con,$salesRep,$year,$value,$month[$m],'tRex',$clients[$a]['clientID'],$clients[$a]['agencyID'], $region,'pay tv', '1,2,3')['revenue']);
                    $totalDigitalForecast[$a][$m] = ($this->getValueByMonth($con,$salesRep,$year,$value,$month[$m],'tRex',$clients[$a]['clientID'],$clients[$a]['agencyID'], $region,'digital','1,2,3')['revenue']);
                }   
                
                $totalCurrentPayTvBookings[$a][$m] = ($this->getValueByMonth($con,$salesRep,$year,$value,$month[$m],'bookings',$clients[$a]['clientID'],$clients[$a]['agencyID'], $region,'pay tv','1,2,3')['revenue']);           
                $totalCurrentDigitalBookings[$a][$m] = ($this->getValueByMonth($con,$salesRep,$year,$value,$month[$m],'bookings',$clients[$a]['clientID'],$clients[$a]['agencyID'], $region,'digital','1,2,3')['revenue']);

                $totalMonthPayTvForecast[$a][$m] = ($this->getValueByMonth($con,$salesRep,$year,$value,$month[$m],'monthly',$clients[$a]['clientID'],$clients[$a]['agencyID'],$region,'pay tv', '1,2,3')['revenue'])*($probabilityMonth[$a][$m][0]['probability']/100);
                $totalMonthDigitalForecast[$a][$m] = ($this->getValueByMonth($con,$salesRep,$year,$value,$month[$m],'monthly',$clients[$a]['clientID'],$clients[$a]['agencyID'],$region,'digital','1,2,3')['revenue'])*($probabilityMonth[$a][$m][0]['probability']/100);

                $totalNewMonthPayTvD[$a][$m] = ($this->getValueByMonth($con,$salesRep,$year,$value,$month[$m],'newMonthly',$clients[$a]['clientID'],$clients[$a]['agencyID'],$region,'pay tv','dc')['revenue'])*($probabilityNewMonth[$a][$m][0]['probability']/100);   

                $totalNewMonthPayTvW[$a][$m] = (($this->getValueByMonth($con,$salesRep,$year,$value,$month[$m],'newMonthly',$clients[$a]['clientID'],$clients[$a]['agencyID'],$region,'pay tv','wm')['revenue'])*($probabilityNewMonth[$a][$m][0]['probability']/100));   

                $totalNewMonthPayTvS[$a][$m] = (($this->getValueByMonth($con,$salesRep,$year,$value,$month[$m],'newMonthly',$clients[$a]['clientID'],$clients[$a]['agencyID'],$region,'pay tv','spt')['revenue'])*($probabilityNewMonth[$a][$m][0]['probability']/100));   
                    
                $totalNewMonthDigitalD[$a][$m] = ($this->getValueByMonth($con,$salesRep,$year,$value,$month[$m],'newMonthly',$clients[$a]['clientID'],$clients[$a]['agencyID'],$region,'digital','dc')['revenue'])*($probabilityNewMonth[$a][$m][0]['probability']/100);   

                $totalNewMonthDigitalW[$a][$m] = (($this->getValueByMonth($con,$salesRep,$year,$value,$month[$m],'newMonthly',$clients[$a]['clientID'],$clients[$a]['agencyID'],$region,'digital','wm')['revenue']))*($probabilityNewMonth[$a][$m][0]['probability']/100);   

                $totalNewMonthDigitalS[$a][$m] = (($this->getValueByMonth($con,$salesRep,$year,$value,$month[$m],'newMonthly',$clients[$a]['clientID'],$clients[$a]['agencyID'],$region,'digital','spt')['revenue'])*($probabilityNewMonth[$a][$m][0]['probability']/100));

                
                //var_dump(($this->getValueByMonth($con,$salesRep,$year,$value,$month[$m],'newMonthly',null, null, $region,'pay tv','wm')));
            
                $totalMonthPayTvForecast[$a][$m] = ($totalMonthPayTvForecast[$a][$m] + $totalNewMonthPayTvD[$a][$m] + $totalNewMonthPayTvW[$a][$m] + $totalNewMonthPayTvS[$a][$m]);
                $totalMonthDigitalForecast[$a][$m] = $totalMonthDigitalForecast[$a][$m] + $totalNewMonthDigitalD[$a][$m] + $totalNewMonthDigitalW[$a][$m] + $totalNewMonthDigitalS[$a][$m];
                 
            }              
            $probability[$a] = $this->getProbability($con,$clients[$a]['clientID'],$clients[$a]['agencyID'],$salesRep);
            //var_dump($probability[$a]);
            $totalPayTvForecast[$a] = $this->addFcstWithBooking($totalCurrentPayTvBookings[$a],$totalPayTvForecast[$a],$totalMonthPayTvForecast[$a]);
            $totalDigitalForecast[$a] = $this->addFcstWithBooking($totalCurrentDigitalBookings[$a],$totalDigitalForecast[$a],$totalMonthDigitalForecast[$a]);

            for ($m=0; $m <sizeof($month) ; $m++) {  //this one is to the months
            
                $totalForecast[$a][$m] = ($totalPayTvForecast[$a][$m] + $totalDigitalForecast[$a][$m]);
                $totalPending[$a][$m] = ($totalForecast[$a][$m]) - ($totalCurrentBookings[$a][$m]);
                if ($totalPending[$a][$m] < 0) {
                    $totalPending[$a][$m] = 0;
                }
            }


            $totalPivot[$a] = array('currentBookings' => $this->addQuartersAndTotal($totalCurrentBookings[$a]),'previousBookings' => $this->addQuartersAndTotal($totalPreviousBookings[$a]), 'currentTarget' => $this->addQuartersAndTotal($totalCurrentTarget[$a]), 'payTvForecast' => $this->addQuartersAndTotal($totalPayTvForecast[$a]), 'digitalForecast' => $this->addQuartersAndTotal($totalDigitalForecast[$a]), 'currentDigitalBookings' => $this->addQuartersAndTotal($totalCurrentDigitalBookings[$a]), 'currentPayTvBookings' => $this->addQuartersAndTotal($totalCurrentPayTvBookings[$a]),'forecast' => $this->addQuartersAndTotal($totalForecast[$a]), 'pending' => $this->addQuartersAndTotal($totalPending[$a]), 'payTvForecastC' => ($totalPayTvForecast[$a]), 'digitalForecastC' => ($totalDigitalForecast[$a]), 'currentDigitalBookingsC' => ($totalCurrentDigitalBookings[$a]),  'totalCurrentPayTvBookingsC' => ($totalCurrentPayTvBookings[$a]), 'monthPayTvForecast' => $totalMonthPayTvForecast[$a], 'monthDigitalForecast' => $totalMonthDigitalForecast[$a]);
       
            $clientInfo[$a] = array('clientName' => $clients[$a]['clientName'], 'clientID' => $clients[$a]['clientID'],'agencyName' => $clients[$a]['agencyName'],'agencyID' => $clients[$a]['agencyID'], 'probability' => $probability[$a]);
        }
       
        $table = array('clientInfo' => $clientInfo,'companyValues' => $pivot,'total' => $totalPivot,'probabilityMonth' => $probabilityMonth, 'probabilityNewMonth' => $probabilityNewMonth);

        
       //var_dump($monthPayTvForecast);
       // var_dump($table['clientInfo']);
       return $table;
    }

    public function getMonthlyClients(String $salesRep, Object $con, Object $sql,$cDate,$lastMonday){
        
        $year = (int)date("Y");
        $pYear = $year-1;
        if ($cDate >= $lastMonday) {
            $months =  array(date('n')+1,date('n')+2,date('n')+3);
        }else{
            $months =  array(date('n'),date('n')+1,date('n')+2);
        }       

         $selectClient = "SELECT distinct  c.ID as clientID, c.name as clientName, a.ID as agencyID, a.name as agencyName
                            from new_clients_fcst f
                            left join sales_rep sr on sr.ID = f.sales_rep_id 
                            LEFT JOIN client c ON c.id = f.client_id
                            LEFT JOIN agency a ON a.id = f.agency_id
                            WHERE (sr.ID IN ($salesRep))
                            AND (f.month IN ($months[0],$months[1],$months[2]))

                            ";
                    // var_dump($selectClient);
            $resultClient = $con->query($selectClient);
            $from = array('clientName', 'clientID','agencyName','agencyID');
            $client = $sql->fetch($resultClient, $from, $from);
            //var_dump($client);

            return $client;
            
    }

    //make a list of clients for the front-end button to add a new client basis on existing clients
    public function listOFClients(Object $con, int $year){
        $sql = new sql();
        $year = (int)date("Y");
        $pYear = $year-1;
        $ppYear = $year-2;

        $select = "SELECT DISTINCT c.ID AS id ,c.name as client, a.ID as aID, a.name as agency
                    FROM wbd w
                    left join client c on c.ID = w.client_id
                    left join agency a on a.ID = w.agency_id
                    WHERE c.client_group_id = 1 
                    and w.year in ($year,$pYear)
                    ORDER BY c.name ASC";
        //var_dump($select);
        $from = array('id','client','aID','agency');
        $selectQuery = $con->query($select);
        $client = $sql->fetch($selectQuery, $from, $from);



        $selectPipe = "SELECT DISTINCT c.ID AS id ,c.name as client, a.ID as aID, a.name as agency
                    FROM pipeline p
                    left join client c on c.ID = p.client
                    left join agency a on a.ID = p.agency
                    where p.client != 0
                    and p.agency != 0
                    ";
        $fromPipe = array('id','client','aID','agency');
        $queryPipe = $con->query($selectPipe);
        $clientPipe = $sql->fetch($queryPipe, $fromPipe, $fromPipe);
        //var_dump($clientPipe);

        if ($clientPipe ) {
            // code...
        }
        array_merge($client,$clientPipe);
        array_unique($client, SORT_REGULAR);
        $client = $client;


        return $client;
    }

    //this function make the inclusion of new clients to make forecast
    public function newClientInclusion(Object $con, String $salesRep, String $client,String $agency){
        $updateTime = date("Y-m-d");
        $month = date('n');
        $year = date('Y');
        $insertQuery = "INSERT INTO  ae_new_clients
                        SET created_date = '$updateTime',
                        sales_rep_id = $salesRep,
                        client_id = $client,
                        agency_id = $agency
                        ";
        //var_dump($insertQuery);
        $resultInsertQuery = $con->query($insertQuery);

    }

    public function getSalesRepByClient(String $salesRep, Object $con, Object $sql){
        
        $year = (int)date("Y");
        $pYear = $year-1;
        $ppYear = $year-2;

         $selectClient = "SELECT distinct  c.id as id, ag.id as agency 
                            from ae_new_clients a
                            left join sales_rep sr on sr.ID = a.sales_rep_id 
                            left join client c on c.ID = a.client_id 
                            left join agency ag on ag.ID = a.agency_id
                            WHERE (sr.ID IN ($salesRep))";
                        //var_dump($selectClient);
            $resultClient = $con->query($selectClient);
            $from = array('id','agency');
            $client = $sql->fetch($resultClient, $from, $from);
           // var_dump($client);

            if ($client != null) {
                for ($c=0; $c < sizeof($client); $c++) {
                    $tmp1[] = $client[$c]['id']; 
                    $tmp2[] = $client[$c]['agency']; 
                    
                    $queryClient[$c] = "SELECT distinct sr.id as srID, sr.name as srName, a.id as agencyID, a.name as agencyName, c.id as clientID, c.name as clientName from  wbd cm 
                           left join agency a on a.ID = cm.agency_id 
                           left join client c on c.ID = cm.client_id 
                           left join sales_rep sr on sr.ID = cm.current_sales_rep_id  
                           where c.id in ($tmp1[$c])
                           and a.id in ($tmp2[$c])
                           and cm.`year` in ($year,$pYear)
                           order by c.name asc";
                    //echo "<pre>$queryClient[$c]</pre>";
                    $result[$c] = $con->query($queryClient[$c]);
                    $from = array('clientName', 'clientID','agencyName','agencyID');
                    $tmp[] = $sql->fetch($result[$c], $from, $from);
                }
                //var_dump($tmp);
                for ($x=0; $x <sizeof($tmp) ; $x++) { 
                   if ($tmp != false) {
                        $valueClient[] = $tmp[$x][0];
                    }else{
                        $valueClient = null;
                    }
                } 
               // var_dump($valueClient);
                return $valueClient;
            }else{
                $valueClient = null;

                return $valueClient;
            }
            //var_dump($valueClient);
    }

    //THIS FUNCTION SAVE OR UPDATE THE FORECAST MADE BY THE SALES REP
    public function saveForecast(Object $con, int $client, int $agency, int $year, String $value, String $company, String $month, int $salesRep, String $platform, string $forecastValue, int $currency, int $probability){
        $sql = new sql();
        $selectQuery = "SELECT agency_id AS agency, client_id AS client
                        FROM ae_forecast
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
        //var_dump($selectResultQuery);
       // var_dump($resultSelect);
        if ($resultSelect == false){
             //var_dump($forecastValue);
            mysqli_query($con,"INSERT INTO  ae_forecast
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
            $update = "UPDATE ae_forecast
                        SET revenue = '$forecastValue', success_probability = $probability
                        WHERE sales_rep_id = $salesRep
                        AND client_id = $client
                        AND agency_id = $agency
                        AND month = $month
                        AND company_id = $company
                        AND currency = $currency
                        AND value = '$value'
                        AND year = $year 
                        AND platform = '$platform'";

            $query = $con->query($update);

             //var_dump($update);
           
        }
    }

    //THIS FUNCTION GET THE VALUE BY MONTH AND COMPANY BY CLIENT AND REP
    public function getValueByMonth(Object $con, int $salesRep, int $year, string $value, int $month, string $table, int $client=null, int $agency=null, int $regionID, string $platform=null, string $company=null){
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
                                WHERE w.current_sales_rep_id = $salesRep
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
                                WHERE w.current_sales_rep_id = $salesRep
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
                                AND b.type = 'Linear'
                                AND w.current_sales_rep_id = $salesRep
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
                                AND b.type = 'Non-Linear'
                                AND w.current_sales_rep_id = $salesRep
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
                            AND w.current_sales_rep_id = $salesRep
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
                            WHERE pbs.sales_rep_id = $salesRep
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
                                FROM ae_forecast f                                
                                WHERE f.sales_rep_id = $salesRep
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
                                FROM ae_forecast f
                                LEFT JOIN client c ON c.ID = f.client_id
                                LEFT JOIN agency a ON a.ID = f.agency_id
                                WHERE f.sales_rep_id = $salesRep
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
                                WHERE f.sales_rep_id = $salesRep
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
                                WHERE f.sales_rep_id = $salesRep
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

            case 'monthly':
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
        case 'newMonthly':
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
        }

       
    }
    //this function check if exists forecast in database for the current rep
    public function checkForecast(Object $con, int $salesRep,$client=null,$agency=null,$company=null,$platform=null){
        $sql = new sql();

        if ($client == null) {
            $selectQuery = "SELECT sales_rep_id as salesRep
                        FROM ae_forecast
                        WHERE sales_rep_id = $salesRep 
                        ";
        }else{
            $selectQuery = "SELECT sales_rep_id as salesRep
                        FROM ae_forecast
                        WHERE sales_rep_id = $salesRep
                        AND client_id = $client
                        AND agency_id = $agency
                        AND (company_id IN ($company)) 
                        AND (platform = '$platform') 
                        ";
        }
        
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
    public function getProbability(Object $con, int $client, int $agency, int $salesRep){
        $sql = new sql();

        $selectQuery = "SELECT DISTINCT success_probability AS probability
                        FROM ae_forecast
                        WHERE sales_rep_id = $salesRep
                        AND client_id = $client
                        AND agency_id = $agency";
       //var_dump($selectQuery);
        $from = array('probability');
        $selectResultQuery = $con->query($selectQuery);
        $resultSelect = $sql->fetch($selectResultQuery, $from, $from);

        if ($resultSelect == false) {
            $resultSelect[0]['probability'] = '100';
        }

        return $resultSelect;
    }
    //THIS FUNCTION GET ALL THE CLIENTS FOR THE SELECTED SALES REP
    public function getClientByRep(Object $con,int $salesRep, int $region, int $year, int $pYear){
        $sql = new sql();
        //var_dump($salesRep);
        if ($salesRep == '287') {
            $selectWBD = "SELECT DISTINCT c.name as clientName, c.ID as clientID, a.name as agencyName, a.ID as agencyID
                    FROM wbd w
                    LEFT JOIN client c ON c.ID = w.client_id
                    LEFT JOIN agency a ON a.ID = w.agency_id
                    WHERE (w.current_sales_rep_id = \"$salesRep\" )
                    AND w.year IN (\"$year\")   
                    AND w.gross_value > 0                 
                    ORDER BY 1
                    ";    
        }else{
            $selectWBD = "SELECT DISTINCT c.name as clientName, c.ID as clientID, a.name as agencyName, a.ID as agencyID
                    FROM wbd w
                    LEFT JOIN client c ON c.ID = w.client_id
                    LEFT JOIN agency a ON a.ID = w.agency_id
                    WHERE (w.current_sales_rep_id = \"$salesRep\" )
                    AND w.year IN (\"$year\",\"$pYear\")  
                    ORDER BY 1
                    ";    
        }
        $queryWBD = $con->query($selectWBD);
        $fromWBD = array('clientName', 'clientID','agencyName','agencyID');
        $resultWBD = $sql->fetch($queryWBD,$fromWBD,$fromWBD);
        //var_dump($resultWBD);

        $selectForecast = "SELECT DISTINCT c.name as clientName, c.ID as clientID, a.name as agencyName, a.ID as agencyID
                    FROM ae_forecast w
                    LEFT JOIN client c ON c.ID = w.client_id
                    LEFT JOIN agency a ON a.ID = w.agency_id
                    WHERE (w.sales_rep_id = \"$salesRep\" )                  
                    ORDER BY 1
                    ";
        $queryForecast = $con->query($selectForecast);
        $fromForecast = array('clientName', 'clientID','agencyName','agencyID');
        $resultForecast = $sql->fetch($queryForecast,$fromForecast,$fromForecast);
        //$resultForecast = false;
        //var_dump($resultForecast);
        if ($resultForecast != false) {
            $result = array_merge($resultWBD,$resultForecast);
            $result = array_unique($result, SORT_REGULAR);
            $result = array_values($result);
        }else{
            $result = array_values($resultWBD);

        }
        //var_dump($result);
        return $result;
    }

    public function getProbabilityMonthly(Object $con, int $client, int $agency, int $salesRep,$month){
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
        //var_dump($resultSelect);
        return $resultSelect;
    }

     public function getProbabilityNewClientMonthly(Object $con, String $client, String $agency, int $salesRep,$month){
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
    public function addFcstWithBooking(Array $booking, Array $fcst, Array $monthly){

        $cDate = date('d/m/Y');
        $cMonth = date('M');
        $cYear = date('Y');
        $lastMonday = date('d/m/Y',strtotime("last Monday of $cMonth $cYear"));
        
        if ($cDate >= $lastMonday) {
            $date = intval(date('n'));
            $nDate = $date+1;
            $nNDate = $date + 2;
        }else{
            $date = intval(date('n')-1);
            $nDate = $date+1;
            $nNDate = $date + 2;
       
        }  
        
        //var_dump($nDate);
        for ($c=0; $c < sizeof($booking); $c++) { 
            if ($c<$date) {
                $sum[$c] = $booking[$c];
            }elseif($c == $date || $c == $nDate || $c == $nNDate){
               // var_dump($c);
                $sum[$c] = $monthly[$c];
            }else{
                //var_dump($c);
                $sum[$c] = $fcst[$c];
            }
        }
        
        //var_dump($sum);
        return $sum;
    }

   
}
