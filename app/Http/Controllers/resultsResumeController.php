<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\Render;
use App\dataBase;
use App\base;
use App\monthly;
use App\region;
use App\salesRep;
use App\share;
use App\shareRender;
use App\brand;
use App\pRate;
use App\sql;

class resultsResumeController extends Controller{
    
	public function get(){
		$base = new base();
        $db = new dataBase();
        $con = $db->openConnection("DLA");
        $r = new region();
        $sr = new salesRep();
        $b = new brand();
        $pr = new pRate();
		$render = new Render();

		$region = $r->getRegion($con,null);
        $brand = $b->getBrand($con);
        $salesRepGroup = $sr->getSalesRepGroup($con,null);
        $salesRep = $sr->getSalesRep($con,null);
        $currency = $pr->getCurrency($con);

		return view('adSales.results.0resumeGet',compact('render','region','brand','salesRepGroup','salesRep','currency'));

	}

	public function post(){
		$sql = new sql();
		$base = new base();
        $db = new dataBase();
        $con = $db->openConnection("DLA");
        $r = new region();
        $sr = new salesRep();
        $b = new brand();
        $pr = new pRate();
		$render = new Render();
		$regionID = Request::get('region');
		$brand = $base->handleBrand( $con, $b ,Request::get('brand'));
		$currencyID = Request::get('currency');
		$value = Request::get('value');
		$month = $base->getMonth();
		$table = "cmaps c";
		$sum = "gross";
		$as = "grossValue";
		$columns = " c.gross AS 'grossValue',
		             c.year AS 'year',
		             c.month AS 'month'
				   ";
		$join = "LEFT JOIN brand b ON b.ID = c.brand_id";
		$id = implode(",", $brand);
		for ($m=0; $m < sizeof($month); $m++) { 
			$where[$m] = "WHERE (c.month IN (".$month[$m][1].") ) ";//AND ( c.brand_id IN ($id) )";
			$res[$m] = $sql->selectSum($con,$sum,$as,$table,$join,$where[$m]);
			$fetch = 'grossValue';
			$cmaps[$m] = $sql->fetchSum($res[$m],$fetch);
			var_dump($month[$m][0]);
			var_dump($cmaps[$m]);

		}

	}

}
