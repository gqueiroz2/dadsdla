<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\base;
use App\dataBase;
use App\region;
use App\pRate;
use App\brand;
use App\renderMarketRanking;
use App\rankings;
use App\rankingMarket;
use Validator;

class rankingMarketController extends Controller {

	public function get(){
		
		$db = new dataBase();
      	$con = $db->openConnection("DLA");

      	$region = new region();
      	$salesRegion = $region->getRegion($con);

      	$currency = new pRate();
      	$currencies = $currency->getCurrency($con);

      	$b = new brand();
      	$brands = $b->getBrand($con);

      	$render = new renderMarketRanking();
      
      	return view("adSales.ranking.1marketGet", compact('salesRegion', 'currencies', 'brands', 'render')); 
	}

	public function post(){
		
		$db = new dataBase();
      	$con = $db->openConnection("DLA");

      	$validator = Validator::make(Request::all(),[
            'region' => 'required',
            'type' => 'required',
            'month' => 'required',
            'brand' => 'required',
            'currency' => 'required',
            'value' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $region = Request::get("region");
    	$r = new region();
    	$salesRegion = $r->getRegion($con);
    	$tmp = $r->getRegion($con,array($region));

        if(is_array($tmp)){
            $rtr = $tmp[0]['name'];
        }else{
            $rtr = $tmp['name'];
        }

    	$type = Request::get("type");

    	$tmp = Request::get("brand");
        $base = new base();
        $brands = $base->handleBrand($tmp);

        $b = new brand();
        $brand = $b->getBrand($con);

    	$months = Request::get("month");

    	$currency = Request::get("currency");
        $p = new pRate();
        $pRate = $p->getCurrency($con, array($currency));
        $currencies = $p->getCurrency($con);

    	$value = Request::get("value");
    	
    	$rm = new rankingMarket();

    	$cYear = intval(date('Y'));
        $pYear = $cYear - 1;

		$years = array($cYear, $pYear);

		$rName = $rm->TruncateRegion($rtr);

		$render = new renderMarketRanking();

		return view("adSales.ranking.1marketPost", compact('salesRegion', 'currencies', 'brand', 'type', 'brands', 'months', 'value', 'pRate', 'region', 'render', 'rName'));
	}

}

/*
<script type="text/javascript">
		$(document).ready(function(){
			
			var months = <?php echo json_encode($months); ?>;
            var type = "{{$type}}";
            var value = "{{$value}}";
            var currency = <?php echo json_encode($pRate); ?>;
            var region = "{{$region}}";

			ajaxSetup();

			@for($b = 0; $b < sizeof($brand); $b++)
				$(document).on('click', "#"+"{{$brand[$b]['name']}}", function(){

                    var name = $(this).text();

                    if ($("#sub"+"{{$brand[$b]['name']}}").css("display") == "none") {

                        $.ajax({
                            url: "/ajaxRanking/brandSubRanking",
                            method: "POST",
                            data: {name, months, type, value, currency, region},
                            success: function(output){
                                $("#sub"+"{{$brand[$b]['name']}}").html(output);
                                $("#sub"+"{{$brand[$b]['name']}}").css("display", "");
                            },
                            error: function(xhr, ajaxOptions,thrownError){
                                alert(xhr.status+" "+thrownError);
                            }
                        });
                    }else{
                    	$("#sub"+"{{$brand[$b]['name']}}").html(" ");
                        $("#sub"+"{{$brand[$b]['name']}}").css("display", "none");
                    }
                });
            @endfor
		});
	</script>
	*/