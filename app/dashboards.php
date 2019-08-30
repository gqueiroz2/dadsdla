<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\rank;
use App\sql;
use App\subRankings;
use App\brand;
use App\rankings;
class dashboards extends rank{
    
	public function mount($con,$p,$type,$regionID,$currency,$value,$baseFilter,$secondaryFilter,$years){
		$sr = new subRankings();

	   	/*DEFINIR SE PARA BRASIL PEGA CMAPS OU NAO*/
	   	/*DEFINIR SE PARA BRASIL PE[
	   	GA CMAPS OU NAO*/
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
        	$columnD = "gross_revenue";
        }else{
            $column = "net_revenue_prate";  
        	$columnD = "net_revenue";	
        }        

	    switch ($type) {
	    	case 'client':
    			$last3YearsRoot = $this->last3Years($con,"agency","root",$type,$p,$sr,$table,$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency);
    			$last3YearsChild = $this->last3Years($con,"client","child",$type,$p,$sr,$table,$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency);
    			$last3YearsByMonth = $this->last3YearsByMonth($con,$type,$p,$sr,$table,$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency,$columnD);
    			$last3YearsByBrand = $this->last3YearsByBrand($con,$type,$p,$sr,$table,$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency,$columnD);
    			$last3YearsByProduct = $this->last3YearsByProduct($con,$type,$p,$sr,$table,$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency);
	    		break;
	    	
	    	default:
	    		if($type == "agency"){	    	
	    			$last3YearsRoot = $this->last3Years($con,"agency","root",$type,$p,$sr,$table,$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency);
	    			$last3YearsChild = $this->last3Years($con,"client","child",$type,$p,$sr,$table,$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency);
	    			$last3YearsByMonth = $this->last3YearsByMonth($con,$type,$p,$sr,$table,$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency,$columnD);
	    			$last3YearsByBrand = $this->last3YearsByBrand($con,$type,$p,$sr,$table,$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency,$columnD);
	    			$last3YearsByProduct = $this->last3YearsByProduct($con,$type,$p,$sr,$table,$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency);
	    		}else{
	    			$last3YearsRoot = $this->last3Years($con,"agencyGroup","root",$type,$p,$sr,$table,$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency);
	    			$last3YearsChild = $this->last3Years($con,"agency","child",$type,$p,$sr,$table,$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency);
	    			$last3YearsByMonth = $this->last3YearsByMonth($con,$type,$p,$sr,$table,$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency,$columnD);
	    			$last3YearsByBrand = $this->last3YearsByBrand($con,$type,$p,$sr,$table,$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency,$columnD);
	    			$last3YearsByProduct = $this->last3YearsByProduct($con,$type,$p,$sr,$table,$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency);
	    		}

	    		break;
	    }

        
        /*var_dump("last3YearsRoot");
        var_dump($last3YearsRoot);
        var_dump("last3YearsChild");
        var_dump($last3YearsChild);
        var_dump("last3YearsByMonth");
        var_dump($last3YearsByMonth);
        var_dump("last3YearsByBrand");
        var_dump($last3YearsByBrand);
        var_dump("last3YearsByProduct");
        var_dump($last3YearsByProduct);
        var_dump("------------------------------------------------------------");*/


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

        if (is_array($secondaryFilter)) {
            $secondaryFilter = implode(",", $secondaryFilter);
        }

		$products = $this->getProducts($con,$table,$type,$baseFilter);

		for ($y=0; $y < sizeof($years); $y++) { 
			for ($p=0; $p < sizeof($products); $p++) { 
				if($type == "agencyGroup"){
		    		$smt = "agency_group";
		    		$join = "LEFT JOIN agency a ON a.ID = y.agency_id";
		    		$where = "WHERE(year = \"".$years[$y]."\")
		    					AND (client_product = \"".$products[$p]['product']."\")
		    					AND ( ".$smt."_id = \"".$baseFilter->id."\")
                                AND ( client_id = \"".$products[$p]['clientID']."\")
                                AND (agency_id IN (".$secondaryFilter."))";
		    	}elseif ($type == "agency") {
                    $join = false;
                    $where = "WHERE(year = \"".$years[$y]."\")
                                AND (client_product = \"".$products[$p]['product']."\")
                                AND ( ".$type."_id = \"".$baseFilter->id."\")
                                AND ( client_id = \"".$products[$p]['clientID']."\")
                                AND (client_id IN (".$secondaryFilter."))";
                }else{
		    		$join = false;
		    		$where = "WHERE(year = \"".$years[$y]."\")
		    					AND (client_product = \"".$products[$p]['product']."\")
		    					AND ( ".$type."_id = \"".$baseFilter->id."\")
                                AND ( client_id = \"".$products[$p]['clientID']."\")
                                AND (agency_id IN (".$secondaryFilter."))";
		    	}

				$some[$y][$p] = "SELECT SUM($column) AS mySum 
		    					FROM $table y 
		    					$join
		    					$where";


		    	$res[$y][$p] = $con->query($some[$y][$p]);
		    	$from = array("mySum");
		    	$values[$y][$p] = $sql->fetch($res[$y][$p],$from,$from)[0]['mySum']*$pRate;
			}
		}

		$rtr = array("products" => $products , "values" => $values);
		return $rtr;
	}

	public function last3YearsByBrand($con,$type,$p,$sr,$table,$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency,$columnD){
		$sql = new sql(); 
		$brands = $this->getBrands($con);

        if (is_array($secondaryFilter)) {
            $secondaryFilter = implode(",", $secondaryFilter);
        }

		for ($y=0; $y < sizeof($years); $y++) { 
		    for ($b=0; $b < sizeof($brands); $b++) { 
		    	
		    	if($type == "agencyGroup"){
		    		$smt = "agency_group";
		    		$join = "LEFT JOIN agency a ON a.ID = y.agency_id";
                    if ($brands[$b][0] == '9') {
                        $where = "WHERE(year = \"".$years[$y]."\")
                                    AND (brand_id != \"10\")
                                    AND ( ".$smt."_id = \"".$baseFilter->id."\")
                                    AND (agency_id IN (".$secondaryFilter."))";
                    }else{
                        $where = "WHERE(year = \"".$years[$y]."\")
                                    AND (brand_id = \"".$brands[$b][0]."\")
                                    AND ( ".$smt."_id = \"".$baseFilter->id."\")
                                    AND (agency_id IN (".$secondaryFilter."))";
                    }
		    	}elseif ($type == "agency") {
                    $join = false;
                    if ($brands[$b][0] == '9') {
                        $where = "WHERE(year = \"".$years[$y]."\")
                                AND (brand_id != \"10\")
                                AND ( ".$type."_id = \"".$baseFilter->id."\")
                                AND (client_id IN (".$secondaryFilter."))";
                    }else{
                        $where = "WHERE(year = \"".$years[$y]."\")
                                AND (brand_id = \"".$brands[$b][0]."\")
                                AND ( ".$type."_id = \"".$baseFilter->id."\")
                                AND (client_id IN (".$secondaryFilter."))";    
                    }
                }else{
		    		$join = false;
                    if ($brands[$b][0] == '9') {
                        $where = "WHERE(year = \"".$years[$y]."\")
                                AND (brand_id != \"10\")
                                AND ( ".$type."_id = \"".$baseFilter->id."\")
                                AND (agency_id IN (".$secondaryFilter."))";
                    }else{
                        $where = "WHERE(year = \"".$years[$y]."\")
                                AND (brand_id = \"".$brands[$b][0]."\")
                                AND ( ".$type."_id = \"".$baseFilter->id."\")
                                AND (agency_id IN (".$secondaryFilter."))";    
                    }
		    	}	    	

                if ($brands[$b][0] == '9' || $brands[$b][0] == '10') {
                    $some[$y][$b] = "SELECT SUM($columnD) AS mySum 
                                FROM fw_digital y
                                $join
                                $where";
                }else{
                    $some[$y][$b] = "SELECT SUM($column) AS mySum 
                                FROM $table y
                                $join
                                $where";
                }

		    	$res[$y][$b] = $con->query($some[$y][$b]);
		    	$from = array("mySum");
		    	$values[$y][$b] = $sql->fetch($res[$y][$b],$from,$from)[0]['mySum']*$pRate;
		    }
    	}

    	return $values;
	}

	public function last3YearsByMonth($con,$type,$p,$sr,$table,$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency,$columnD){
		$sql = new sql(); 
		$months = $this->months; 

        if (is_array($secondaryFilter)) {
            $secondaryFilter = implode(",", $secondaryFilter);
        }

		for ($y=0; $y < sizeof($years); $y++) { 
		    for ($m=0; $m < sizeof($months); $m++) { 
		    	
		    	if($type == "agencyGroup"){
		    		$smt = "agency_group";
		    		$join = "LEFT JOIN agency a ON a.ID = y.agency_id";
		    		$where = "WHERE(year = \"".$years[$y]."\")
		    					AND (month = \"".$months[$m]."\")
		    					AND ( ".$smt."_id = \"".$baseFilter->id."\")
                                AND (agency_id IN (".$secondaryFilter."))";
                    $someD[$y][$m] = "SELECT SUM($columnD) as 'mySum' FROM fw_digital y $join WHERE (year = \"".$years[$y]."\") AND (month = \"".$months[$m]."\") AND ( ".$smt."_id = \"".$baseFilter->id."\") AND (agency_id IN (".$secondaryFilter."))";
		    	}elseif ($type == "agency") {
                    $join = false;
                    $where = "WHERE(year = \"".$years[$y]."\")
                                AND (month = \"".$months[$m]."\")
                                AND ( ".$type."_id = \"".$baseFilter->id."\") AND (client_id IN (".$secondaryFilter."))";
                    $someD[$y][$m] = "SELECT SUM($columnD) as 'mySum' FROM fw_digital y $join WHERE (year = \"".$years[$y]."\") AND (month = \"".$months[$m]."\") AND ( ".$type."_id = \"".$baseFilter->id."\") AND (client_id IN (".$secondaryFilter."))";
                }else{
		    		$join = false;
		    		$where = "WHERE(year = \"".$years[$y]."\")
		    					AND (month = \"".$months[$m]."\")
		    					AND ( ".$type."_id = \"".$baseFilter->id."\") AND (agency_id IN (".$secondaryFilter."))";
                    $someD[$y][$m] = "SELECT SUM($columnD) as 'mySum' FROM fw_digital y $join WHERE (year = \"".$years[$y]."\") AND (month = \"".$months[$m]."\") AND ( ".$type."_id = \"".$baseFilter->id."\") AND (agency_id IN (".$secondaryFilter."))";
		    	}

		    	$some[$y][$m] = "SELECT SUM($column) AS mySum 
		    					FROM $table y
		    					$join
		    					$where";

		    	$res[$y][$m] = $con->query($some[$y][$m]);

		    	$from = array("mySum");
		    	$values[$y][$m] = $sql->fetch($res[$y][$m],$from,$from)[0]['mySum']*$pRate;


                $resD[$y][$m] = $con->query($someD[$y][$m]);

                $valuesD[$y][$m] = $sql->fetch($resD[$y][$m],$from,$from)[0]['mySum']*$pRate;

                $values[$y][$m] += $valuesD[$y][$m];

		    }
    	}
    	return $values;
	}

    public function last3Years($con,$what,$kind,$type,$p,$sr,$table,$regionID,$pRate,$column,$baseFilter,$secondaryFilter,$years,$value,$currency){
    	$sql = new sql();    

	    $brands = $this->getBrands($con);
	    $months = $this->months;
	    $cr = $p->getCurrency($con, array($currency));
	    
	    if($kind == "root"){
	    	if ($type == "agencyGroup") {
                $somekind = $sr->getAllValues($con,$table,$type,$type, $brands, $regionID, $value, $years, $months, $cr, "", "agency", $secondaryFilter);
                $somekind2 = $sr->getAllValues($con,$table,$type,$type, $brands, $regionID, $value, $years, $months, $cr, "", "agency");
	    	}else{
                $somekind = $sr->getAllValues($con,$table,$type,$type, $brands, $regionID, $value, $years,$months,$cr, null, null, $secondaryFilter);
                $somekind2 = $sr->getAllValues($con,$table,$type,$type, $brands, $regionID, $value, $years,$months,$cr);
	    	}
            
            $filterValues = $sr->filterValues2($somekind, array($baseFilter), $type);

	    	$values = $this->assembler($somekind,array($baseFilter), $years, $type, $filterValues, $somekind2);

	    	unset($values[1]);
	    	
	    }else{    	
	    	$filter = $baseFilter->$type;
	    	
	    	$values = $sr->getSubResults($con, $brands, $type, $regionID, $value, $cr, $months, $years, $filter, $secondaryFilter);
            
	    	//$mtx = $sr->assembler($values,$years,$type);
	    }
	    return $values;
    }

    

    public function getProducts($con,$table,$type,$filter){
    	$sql = new sql();

    	if($type == "client"){
    		$smt = "client";
            $join = "LEFT JOIN client c ON c.ID = y.client_id";
            $where = "WHERE( ".$smt."_id = \"".$filter->id."\" )";
    	}else{
    		$smt = "agency";

    		if($type == "agencyGroup"){

    			$join = "LEFT JOIN agency a ON a.ID = y.agency_id 
                         LEFT JOIN client c ON c.ID = y.client_id";

    			$where = "WHERE( ".$smt."_group_id = \"".$filter->id."\" )";
    		}else{
                $join = "LEFT JOIN client c ON c.ID = y.client_id";
    			$where = "WHERE( ".$smt."_id = \"".$filter->id."\" )";
    		}    		

    	}

    	$select = "SELECT DISTINCT client_product, client_id, c.name AS \"client\"

    						FROM $table y $join $where";
                            
    	$res = $con->query($select);
    	$from = array("client_product","client_id","client");
    	$to = array("product","clientID","client");
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

    public function assembler($values, $type2, $years, $type, $filterValues, $somekind){

        if (strlen($type) > 6) {
            $var = "agency groups";
            $aux = "agencyGroup";
        }else{
            if ($type == "client") {
                $var = "Client";
                $aux = $type;    
            }else{
                $var = "Agency";
                $aux = $type;
            }
            
        }

        for ($y=0; $y < sizeof($years); $y++) { 
            $mtx[$y][0] = "Pos. ".$years[$y];
        }

        $last = $y;
        
        $mtx[$last][0] = "Agency Group";

        if ($type == "agency") {
            $option = 2;
            $mtx[$last+1][0] = ucfirst($var);
        }else{
            $option = 1;
        }
        
        if ($type == "client") {
            $mtx[$last][0] = ucfirst($var);
        }

        for ($l=0; $l < sizeof($years); $l++) { 
            $mtx[(sizeof($years)+$l+$option)][0] = "Rev. ".$years[$l];
        }

        if (sizeof($years) >= 2) {
            $last = $l+sizeof($years)+$option;

            $mtx[$last][0] = "VAR ABS.";
            $mtx[$last+1][0] = "VAR %";    
        }

        if (is_array($values[0])) {
            $p = 0;
        }elseif (is_array($values[1])) {
            $p = 1;
        }else{
            $p = 2;
        }

        $name = $values[$p][0][$type];

        for ($i=0; $i < sizeof($somekind); $i++) { 
            for ($j=0; $j < sizeof($somekind[$i]); $j++) { 
                if ($somekind[$i][$j][$type] == $name) {
                    for ($p=0; $p < 3; $p++) { 
                        if (is_array($values[$i])) {
                            $somekind[$i][$j]['total'] = $values[$p][0]['total'];
                        }else{
                            $somekind[$i][$j]['total'] = 0;
                        }   
                    }
                }
            }
        }

        for ($i=0; $i < sizeof($somekind); $i++) { 
            usort($somekind[$i], array($this,'compare'));   
        }

        for ($t=0; $t < sizeof($type2); $t++) { 
            
            if ($filterValues[$type2[$t]->id] == 1) {
                for ($m=0; $m < sizeof($mtx); $m++) { 
                    array_push($mtx[$m], $this->checkColumn($mtx, $m, $type2, $t, $values, $years, $aux, sizeof($mtx[$m]), $somekind));
                }
            }
        }

        $total = $this->assemblerTotal($mtx, $years);

        return array($mtx, $total);
    }

    public function checkColumn($mtx, $m, $type2, $t, $values, $years, $type, $p, $somekind){

        if (substr($mtx[$m][0], 0, 3) == "Pos") {
            $var = substr($mtx[$m][0], 5);

            $res = $this->checkOtherYearsPosition($type2[$t]->$type, $values, $var, $years, $type, $somekind);
        }elseif (substr($mtx[$m][0], 0, 3) == "Rev") {
            $var = substr($mtx[$m][0], 5);

            $res = $this->getValueByYear($type2[$t]->$type, $values, $var, $years, $type);
        }elseif ($mtx[$m][0] == "VAR ABS.") {
            if ($mtx[$m-sizeof($years)][$p] == "-" && $mtx[$m-sizeof($years)+1][$p] == "-") {
                $res = "-";
            }elseif ($mtx[$m-sizeof($years)][$p] == "-") {
                $res = ($mtx[$m-sizeof($years)+1][$p]*-1);
            }elseif ($mtx[$m-sizeof($years)+1][$p] == "-") {
                $res = $mtx[$m-sizeof($years)][$p];
            }else{
                $res = $mtx[$m-sizeof($years)][$p] - $mtx[$m-sizeof($years)+1][$p];
            }
        }elseif ($mtx[$m][0] == "VAR %") {
            if ($mtx[$m-sizeof($years)][$p] == 0 || $mtx[$m-sizeof($years)][$p] == "-" || $mtx[$m-sizeof($years)-1][$p] == "-") {
                $res = 0.0;
            }else{
                $res = ($mtx[$m-sizeof($years)-1][$p] / $mtx[$m-sizeof($years)][$p])*100;
            }
        }elseif ($type == "agency" || $type == "agencyGroup") {
            if ($mtx[$m][0] == "Agency Group") {
                $res = $type2[$t]->agencyGroup;
            }else{
                $res = $type2[$t]->$type;    
            }
        }else{
            $res = $type2[$t]->$type;
        }    
        

        return $res;

    }

    public function checkOtherYearsPosition($name, $values, $year, $years, $type, $somekind){
        
        for ($y=0; $y < sizeof($years); $y++) { 
            if ($year == $years[$y]) {
                $p = $y;       
            }
        }

        $ok = 0;

        if (is_array($somekind[$p])) {
            for ($v=0; $v < sizeof($somekind[$p]); $v++) { 
                if ($somekind[$p][$v][$type] == $name) {
                    $pos = $v+1;
                    $ok = 1;
                }
            }   
        }else{
            $pos = false;
        }

        if ($ok == 0) {
            $pos = "-";
        }

        return $pos;

    }

    public function getValueByYear($name, $values, $year, $years, $type){

        for ($y=0; $y < sizeof($years); $y++) { 
            if ($year == $years[$y]) {
                $p = $y;
            }
        }

        $ok = 0;

        if (is_array($values[$p])) {
            for ($v=0; $v < sizeof($values[$p]); $v++) { 
                    if ($name == "Others") {
                        //var_dump("name", $values[$p][$v][$type]);
                        //var_dump("value", $values[$p][$v]["total"]);
                    }
                if ($values[$p][$v][$type] == $name) {
                    $rtr = $values[$p][$v]['total'];
                    $ok = 1;
                }
            }
        }else{
            $rtr = false;
        }

        if ($ok == 0) {
            $rtr = "-";
        }

        return $rtr;
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
