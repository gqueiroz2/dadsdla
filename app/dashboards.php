<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\rank;
use App\sql;
use App\subRankings;
use App\brand;

class dashboards extends rank{
    
	public function mount($con,$p,$type,$regionID,$currency,$value,$baseFilter,$secondaryFilter,$years){
		$sr = new subRankings();

	   	/*DEFINIR SE PARA BRASIL PEGA CMAPS OU NAO*/
	   	/*DEFINIR SE PARA BRASIL PEGA CMAPS OU NAO*/
	   	/*DEFINIR SE PARA BRASIL PEGA CMAPS OU NAO*/

	   	$table = "ytd";

	   	/*DEFINIR SE PARA BRASIL PEGA CMAPS OU NAO*/
	   	/*DEFINIR SE PARA BRASIL PEGA CMAPS OU NAO*/
	   	/*DEFINIR SE PARA BRASIL PEGA CMAPS OU NAO*/

	    
        $currencyName = $p->getCurrency($con, array($currency))[0]['name'];

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

	    switch ($type) {
	    	case 'client':
    			$last3YearsRoot = false;//$this->last3Years($con,"agency","root",$type,$p,$sr,$table,$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency);
    			$last3YearsChild = false;//$this->last3Years($con,"client","child",$type,$p,$sr,$table,$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency);
    			$last3YearsByMonth = false;//$this->last3YearsByMonth($con,$type,$p,$sr,$table,$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency);
    			$last3YearsByBrand = false;//$this->last3YearsByBrand($con,$type,$p,$sr,$table,$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency);
    			$last3YearsByProduct = false;//$this->last3YearsByProduct($con,$type,$p,$sr,$table,$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency);
	    		break;
	    	
	    	default:
	    		if($type == "agency"){	    			
	    			$last3YearsRoot = $this->last3Years($con,"agency","root",$type,$p,$sr,$table,$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency);
	    			$last3YearsChild = $this->last3Years($con,"client","child",$type,$p,$sr,$table,$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency);
	    			$last3YearsByMonth = $this->last3YearsByMonth($con,$type,$p,$sr,$table,$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency);
	    			$last3YearsByBrand = $this->last3YearsByBrand($con,$type,$p,$sr,$table,$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency);
	    			$last3YearsByProduct = $this->last3YearsByProduct($con,$type,$p,$sr,$table,$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency);
	    			/*
	    			var_dump($last3YearsRoot);
	    			var_dump($last3YearsChild);
	    			var_dump($last3YearsByMonth);
	    			var_dump($last3YearsByProduct);
	    			*/
	    		}else{

	    		}
	    		break;
	    }

	    $rtr = array( "last3YearsRoot" => $last3YearsRoot,
	    			  "last3YearsChild" => $last3YearsChild,
	    			  "last3YearsByMonth" => $last3YearsByMonth,
	    			  "last3YearsByBrand" => $last3YearsByBrand,
	    			  "last3YearsByProduct" => $last3YearsByProduct,

	                );

	    return $rtr;

	}

	public function last3YearsByProduct($con,$type,$p,$sr,$table,$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency){
		$sql = new sql(); 	
		$products = $this->getProducts($con,$table,$type,$baseFilter);
		for ($y=0; $y < sizeof($years); $y++) { 
			for ($p=0; $p < sizeof($products); $p++) { 
				$some[$y][$p] = "SELECT SUM($column) AS mySum 
		    					FROM $table 
		    					WHERE(year = \"".$years[$y]."\")
		    					AND (client_product = \"".$products[$p]['product']."\")
		    					AND ( ".$type."_id = \"".$baseFilter->id."\")";
		    	$res[$y][$p] = $con->query($some[$y][$p]);
		    	$from = array("mySum");
		    	$values[$y][$p] = $sql->fetch($res[$y][$p],$from,$from)[0]['mySum']*$pRate;
			}
		}

		$rtr = array("products" => $products , "values" => $values);
		return $rtr;
	}

	public function last3YearsByBrand($con,$type,$p,$sr,$table,$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency){
		$sql = new sql(); 
		$brands = $this->getBrands($con);

		for ($y=0; $y < sizeof($years); $y++) { 
		    for ($b=0; $b < sizeof($brands); $b++) { 
		    	
		    	$some[$y][$b] = "SELECT SUM($column) AS mySum 
		    					FROM $table 
		    					WHERE(year = \"".$years[$y]."\")
		    					AND (brand_id = \"".$brands[$b][0]."\")
		    					AND ( ".$type."_id = \"".$baseFilter->id."\")";
		    	$res[$y][$b] = $con->query($some[$y][$b]);
		    	$from = array("mySum");
		    	$values[$y][$b] = $sql->fetch($res[$y][$b],$from,$from)[0]['mySum']*$pRate;
		    }
    	}

    	return $values;
	}

	public function last3YearsByMonth($con,$type,$p,$sr,$table,$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency){
		$sql = new sql(); 
		$months = $this->months;   	
		for ($y=0; $y < sizeof($years); $y++) { 
		    for ($m=0; $m < sizeof($months); $m++) { 
		    	
		    	$some[$y][$m] = "SELECT SUM($column) AS mySum 
		    					FROM $table 
		    					WHERE(year = \"".$years[$y]."\")
		    					AND (month = \"".$months[$m]."\")
		    					AND ( ".$type."_id = \"".$baseFilter->id."\")";
		    	$res[$y][$m] = $con->query($some[$y][$m]);
		    	$from = array("mySum");
		    	$values[$y][$m] = $sql->fetch($res[$y][$m],$from,$from)[0]['mySum']*$pRate;
		    }
    	}

    	return $values;
	}

    public function last3Years($con,$what,$kind,$type,$p,$sr,$table,$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency){
    	$sql = new sql();    	
	    if($kind == "root"){
	    	for ($y=0; $y < sizeof($years); $y++) { 
		    	$some[$y] = "SELECT SUM($column) AS mySum 
		    					FROM $table 
		    					WHERE(year = \"".$years[$y]."\") 
		    					AND ( ".$what."_id = \"".$baseFilter->id."\")";
		    	$res[$y] = $con->query($some[$y]);
		    	$from = array("mySum");
		    	$values[$y] = $sql->fetch($res[$y],$from,$from)[0]['mySum']*$pRate;
	    	}
	    }else{
	    	$brands = $this->getBrands($con);
	    	$months = $this->months;
	    	$filter = $baseFilter->$type;
	    	$cr = $p->getCurrency($con, array($currency));
	    	$values = $sr->getSubResults($con, $brands, $type, $regionID, $value, $cr, $months, $years, $filter);
	    	//$mtx = $sr->assembler($values,$years,$type);
	    }
	    return $values;
    }

    public function getProducts($con,$table,$type,$filter){
    	$sql = new sql();
    	$select = "SELECT DISTINCT client_product 
    						FROM $table
    						WHERE( ".$type."_id = \"".$filter->id."\" )";

    	$res = $con->query($select);
    	$from = array("client_product");
    	$to = array("product");
    	$products = $sql->fetch($res,$from,$to);

    	return $products;
    }

    public function getBrands($con){
    	$b = new brand();
    	$temp = $b->getBrand($con);

    	for ($i=0; $i < sizeof($temp); $i++) { 
    		$brands[$i][0] = $temp[$i]['id'];
    		$brands[$i][1] = $temp[$i]['name'];
    	}

    	return $brands;
    }

    public function getMonths(){
    	return $this->months;
    }

    public function getMonthsFullName(){
    	return $this->monthsFullName;
    }

    protected $months = array(1,2,3,4,5,6,7,8,9,10,11,12);
    protected $monthsFullName = array("January",
    	 					  "February",
    	 					  "March",
    	 					  "April",
    	 					  "May",
    	 					  "June",
    	 					  "July",
    	 					  "August",
    	 					  "September",
    	 					  "October",
    	 					  "November",
    	 					  "December"
                             );

}
