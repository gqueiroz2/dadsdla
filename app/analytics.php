<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class analytics extends Model{
    
    public function assembler($con,$sql){
        
        $listUsers = $this->listUsers($con,$sql);
    	$list = $this->getInfo($con,$sql);

        $dealWithList = $this->dealWithList($list);  
        
        if($dealWithList){        
            $lastWeekV = sizeof($dealWithList['lastWeek']);

            $allV = sizeof($dealWithList['all']); 

            $last15Days = $this->last15Days($list);
            $last15DaysV = 0;
            for ($l=0; $l < sizeof($last15Days); $l++) { 
                if(!empty($last15Days[$l])){
                    $lastFifteenDaysV[$l] = sizeof($last15Days[$l]);
                }else{
                    $lastFifteenDaysV[$l] = 0;
                }
                $last15DaysV += $lastFifteenDaysV[$l];
            }       

            $lastSevenDays = $this->lastSevenDays($list);


            $lastSevenDaysV = 0;

            for ($l=0; $l < sizeof($lastSevenDays); $l++) { 
                $lastSevenDaysA[$l] = sizeof($lastSevenDays[$l]);
                $lastSevenDaysV += $lastSevenDaysA[$l];
            }

            $lastDay = $this->lastDay($list); 
            $lastDayV = sizeof($lastDay);

            $lastHour = $this->lastHour($list);
            $lastHourV = sizeof($lastHour);

            $all = $list;

            $regions = $this->regions($list);       

            $visitsByRegion = $this->visitsByRegion($regions,$list);

            $rtr = array( 
                        "lastHourV" => $lastHourV,
                        "lastDayV" => $lastDayV,
                        "lastWeekV" => $lastWeekV,
                        "last15DaysV" => $last15DaysV,
                        "allV" => $allV,

                        "last15Days" => $last15Days,
                        "lastFifteenDaysV" => $lastFifteenDaysV,
                        "lastSevenDays" => $lastSevenDays,
                        "lastSevenDaysV" => $lastSevenDaysV,
                        "lastSevenDaysA" => $lastSevenDaysA,
                        "lastDay" => $lastDay,
                        "lastHour" => $lastHour,

                        "regions" => $regions,
                        "visitsByRegion" => $visitsByRegion,

                        "all" => $all
                    );
        }else{
            $rtr = false;
        }

        return $rtr;

    }

    public function visitsByRegion($regions,$list){
        
        for ($r=0; $r < sizeof($regions); $r++) { 
            $listR[$r] = array();
            for ($l=0; $l < sizeof($list); $l++) { 
                if($list[$l]['region'] == $regions[$r]){
                    array_push($listR[$r], $list[$l]);
                }
            }
        }

        for ($i=0; $i < sizeof($listR); $i++) { 
            
            $last15DaysR[$i] = $this->last15Days($listR[$i]);

            $lastSevenDaysR[$i] = $this->lastSevenDays($listR[$i]);

            $lastDayR[$i] = $this->lastDay($listR[$i]); 

            $lastHourR[$i] = $this->lastHour($listR[$i]);
            
        }

        
        for ($l=0; $l < sizeof($last15DaysR); $l++) { 
            $last15DaysVR[$l] = 0;
            for ($m=0; $m < sizeof($last15DaysR[$l]); $m++) { 
                if(!empty($last15DaysR[$l][$m])){
                    $lastFifteenDaysVR[$l] = sizeof($last15DaysR[$l][$m]);
                }else{
                    $lastFifteenDaysVR[$l] = 0;
                }
                $last15DaysVR[$l] += $lastFifteenDaysVR[$l];
            }
        }      

        

        for ($l=0; $l < sizeof($lastSevenDaysR); $l++) { 
            $lastSevenDaysVR[$l] = 0;
            for ($m=0; $m < sizeof($lastSevenDaysR[$l]); $m++) { 
                $lastSevenDaysAR[$l] = sizeof($lastSevenDaysR[$l][$m]);
                $lastSevenDaysVR[$l] += $lastSevenDaysAR[$l];
            }
        }

        for ($l=0; $l < sizeof($lastDayR); $l++) { 
            $lastDayVR[$l] = sizeof($lastDayR[$l]);
            $lastHourVR[$l] = sizeof($lastHourR[$l]);
        }

        $rtr = array( 

                    "listR" => $listR,
                    "last15DaysR" => $last15DaysR ,
                    "lastSevenDaysR" => $lastSevenDaysR,
                    "lastDayR" => $lastDayR ,
                    "lastHourR" => $lastHourR,
                    "lastFifteenDaysVR" => $lastFifteenDaysVR,
                    "last15DaysVR" => $last15DaysVR,
                    "lastSevenDaysVR" => $lastSevenDaysVR,
                    "lastSevenDaysAR" => $lastSevenDaysAR,
                    "lastDayVR" => $lastDayVR ,
                    "lastHourVR" => $lastHourVR
                );

        return ($rtr);

    }

    public function regions($list){

        $regions = array();

        for ($l=0; $l < sizeof($list); $l++) { 
            array_push($regions, $list[$l]['region']);
        }

        $regions = array_values(array_unique($regions));

        return ($regions);

    }

    public function lastHour($list){
        $today = date('Y-m-d');

        $time = date('H:i');

        $lastHour = array();

        for ($l=0; $l < sizeof($list); $l++){ 
            if(( strtotime($today) == strtotime($list[$l]['day']))){
                if( strtotime($list[$l]['hour']) >  strtotime($time) ){
                    array_push($lastHour,$list[$l]);
                    unset($list[$l]);
                    $list = array_values($list);
                }
            }
        }

        return $lastHour;
    }


    public function lastDay($list){
        $today = date('Y-m-d');
        $yesterday = date('Y-m-d', strtotime('-1 days'));

        $time = date('H:i',strtotime('-1 hours'));

        $lastDay = array();

        for ($l=0; $l < sizeof($list); $l++){ 
            if( 
                ( strtotime($yesterday) == strtotime($list[$l]['day']) ) 
                || 
                ( strtotime($today) == strtotime($list[$l]['day']) )
              ){
                
                if( strtotime($yesterday) == strtotime($list[$l]['day']) ){
                    if( strtotime($list[$l]['hour']) >  strtotime($time) ){
                        array_push($lastDay,$list[$l]);
                        unset($list[$l]);
                        $list = array_values($list);
                    }
                }else{
                    array_push($lastDay,$list[$l]);
                    unset($list[$l]);
                    $list = array_values($list);
                }
            }
        }

        return $lastDay;
    }

    public function lastSevenDays($list){

        $sevenDays = array(  
                            date('Y-m-d'),
                            date('Y-m-d', strtotime('-1 days')),
                            date('Y-m-d', strtotime('-2 days')),
                            date('Y-m-d', strtotime('-3 days')),
                            date('Y-m-d', strtotime('-5 days')),
                            date('Y-m-d', strtotime('-6 days')),
                            date('Y-m-d', strtotime('-7 days'))
        );

        for ($s=0; $s < sizeof($sevenDays); $s++) { 
            $sevenDaysI[$s] = array();
            for ($l=0; $l < sizeof($list); $l++) { 
                if( strtotime($sevenDays[$s]) == strtotime($list[$l]['day'])  ){
                    array_push($sevenDaysI[$s],$list[$l]);
                    unset($list[$l]);
                    $list = array_values($list);

                }
            }
        }

        return $sevenDaysI;

    }

    public function last15Days($list){
        $last15Days = array(  
                            date('Y-m-d'),
                            date('Y-m-d', strtotime('-1 days')),
                            date('Y-m-d', strtotime('-2 days')),
                            date('Y-m-d', strtotime('-3 days')),
                            date('Y-m-d', strtotime('-5 days')),
                            date('Y-m-d', strtotime('-6 days')),
                            date('Y-m-d', strtotime('-7 days')),
                            date('Y-m-d', strtotime('-8 days')),
                            date('Y-m-d', strtotime('-9 days')),
                            date('Y-m-d', strtotime('-10 days')),
                            date('Y-m-d', strtotime('-11 days')),
                            date('Y-m-d', strtotime('-12 days')),
                            date('Y-m-d', strtotime('-13 days')),
                            date('Y-m-d', strtotime('-14 days')),
                            date('Y-m-d', strtotime('-15 days'))
        );

        for ($s=0; $s < sizeof($last15Days); $s++) { 
            $last15DaysI[$s] = array();
            for ($l=0; $l < sizeof($list); $l++) { 
                if( strtotime($last15Days[$s]) == strtotime($list[$l]['day'])  ){
                    array_push($last15DaysI[$s],$list[$l]);
                    unset($list[$l]);
                    $list = array_values($list);

                }
            }
        }

        return $last15DaysI;
    }

    public function dealWithList($list){
        date_default_timezone_set('America/Sao_Paulo');

        $today = date('Y-m-d');
        
        $now = date("H:i:s");

        $oneHourAgo = date('H:i:s', strtotime('-1 hour'));

        $yesterday = date('Y-m-d', strtotime('-1 days'));

        $lastWeekDate = date('Y-m-d', strtotime('-7 days'));

        $last15Date = date('Y-m-d', strtotime('-15 days'));

        $lastHour = array();
        $lastDay = array();
        $lastWeek = array();
        $last15Days = array();
        $all = array();

        if($list){
            for ($l=0; $l < sizeof($list); $l++) { 
                /* VERIFICA SE O REGISTRO FOI CRIADO*/
                array_push($all, $list[$l]);

                /* VERIFICA SE O REGISTRO FOI CRIADO NA ÚLTIMA HORA */
                if( strtotime($list[$l]['hour']) < strtotime($now) &&  strtotime($list[$l]['hour']) > strtotime($oneHourAgo) ){
                    array_push($lastHour, $list[$l]);
                }

                /* VERIFICA SE O REGISTRO FOI CRIADO NAS ÚLTIMAS 24 HORAS */
                if( 
                    (strtotime($list[$l]['day']) <= strtotime($today)) 
                    && 
                    ( 
                        ( strtotime($list[$l]['day']) >= strtotime($yesterday) ) 
                            && 
                        ( strtotime($list[$l]['hour']) >= strtotime($now) )
                    )
                  ){
                    array_push($lastDay, $list[$l]);
                }

                /* VERIFICA SE O REGISTRO FOI CRIADO NA ÚLTIMA SEMANA */
                if( 
                    (strtotime($list[$l]['day']) <= strtotime($today)) 
                    && 
                    ( 
                        ( strtotime($list[$l]['day']) >= strtotime($lastWeekDate) ) 
                            && 
                        ( strtotime($list[$l]['hour']) >= strtotime($now) )
                    )
                  ){
                    array_push($lastWeek, $list[$l]);
                }

                /* VERIFICA SE O REGISTRO FOI CRIADO NOS ULTIMOS 15 DIAS */
                if( 
                    (strtotime($list[$l]['day']) <= strtotime($last15Date)) 
                    && 
                    ( 
                        ( strtotime($list[$l]['day']) >= strtotime($last15Date) ) 
                            && 
                        ( strtotime($list[$l]['hour']) >= strtotime($now) )
                    )
                  ){
                    array_push($lastWeek, $list[$l]);
                }
            }

            $rtr = array(
                         "lastHour" => $lastHour,
                         "lastDay" => $lastDay,
                         "lastWeek" => $lastWeek,
                         "last15Days" => $last15Days,
                         "all" => $all
                     );
        }else{
            $rtr = false;
        }

        return $rtr;
       

    }

    public function listUsers($con,$sql){
        $select = "SELECT DISTINCT
                        u.ID AS 'userID',
                        u.name AS 'userName',
                        r.ID AS 'regionID',
                        r.name AS 'region'
                        FROM analytics a
                        LEFT JOIN region r ON r.ID = a.region_id
                        LEFT JOIN user u ON u.ID = a.user_id
                        ";

        $from = array('userID','userName','regionID','region');

        $res = $con->query($select);

        $listUsers = $sql->fetch($res,$from,$from);

        return $listUsers;

    }

    public function getInfo($con,$sql){
    	$select = "SELECT 
    					u.ID AS 'userID',
    					u.name AS 'userName',
    					r.ID AS 'regionID',
    					r.name AS 'region',
    					a.day AS 'day',
    					a.hour AS 'hour',
    					a.url AS 'url',
    					a.short_url AS 'shortUrl',
    					a.ip AS 'ip'
    					FROM analytics a
    					LEFT JOIN region r ON r.ID = a.region_id
    					LEFT JOIN user u ON u.ID = a.user_id
                        ORDER BY day DESC,hour DESC
    					";

    	$from = array('userID','userName','regionID','region','day','hour','url','shortUrl','ip');

    	$res = $con->query($select);

    	$list = $sql->fetch($res,$from,$from);

    	return $list;
    }

	public function insertBase($con,$userID,$regionID,$ip,$date,$hour,$url,$shortUrl){		
    	

        $ins = "INSERT INTO analytics (user_id,region_id,day,hour,url,short_url,ip) VALUES (\"".$userID."\",\"".$regionID."\",\"".$date."\",\"".$hour."\",\"".$url."\",\"".$shortUrl."\",\"".$ip."\")";

    	$bool = false;
    	if($con->query($ins) === TRUE){
    		$bool = true;
    	}else{
    		var_dump($con->error);
    		var_dump($ins);
    	}

    	return $bool;

	}




}
