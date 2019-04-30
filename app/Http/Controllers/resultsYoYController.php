<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\dataBase;
use App\region;
use App\brand;
use App\Render;
use App\YoY;

class resultsYoYController extends Controller{

    public function YoYGet(){

        $db = new dataBase();
        $con = $db->openConnection("DLA");

        $render = new Render();

        $region = new region();
        $salesRegion = $region->getRegion($con);

        $brands = new brand();
        $brandsValue = $brands->getBrand($con);

        return view("adSales.results.YoYGet", compact('render', 'salesRegion', 'brandsValue'));

    }

    public function YoYPost(){

    	$db = new dataBase();
        $con = $db->openConnection("DLA");

    	$regionID = Request::get("region");
    	$r = new region();
    	$regionName = $r->getRegion($con, array($regionID));

    	$year = Request::get("year");
    	$brand = Request::get("brand");
    	$currency = Request::get("currency");
    	$value = Request::get("value");
    	$form1 = Request::get("firstPos");
    	$form2 = Request::get("secondPos");

    	$yearLine1 = $year - 1;
    	$nameLine1 = "Real $yearLine1";

    	$nameLine2 = "Target $year";

    	$nameLine3 = "Real $year"

        $yoy = new YoY();
        $brandsName = $yoy->getBrandsName($con, $brand);
        $lineForm1 = $yoy->line13Get($con, $form1, $yearLine1, $value, $regionID);
        $lineForm2 = $yoy->line2Get($con, $form2, $regionID, $year);
        $lineForm1 = $yoy->line13Get($con, $form1, $year, $value, $regionID);
        //var_dump($brandsName);

    }
}
