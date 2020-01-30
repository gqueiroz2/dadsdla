<?php

namespace App\Http\Controllers;

use App\dataBase;
use App\region;
use App\User;
use App\analytics;
use App\sql;
use Illuminate\Support\Facades\Request;

class analyticsController extends Controller{
    public function panel(){
    	$db = new dataBase();
    	$at = new analytics();
    	$sql = new sql();

    	$con = $db->openConnection('DLA');
    	$at = new analytics();

        var_dump("something");

    	$something = $at->assembler($con,$sql);
        

        return view('analytics.home');
    }

    public function base(){
    	$db = new dataBase();
    	$r = new region();
    	$u = new User();
    	$at = new analytics();

    	$con = $db->openConnection('DLA');

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

		$boolean = $at->insertBase($con,$userID,$regionID,$ipV1,$date,$hour,$url,$shortUrl);

    }
}
