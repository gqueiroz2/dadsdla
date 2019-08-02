<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\rank;
use App\sql;

class dashboards extends rank{
    
	public function mount($con,$p,$type,$regionID,$currency,$value,$baseFilter,$secondaryFilter){
		var_dump($regionID);
      	var_dump($type);
	    var_dump($baseFilter);
	    var_dump($secondaryFilter);
	    var_dump($currency);
	    var_dump($value);

	   	/*DEFINIR SE PARA BRASIL PEGA CMAPS OU NAO*/
	   	/*DEFINIR SE PARA BRASIL PEGA CMAPS OU NAO*/
	   	/*DEFINIR SE PARA BRASIL PEGA CMAPS OU NAO*/

	   	$table = "ytd";

	   	/*DEFINIR SE PARA BRASIL PEGA CMAPS OU NAO*/
	   	/*DEFINIR SE PARA BRASIL PEGA CMAPS OU NAO*/
	   	/*DEFINIR SE PARA BRASIL PEGA CMAPS OU NAO*/

	    $cYear = intval(date("Y"));
	    $pYear = $cYear - 1;
	    $ppYear = $pYear - 1;
	    $years = array($cYear,$pYear,$ppYear);
	    var_dump($years);
        $currencyName = $p->getCurrency($con, array($currency))[0]['name'];
        var_dump($currencyName);

        if ($currencyName == "USD") {
            $pRate = 1.0;
        }else{
            $pRate = $p->getPRateByRegionAndYear($con, array($regionID), array($years[0]));
        }

        if($value == "gross"){
        	$column = "gross_revenue_prate";
        }else{
        	$column = "net_revenue_prate";	
        }

        var_dump($pRate);

	    switch ($type) {
	    	case 'client':
	    		# code...
	    		break;
	    	
	    	default:
	    		if($type == "agency"){
	    			var_dump("AKI");
	    			$last3YearsRoot = $this->last3Years($con,"agency","root",$table,$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years);
	    			//$last3YearsChild = $this->last3Years($con,"client","child",$table,$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years);

	    		}else{

	    		}
	    		break;
	    }

	}

    public function last3Years($con,$what,$kind,$table,$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years){
    	$sql = new sql();
    	var_dump("===================================expression===================================");

    	var_dump($regionID);
      	var_dump($what);
      	var_dump($kind);
	    var_dump($baseFilter);
	    var_dump($secondaryFilter);
	    var_dump($pRate);
	    var_dump($column);
	    var_dump($years);	    

	    if($kind == "root"){

	    	

	    	for ($y=0; $y < sizeof($years); $y++) { 

		    	$some = "SELECT SUM($column) AS mySum 
		    					FROM $table 
		    					WHERE(year = \"".$years[$y]."\") 
		    					AND ( ".$what."_id = \"".$baseFilter->id."\")";
		    	var_dump($some);
	    		
		    	$res = $con->query($some);
		    	var_dump($res);

		    	$from = array("mySum");

		    	$val = $sql->fetch($res,$from,$from);

		    	var_dump( number_format( $val[0]['mySum']*$pRate ) );
	    	}


	    }else{

	    }

	    

    }


}
