<?php

namespace App\Http\Controllers;

use App\dataBase;
use App\region;
use App\User;
use App\analytics;
use App\analyticsRender;
use App\sql;
use Illuminate\Support\Facades\Request;

class analyticsController extends Controller{

    public function panel(){

    	$db = new dataBase();
    	$at = new analytics();
    	$sql = new sql();

        $aR = new analyticsRender();

        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        
    	$at = new analytics();

    	$info = $at->assembler($con,$sql);

        return view('analytics.home',compact('aR','info'));
    }

    public function base(){

    	$db = new dataBase();
    	$r = new region();
    	$u = new User();
    	$at = new analytics();

        $default = $db->defaultConnection();
        $con = $db->openConnection($default);

    	$userName = Request::get('userName');
    	$userRegion = Request::get('userRegion');
    	$userEmail = Request::get('userEmail');
    	$date = Request::get('date');
    	$hour = Request::get('hour');
    	$url = Request::get('url');
    	$shortUrl = Request::get('shortUrl');
    	$ipV1 = Request::get('ipV1');

    	$regionID = $r->getRegionByName($con,$userRegion)['id'];
		
		$user = $u->getUserByEmail($con,$userEmail);

		$userID = $user['id'];

        //var_dump($con);

		$boolean = $at->insertBase($con,$userID,$regionID,$ipV1,$date,$hour,$url,$shortUrl);

    }
}
