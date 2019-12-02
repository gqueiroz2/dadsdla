<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class analytics extends Model{
    
    public function assembler($con,$sql){
    	var_dump("function panel");

    	$list = $this->getInfo($con,$sql);

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
    					";

    	var_dump($select);

    	$from = array('userID','userName','regionID','region','day','hour','url','shortUrl','ip');

    	$res = $con->query($select);

    	$list = $sql->fetch($res,$from,$from);

    	var_dump($list);

    }

	public function insertBase($con,$userID,$regionID,$ip,$date,$hour,$url,$shortUrl){
		/*
		var_dump($userID);
    	var_dump($regionID);
    	var_dump($ip);

    	var_dump($date);
    	var_dump($hour);
    	var_dump($url);
    	var_dump($shortUrl);
		*/

    	$ins = "INSERT INTO analytics (user_id,region_id,day,hour,url,short_url,ip) VALUES (\"".$userID."\",\"".$regionID."\",\"".$date."\",\"".$hour."\",\"".$url."\",\"".$shortUrl."\",\"".$ip."\")";

    	$bool = false;
    	if($con->query($ins) === TRUE){
    		$bool = true;
    	}else{
    		var_dump($con->error);
    	}

    	return $bool;

	}




}
