<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class analytics extends Model{
    
    public function assembler($con,$sql){
        
        $listUsers = $this->listUsers($con,$sql);
    	$list = $this->getInfo($con,$sql);
        $dealWithList = $this->dealWithList($list);  

        $lastHourV = sizeof($dealWithList['lastHour']);
        $lastDayV = sizeof($dealWithList['lastDay']);
        $lastWeekV = sizeof($dealWithList['lastWeek']);
        $allV = sizeof($dealWithList['all']); 
        $lastSevenDays = $this->lastSevenDays($list);

        for ($l=0; $l < sizeof($lastSevenDays); $l++) { 
            $lastSevenDaysV[$l] = sizeof($lastSevenDays[$l]);
        }

        var_dump($lastSevenDaysV);

/*
        $lastHour = $dealWithList['lastHour'];
        $lastDay = $dealWithList['lastDay'];
        $lastWeek = $dealWithList['lastWeek'];
        $all = $dealWithList['all']; 
*/
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

    public function dealWithList($list){
        date_default_timezone_set('America/Sao_Paulo');

        $today = date('Y-m-d');
        
        $now = date("H:i:s");

        $oneHourAgo = date('H:i:s', strtotime('-1 hour'));

        $yesterday = date('Y-m-d', strtotime('-1 days'));

        $lastWeekDate = date('Y-m-d', strtotime('-7 days'));

        $lastHour = array();
        $lastDay = array();
        $lastWeek = array();
        $all = array();

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
        }

        $rtr = array(
                     "lastHour" => $lastHour,
                     "lastDay" => $lastDay,
                     "lastWeek" => $lastWeek,
                     "all" => $all
                 );

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
