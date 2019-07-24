<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\base;
use App\dataBase;
use App\region;
use App\pRate;
use App\brand;
use App\renderRanking;
use App\rankings;
use App\subRankings;
use Validator;

class rankingController extends Controller {
    
    public function agencyNumberByAgencyGroup(){
        $db = new dataBase();
        $con = $db->openConnection("DLA");
        $brands = Request::get("brands");
        $type = Request::get("aux");
        $region = Request::get("region");
        $value = Request::get("value");
        $currency = Request::get("currency");
        $months = Request::get("months");
        $years = Request::get("years");
        $filter = Request::get("name");

        $subR = new subRankings();

        //var_dump($type);

        $x = $subR->myMiddleware($con, $brands, $type, $region, $value, $currency, $months, $years, $filter);

        echo $x;

    }

    public function get(){
    	
    	$db = new dataBase();
        $con = $db->openConnection("DLA");

        $region = new region();
        $salesRegion = $region->getRegion($con);

        $currency = new pRate();
        $currencies = $currency->getCurrency($con);

        $b = new brand();
        $brands = $b->getBrand($con);

        $render = new renderRanking();

        return view('adSales.ranking.0rankingGet', compact('salesRegion', 'currencies', 'brands', 'render'));
    }

    public function post(){


    	$base = new base();

    	$db = new dataBase();
        $con = $db->openConnection("DLA");


        $validator = Validator::make(Request::all(),[
            'region' => 'required',
            'type' => 'required',
            'type2' => 'required',
            'nPos' => 'required',
            'month' => 'required',
            'brand' => 'required',
            'firstPos' => 'required',
            'secondPos' => 'required',
            'thirdPos' => 'required',
            'currency' => 'required',
            'value' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $region = Request::get("region");
    	$r = new region();
    	$salesRegion = $r->getRegion($con);

    	$type = Request::get("type");
    	$temp = Request::get("type2");

        for ($t=0; $t < sizeof($temp); $t++) { 
            $type2[$t] = json_decode(base64_decode($temp[$t]));
        }

        /*for ($t=0; $t < sizeof($type2); $t++) { 
    		$type2[$t] = base64_decode($type2[$t]);
    	}*/

    	$nPos = Request::get("nPos");

    	$months = Request::get("month");

    	$tmp = Request::get("brand");
        $base = new base();
        $brands = $base->handleBrand($tmp);

        $b = new brand();
        $brand = $b->getBrand($con);

        $firstForm = Request::get("firstPos");
        $secondForm = Request::get("secondPos");
        $thirdForm = Request::get("thirdPos");

    	$currency = Request::get("currency");
        $p = new pRate();
        $pRate = $p->getCurrency($con, array($currency));
        $currencies = $p->getCurrency($con);

    	$value = Request::get("value");

    	$r = new rankings();

        $years = $r->createPositions($firstForm, $secondForm, $thirdForm);

        $values = $r->getAllResults($con, $brands, $type, $region, $value, $pRate, $months, $years);
        //var_dump($values);
        $filterValues = $r->filterValues($values, $type2, $type);
        
        $matrix = $r->assembler($values, $type2, $years, $type, $filterValues);
        $mtx = $matrix[0];
        $total = $matrix[1];
        $IDS = $matrix[2];

        if ($nPos == 'All') {
            $size = sizeof($mtx[0]);
        }else{
            $size = ($nPos+1);
        }

        $names = $r->createNames($type, $months, $years);

        $render = new renderRanking();
        //var_dump($values);
        //var_dump("mtx",$matrix[2]);

        $subR = new subRankings();
        return view('adSales.ranking.0rankingPost', compact('con','subR','salesRegion', 'currencies', 'brand', 'render', 'mtx', 'names', 'pRate', 'value', 'total', 'size', 'type', 'months', 'brands', 'years', 'pRate', 'region', 'IDS'));

    }
}

/*{{--@for($n = 0; $n < $size; $n++)

                    <?php
                        $val = $mtx[sizeof($years)][$n];
                        if(array_key_exists($val, $IDS)){
                            $Id = $IDS[$val];   
                        }else{
                            $Id = -1;
                        }

                    ?>

                    $(document).on('click', "#"+"agencyGroup"+{{$Id}}, function(){

                        var aux = "agencyGroup";
                        var name = $(this).text();
                        var months = <?php echo json_encode($months); ?>;
                        var brands = <?php echo json_encode($brands); ?>;
                        var years  = <?php echo json_encode($years); ?>;
                        var type = aux;
                        var value = "{{$value}}";
                        var currency = <?php echo json_encode($pRate); ?>;
                        var region = "{{$region}}";
                        
                        if ($("#sub"+aux+{{$Id}}).css("display") == "none") {
                            $.ajax({
                                url: "/ajaxRanking/subRanking",
                                method: "POST",
                                data: {name, months, brands, years, aux, value, currency, region},
                                success: function(output){
                                    $("#sub"+aux+{{$Id}}).html(output);
                                    $("#sub"+aux+{{$Id}}).css("display", "");
                                },
                                error: function(xhr, ajaxOptions,thrownError){
                                    alert(xhr.status+" "+thrownError);
                                }
                            });
                        }else{
                            $("#sub"+aux+{{$Id}}).css("display", "none");   
                        }
                    });
                @endfor}}*/

