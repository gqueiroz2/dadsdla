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

class property extends pAndR{
    
    public function makeRepTable(Object $con, Int $cYear, Int $pYear, Int $salesRepID, String $value){
        $table = 0;
        $intMonth = array('1','2','3','4','5','6','7','8','9','10','11','12');
        $property = $this->getPropertyByRep($con,$salesRepID,$cYear,$pYear);
        
        $client = $this->getClientByProperty($con,null,$cYear,$pYear,$salesRepID);        
        
       /* for ($c=0; $c <sizeof($client) ; $c++) { 
            for ($m=0; $m <sizeof($intMonth); $m++) { 
                $digitalCurrentBookings[$c][$m] = $this->getValue($con, $client[$c]['clientID'],$client[$c]['agencyID'],$client[$c]['property'], $cYear, $salesRepID,$value,'bookings',$client[$c]['cluster'],$intMonth[$m],'Non-Linear');
                $payTvCurrentBookings[$c][$m] = $this->getValue($con, $client[$c]['clientID'],$client[$c]['agencyID'],$client[$c]['property'], $cYear, $salesRepID,$value,'bookings',$client[$c]['cluster'],$intMonth[$m],'Linear');    
                $digitalPreviousBookings[$c][$m] = $this->getValue($con, $client[$c]['clientID'],$client[$c]['agencyID'],$client[$c]['property'], $pYear, $salesRepID,$value,'bookings',$client[$c]['cluster'],$intMonth[$m],'Non-Linear');
                $payTvPreviousBookings[$c][$m] = $this->getValue($con, $client[$c]['clientID'],$client[$c]['agencyID'],$client[$c]['property'], $pYear, $salesRepID,$value,'bookings',$client[$c]['cluster'],$intMonth[$m],'Linear');        
            }
            
        }*/

        //var_dump($currentBookings);
       // var_dump($previousBookings);
        //$table = array('currentPayTv' => $payTvCurrentBookings, 'previousPayTv' => $payTvPreviousBookings,'currentDigital' => $digitalCurrentBookings, 'previousDigital' => $digitalPreviousBookings, 'property' => $property, 'client' => $client);

        //return $table;
    }

    public function getValue(Object $con, String $client,String $agency,String $property, Int $year, Int $salesRep, String $value, String $table, String $cluster, Int $month, String $platform){
        $sql = new sql();
        
        if ($table == 'bookings') {
            $value .= "_value";    
        }elseif ($table == 'target') {
            strtoupper($value);
        }
       // var_dump($brand);
        switch($table){
            case 'bookings':
                $select = "SELECT SUM(w.$value) AS revenue
                        FROM wbd w
                        LEFT JOIN sales_rep s ON s.ID = w.current_sales_rep_id
                        LEFT JOIN client c ON c.ID =w.client_id
                        LEFT JOIN agency a ON a.ID = w.agency_id
                        LEFT JOIN sales_rep sr ON sr.ID = w.current_sales_rep_id
                        LEFT JOIN brand b ON b.ID = w.brand_id
                        LEFT JOIN clusters cl ON cl.ID = b.cluster_id
                        LEFT JOIN brand_group bg ON bg.ID = b.brand_group_id
                        WHERE w.year IN ($year)
                        AND c.ID = $client
                        AND a.ID = $agency
                        AND w.property = '$property'
                        AND sr.ID = $salesRep
                        AND cl.name = '$cluster'
                        AND w.month = $month
                        AND b.type = '$platform'
                        ";
                
                //echo "<pre>$select</pre>";
                $query = $con->query($select);
                $from = 'revenue';
                $result = $sql->fetchSUM($query,$from);        
                break;
            
        }
        if ($result['revenue'] == null) {
            $result['revenue'] = 0.0;
        }

        return $result;
    }

    public function getClientByProperty(Object $con, String $property=null, Int $cYear, Int $pYear, Int $salesRep){
        $sql = new sql();

        $selectWBD = "SELECT DISTINCT c.id AS clientID, c.name as clientName, a.ID AS agencyID, a.name AS agencyName, sr.name as salesRep, w.property as property, bg.abv as company, cl.name AS cluster
                    FROM wbd w
                    LEFT JOIN sales_rep sr ON sr.ID = w.current_sales_rep_id
                    LEFT JOIN client c ON c.ID =w.client_id
                    LEFT JOIN agency a ON a.ID = w.agency_id     
                    LEFT JOIN brand b ON b.ID = w.brand_id               
                    LEFT JOIN clusters cl ON cl.ID = b.cluster_id
                    LEFT JOIN brand_group bg ON bg.ID = b.brand_group_id
                    WHERE w.year IN ($cYear)
                    AND w.property != ''
                    AND sr.ID = $salesRep
                    ";
                echo "<pre>$selectWBD</pre>";
        $fromWBD = array('clientID','clientName','agencyID','agencyName','salesRep','property', 'company','cluster');
        $queryWBD = $con->query($selectWBD);
        $resultWBD = $sql->fetch($queryWBD, $fromWBD, $fromWBD);

       /* $selectForecast = "SELECT DISTINCT c.id AS clientID, c.name as clientName, a.ID AS agencyID, a.name AS agencyName, sr.name as salesRep, w.property as property
                    FROM forecast w
                    LEFT JOIN sales_rep sr ON sr.ID = w.current_sales_rep_id
                    LEFT JOIN client c ON c.ID =w.client_id
                    LEFT JOIN agency a ON a.ID = w.agency_id
                    WHERE w.year IN ($cYear,$pYear)
                    AND w.property != ''
                    AND sr.ID = $salesRep
                    ";
                //var_dump($select);
        $fromForecast = array('clientID','clientName','agencyID','agencyName','salesRep','property');
        $queryForecast = $con->query($selectForecast);
        $resultForecast = $sql->fetch($queryForecast, $fromForecast, $fromForecast);

        $result = array_merge($resultWBD,$resultForecast);
        $result = array_unique($result, SORT_REGULAR);
        $result = array_values($result);*/
       // var_dump($result);
        return $resultWBD;
    }

    public function getPropertyByRep(Object $con, Int $salesRep, Int $cYear, Int $pYear){
        $sql = new sql();

        $select = "SELECT DISTINCT w.property AS property
                    FROM wbd w
                    LEFT JOIN sales_rep s ON s.ID = w.current_sales_rep_id
                    WHERE w.year IN ($cYear,$pYear)
                    ";

        $from = array('property');
        $query = $con->query($select);
        $result = $sql->fetch($query, $from, $from);

        for ($p=0; $p <sizeof($result) ; $p++) { 
            if ($result[$p]['property'] == '') {
                unset($result[$p]);
            }
        }

        $result = array_values($result);

        return $result;
    }

    
}
