<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class pacingDLA extends Model{
	
    public function construct($con,$currency,$month,$type,$typeSelect,$region,$value){

		$form = "bts";
		$year = date('Y');
		$pYear = $year - 1;

		switch ($type) {
			case 'brand':				
				for ($b=0; $b < sizeof($typeSelect); $b++) { 
		            for ($m=0; $m < sizeof($month); $m++) { 
		                if ($typeSelect[$b][1] != 'ONL' && $typeSelect[$b][1] != 'VIX') {
		                    $currentAdSales[$b][$m] = $this->defineValuesBrand($con, "ytd", $currency, $typeSelect[$b][0], $month[$m][1], $region, $value,$year);
		                    $previousAdSales[$b][$m] = $this->defineValuesBrand($con, "ytd", $currency, $typeSelect[$b][0], $month[$m][1], $region, $value,$pYear);
		                }else{
		                    $currentAdSales[$b][$m] = $this->defineValuesBrand($con, "ytd", $currency, $typeSelect[$b][0], $month[$m][1], $region, $value, $year);
		                    $previousAdSales[$b][$m] = $this->defineValuesBrand($con, "ytd", $currency, $typeSelect[$b][0], $month[$m][1], $region, $value,$pYear);                    
		                }        
		                $currentTarget[$b][$m] = $this->defineValuesBrand($con, "plan_by_brand", $currency, $typeSelect[$b][0], $month[$m][1], $region, $value, $year, "TARGET");
		                $currentCorporate[$b][$m] = $this->defineValuesBrand($con, "plan_by_brand", $currency, $typeSelect[$b][0], $month[$m][1], $region, $value, $year, "CORPORATE");
		                $currentSAP[$b][$m] = $this->defineValuesBrand($con, "plan_by_brand", $currency, $typeSelect[$b][0], $month[$m][1], $region, $value, $year, "ACTUAL");
		                $previousSAP[$b][$m] = $this->defineValuesBrand($con, "plan_by_brand", $currency, $typeSelect[$b][0], $month[$m][1], $region, $value, $pYear, "ACTUAL");
		            }
		        }

		        $rtr = array( "typeSelect" => $typeSelect,
                              "currentAdSales" => $currentAdSales,
		        			  "previousAdSales" => $previousAdSales,
		        			  "currentTarget" => $currentTarget,
		        			  "currentCorporate" => $currentCorporate,
		        			  "currentSAP" => $currentSAP,
		        			  "previousSAP" => $previousSAP        			  
		        );
		        
				break;

			case 'advertiser':				
				for ($b=0; $b < sizeof($typeSelect); $b++) { 
		            for ($m=0; $m < sizeof($month); $m++) {
            	
	                    $currentAdSales[$b][$m] = $this->defineValuesAdvertiser($con, "ytd", $currency, $typeSelect[$b]['id'], $month[$m][1], $region, $value, $year);
	                    $previousAdSales[$b][$m] = $this->defineValuesAdvertiser($con, "ytd", $currency, $typeSelect[$b]['id'], $month[$m][1], $region, $value,$pYear);                    		                     
		                $currentTarget[$b][$m] = 0.0;//$this->defineValuesAdvertiser($con, "plan_by_brand", $currency, $typeSelect[$b]['id'], $month[$m][1], $region, $value, $year, "TARGET");
		                $currentCorporate[$b][$m] = 0.0;//$this->defineValuesAdvertiser($con, "plan_by_brand", $currency, $typeSelect[$b]['id'], $month[$m][1], $region, $value, $year, "CORPORATE");
		                $currentSAP[$b][$m] = 0.0;//$this->defineValuesAdvertiser($con, "plan_by_brand", $currency, $typeSelect[$b]['id'], $month[$m][1], $region, $value, $year, "ACTUAL");
		                $previousSAP[$b][$m] = 0.0;//$this->defineValuesAdvertiser($con, "plan_by_brand", $currency, $typeSelect[$b]['id'], $month[$m][1], $region, $value, $pYear, "ACTUAL");
		                
		            }
		        }

		        $rtr = array( "typeSelect" => $typeSelect,
                              "currentAdSales" => $currentAdSales,
		        			  "previousAdSales" => $previousAdSales,
		        			  "currentTarget" => $currentTarget,
		        			  "currentCorporate" => $currentCorporate,
		        			  "currentSAP" => $currentSAP,
		        			  "previousSAP" => $previousSAP        			  
		        );

				break;

			case 'agency':				
				for ($b=0; $b < sizeof($typeSelect); $b++) { 
		            for ($m=0; $m < sizeof($month); $m++) {
            	
	                    $currentAdSales[$b][$m] = $this->defineValuesAgency($con, "ytd", $currency, $typeSelect[$b]['id'], $month[$m][1], $region, $value, $year);
	                    $previousAdSales[$b][$m] = $this->defineValuesAgency($con, "ytd", $currency, $typeSelect[$b]['id'], $month[$m][1], $region, $value,$pYear);                    		                     
		                $currentTarget[$b][$m] = 0.0;//$this->defineValuesAdvertiser($con, "plan_by_brand", $currency, $typeSelect[$b]['id'], $month[$m][1], $region, $value, $year, "TARGET");
		                $currentCorporate[$b][$m] = 0.0;//$this->defineValuesAdvertiser($con, "plan_by_brand", $currency, $typeSelect[$b]['id'], $month[$m][1], $region, $value, $year, "CORPORATE");
		                $currentSAP[$b][$m] = 0.0;//$this->defineValuesAdvertiser($con, "plan_by_brand", $currency, $typeSelect[$b]['id'], $month[$m][1], $region, $value, $year, "ACTUAL");
		                $previousSAP[$b][$m] = 0.0;//$this->defineValuesAdvertiser($con, "plan_by_brand", $currency, $typeSelect[$b]['id'], $month[$m][1], $region, $value, $pYear, "ACTUAL");
		                
		            }
		        }

		        $rtr = array( "typeSelect" => $typeSelect,
                              "currentAdSales" => $currentAdSales,
		        			  "previousAdSales" => $previousAdSales,
		        			  "currentTarget" => $currentTarget,
		        			  "currentCorporate" => $currentCorporate,
		        			  "currentSAP" => $currentSAP,
		        			  "previousSAP" => $previousSAP        			  
		        );

				break;

            case 'agencyGroup':              
                for ($b=0; $b < sizeof($typeSelect); $b++) { 
                    for ($m=0; $m < sizeof($month); $m++) {
                
                        $currentAdSales[$b][$m] = $this->defineValuesAgencyGroup($con, "ytd", $currency, $typeSelect[$b]['id'], $month[$m][1], $region, $value, $year);
                        $previousAdSales[$b][$m] = $this->defineValuesAgencyGroup($con, "ytd", $currency, $typeSelect[$b]['id'], $month[$m][1], $region, $value,$pYear);                                              
                        $currentTarget[$b][$m] = 0.0;//$this->defineValuesAdvertiser($con, "plan_by_brand", $currency, $typeSelect[$b]['id'], $month[$m][1], $region, $value, $year, "TARGET");
                        $currentCorporate[$b][$m] = 0.0;//$this->defineValuesAdvertiser($con, "plan_by_brand", $currency, $typeSelect[$b]['id'], $month[$m][1], $region, $value, $year, "CORPORATE");
                        $currentSAP[$b][$m] = 0.0;//$this->defineValuesAdvertiser($con, "plan_by_brand", $currency, $typeSelect[$b]['id'], $month[$m][1], $region, $value, $year, "ACTUAL");
                        $previousSAP[$b][$m] = 0.0;//$this->defineValuesAdvertiser($con, "plan_by_brand", $currency, $typeSelect[$b]['id'], $month[$m][1], $region, $value, $pYear, "ACTUAL");
                        
                    }
                }

                $rtr = array( "typeSelect" => $typeSelect,
                              "currentAdSales" => $currentAdSales,
                              "previousAdSales" => $previousAdSales,
                              "currentTarget" => $currentTarget,
                              "currentCorporate" => $currentCorporate,
                              "currentSAP" => $currentSAP,
                              "previousSAP" => $previousSAP                   
                );

                break;

			case 'ae':			
				

				for ($b=0; $b < sizeof($typeSelect); $b++) { 
		            for ($m=0; $m < sizeof($month); $m++) { 
		                
	                    $currentAdSales[$b][$m] = $this->defineValuesAE($con, "ytd", $currency, $typeSelect[$b], $month[$m][1], $region, $value,$year);
	                    $previousAdSales[$b][$m] = $this->defineValuesAE($con, "ytd", $currency, $typeSelect[$b], $month[$m][1], $region, $value,$pYear);
		                
		                $currentTarget[$b][$m] = $this->defineValuesAE($con, "plan_by_sales", $currency, $typeSelect[$b], $month[$m][1], $region, $value, $year, "TARGET");
		                $currentCorporate[$b][$m] = $this->defineValuesAE($con, "plan_by_sales", $currency, $typeSelect[$b], $month[$m][1], $region, $value, $year, "CORPORATE");
		                $currentSAP[$b][$m] = $this->defineValuesAE($con, "plan_by_sales", $currency, $typeSelect[$b], $month[$m][1], $region, $value, $year, "ACTUAL");
		                $previousSAP[$b][$m] = $this->defineValuesAE($con, "plan_by_sales", $currency, $typeSelect[$b], $month[$m][1], $region, $value, $pYear, "ACTUAL");
		            }
		        }

		        $rtr = array( "typeSelect" => $typeSelect,
                              "currentAdSales" => $currentAdSales,
		        			  "previousAdSales" => $previousAdSales,
		        			  "currentTarget" => $currentTarget,
		        			  "currentCorporate" => $currentCorporate,
		        			  "currentSAP" => $currentSAP,
		        			  "previousSAP" => $previousSAP        			  
		        );
		        
				break;
			
			default:
				$rtr = false;
				break;
		}

		return $rtr;
	}

	public function defineValuesAE($con, $table, $currency, $typeSelect, $month, $region, $value, $keyYear, $source=false){
        $p = new pRate();

        $year = $keyYear;

        if ($table != "plan_by_brand" && $table != "digital") {
            if ($currency[0]['name'] == "USD") {
                if($table == "cmaps"){
                    $pRate = $p->getPRateByRegionAndYear($con, array($region),array($keyYear));
                    $pRateSel = $p->getPRateByRegionAndYear($con, array($region),array($year));
                }else{
                    $pRate = 1.0;
                    $pRateSel = $pRate;
                }
            }else{
                if($table == "cmaps"){
                    $pRate = 1.0;
                    $pRateSel = $pRate;
                }else{
                    
                    $pRate = $p->getPRateByRegionAndYear($con, array($region),array($keyYear));
                    $pRateSel = $p->getPRateByRegionAndYear($con, array($region),array($year));
                    
                    $ccYear = date('Y');
                    $pRate = $p->getPRateByRegionAndYearIBMS($con, array($region), array($ccYear));
                    $pRateSel = $p->getPRateByRegionAndYearIBMS($con, array($region), array($ccYear));
                }                
            }    
        }else{            
            if ($currency[0]['name'] == "USD") {
                $pRate = 1.0;
                $pRateSel = $pRate;
            }else{
                $pRate = $p->getPRateByRegionAndYear($con,array($region),array($keyYear));
                $pRateSel = $p->getPRateByRegionAndYear($con,array($region),array($year));
            }            
        }

        switch ($table) {
            case 'ytd':
                $columns = array("sales_representant_office_id","sales_rep_id", "year", "month");
                $columnsValue = array($region,$typeSelect, $year, $month);
                $value .= "_revenue_prate";
                break;

            case 'plan_by_sales':

                $columns = array("region_id","type_of_revenue", "sales_rep_id", "year", "month", "currency_id");
                $columnsValue = array($region, $value, $typeSelect, $year, $month, 4);
                $value = "value";
                break;

            default:
                $columns = false;
                break;
        }

        if (!$columns) {
            $rtr = false;
        }else{
            $sql = new sql();

            $as = "sum";

            if ($table != "digital") {
                $where = $sql->where($columns, $columnsValue);
            }            

            if($table == "digital"){
                $table = "fw_digital";
            }

            $selectSum = $sql->selectSum($con, $value, $as, $table, null, $where);
            
            $tmp = $sql->fetchSum($selectSum, $as)["sum"];

            if($table == "cmaps"){                          
                $rtr = $tmp/$pRate;
            }else if($table == "plan_by_sales"){                          
                $rtr = $tmp*$pRateSel;
            }else{
                $rtr = $tmp*$pRate;
            }
        }
        return $rtr;		
    }  

    public function defineValuesAdvertiser($con, $table, $currency, $typeSelect, $month, $region, $value, $keyYear, $source=false){
        $p = new pRate();

        $year = $keyYear;

        if ($table != "plan_by_brand" && $table != "digital") {
            if ($currency[0]['name'] == "USD") {
                if($table == "cmaps"){
                    $pRate = $p->getPRateByRegionAndYear($con, array($region),array($keyYear));
                    $pRateSel = $p->getPRateByRegionAndYear($con, array($region),array($year));
                }else{
                    $pRate = 1.0;
                    $pRateSel = $pRate;
                }
            }else{
                if($table == "cmaps"){
                    $pRate = 1.0;
                    $pRateSel = $pRate;
                }else{
                    
                    $pRate = $p->getPRateByRegionAndYear($con, array($region),array($keyYear));
                    $pRateSel = $p->getPRateByRegionAndYear($con, array($region),array($year));
                    
                    $ccYear = date('Y');
                    $pRate = $p->getPRateByRegionAndYearIBMS($con, array($region), array($ccYear));
                    $pRateSel = $p->getPRateByRegionAndYearIBMS($con, array($region), array($ccYear));
                }                
            }    
        }else{            
            if ($currency[0]['name'] == "USD") {
                $pRate = 1.0;
                $pRateSel = $pRate;
            }else{
                $pRate = $p->getPRateByRegionAndYear($con,array($region),array($keyYear));
                $pRateSel = $p->getPRateByRegionAndYear($con,array($region),array($year));
            }            
        }

        switch ($table) {
            case 'ytd':
                $columns = array("sales_representant_office_id","client_id", "year", "month");
                $columnsValue = array($region,$typeSelect, $year, $month);
                $value .= "_revenue_prate";
                break;

            case 'plan_by_brand':

                $columns = array("sales_office_id", "source", "type_of_revenue", "sales_rep_id", "year", "month", "currency_id");
                $columnsValue = array($region, strtoupper($source), $value, $typeSelect, $year, $month, 4);
                $value = "revenue";
                break;

            /*
            case 'cmaps':
                $columns = array("brand_id", "year", "month");
                $columnsValue = array($brand, $year, $month);
                break;           

            case 'mini_header':
                $sql = new sql();

                $columns = array("sales_representant_office_id","campaign_currency_id","brand_id", "year", "month");
                $columnsValue = array($region, $currency[0]['id'], $brand, $year, $month);

                $value .= "_revenue";
                break;

            case 'digital':

                $columns = array("region_id", "brand_id", "year", "month");
                $columnsValue = array($region, $brand, $year, $month);
                $value .= "_revenue";
                if ($brand == '9') {
                    $where = "WHERE ( month = \"".$month."\" ) 
                                           AND ( year =  \" $year \")
                                           AND (region_id = \"".$region."\")
                                           AND (brand_id != '10')";
                }else{
                    $where = "WHERE ( month = \"".$month."\" ) 
                                           AND ( year =  \" $year \")
                                           AND (region_id = \"".$region."\")
                                           AND (brand_id = '".$brand."')";
                }

                break;

            
			*/
            default:
                $columns = false;
                break;
        }

        if (!$columns) {
            $rtr = false;
        }else{
            $sql = new sql();

            $as = "sum";

            if ($table != "digital") {
                $where = $sql->where($columns, $columnsValue);
            }            

            if($table == "digital"){
                $table = "fw_digital";
            }

            $selectSum = $sql->selectSum($con, $value, $as, $table, null, $where);
            
            $tmp = $sql->fetchSum($selectSum, $as)["sum"];

            if($table == "cmaps"){                          
                $rtr = $tmp/$pRate;
            }else if($table == "plan_by_brand"){                          
                $rtr = $tmp*$pRateSel;
            }else{
                $rtr = $tmp*$pRate;
            }
        }
        return $rtr;		
    }      

    public function defineValuesAgency($con, $table, $currency, $typeSelect, $month, $region, $value, $keyYear, $source=false){
        $p = new pRate();

        $year = $keyYear;

        if ($table != "plan_by_brand" && $table != "digital") {
            if ($currency[0]['name'] == "USD") {
                if($table == "cmaps"){
                    $pRate = $p->getPRateByRegionAndYear($con, array($region),array($keyYear));
                    $pRateSel = $p->getPRateByRegionAndYear($con, array($region),array($year));
                }else{
                    $pRate = 1.0;
                    $pRateSel = $pRate;
                }
            }else{
                if($table == "cmaps"){
                    $pRate = 1.0;
                    $pRateSel = $pRate;
                }else{
                    
                    $pRate = $p->getPRateByRegionAndYear($con, array($region),array($keyYear));
                    $pRateSel = $p->getPRateByRegionAndYear($con, array($region),array($year));
                    
                    $ccYear = date('Y');
                    $pRate = $p->getPRateByRegionAndYearIBMS($con, array($region), array($ccYear));
                    $pRateSel = $p->getPRateByRegionAndYearIBMS($con, array($region), array($ccYear));
                }                
            }    
        }else{            
            if ($currency[0]['name'] == "USD") {
                $pRate = 1.0;
                $pRateSel = $pRate;
            }else{
                $pRate = $p->getPRateByRegionAndYear($con,array($region),array($keyYear));
                $pRateSel = $p->getPRateByRegionAndYear($con,array($region),array($year));
            }            
        }

        switch ($table) {
            case 'ytd':
                $columns = array("sales_representant_office_id","agency_id", "year", "month");
                $columnsValue = array($region,$typeSelect, $year, $month);
                $value .= "_revenue_prate";
                break;

            case 'plan_by_brand':

                $columns = array("sales_office_id", "source", "type_of_revenue", "sales_rep_id", "year", "month", "currency_id");
                $columnsValue = array($region, strtoupper($source), $value, $typeSelect, $year, $month, 4);
                $value = "revenue";
                break;

            /*
            case 'cmaps':
                $columns = array("brand_id", "year", "month");
                $columnsValue = array($brand, $year, $month);
                break;           

            case 'mini_header':
                $sql = new sql();

                $columns = array("sales_representant_office_id","campaign_currency_id","brand_id", "year", "month");
                $columnsValue = array($region, $currency[0]['id'], $brand, $year, $month);

                $value .= "_revenue";
                break;

            case 'digital':

                $columns = array("region_id", "brand_id", "year", "month");
                $columnsValue = array($region, $brand, $year, $month);
                $value .= "_revenue";
                if ($brand == '9') {
                    $where = "WHERE ( month = \"".$month."\" ) 
                                           AND ( year =  \" $year \")
                                           AND (region_id = \"".$region."\")
                                           AND (brand_id != '10')";
                }else{
                    $where = "WHERE ( month = \"".$month."\" ) 
                                           AND ( year =  \" $year \")
                                           AND (region_id = \"".$region."\")
                                           AND (brand_id = '".$brand."')";
                }

                break;

            
			*/
            default:
                $columns = false;
                break;
        }

        if (!$columns) {
            $rtr = false;
        }else{
            $sql = new sql();

            $as = "sum";

            if ($table != "digital") {
                $where = $sql->where($columns, $columnsValue);
            }            

            if($table == "digital"){
                $table = "fw_digital";
            }

            $selectSum = $sql->selectSum($con, $value, $as, $table, null, $where);
            
            $tmp = $sql->fetchSum($selectSum, $as)["sum"];

            if($table == "cmaps"){                          
                $rtr = $tmp/$pRate;
            }else if($table == "plan_by_brand"){                          
                $rtr = $tmp*$pRateSel;
            }else{
                $rtr = $tmp*$pRate;
            }
        }
        return $rtr;		
    }      

    public function defineValuesAgencyGroup($con, $table, $currency, $typeSelect, $month, $region, $value, $keyYear, $source=false){
        $p = new pRate();

        $year = $keyYear;

        if ($table != "plan_by_brand" && $table != "digital") {
            if ($currency[0]['name'] == "USD") {
                if($table == "cmaps"){
                    $pRate = $p->getPRateByRegionAndYear($con, array($region),array($keyYear));
                    $pRateSel = $p->getPRateByRegionAndYear($con, array($region),array($year));
                }else{
                    $pRate = 1.0;
                    $pRateSel = $pRate;
                }
            }else{
                if($table == "cmaps"){
                    $pRate = 1.0;
                    $pRateSel = $pRate;
                }else{
                    
                    $pRate = $p->getPRateByRegionAndYear($con, array($region),array($keyYear));
                    $pRateSel = $p->getPRateByRegionAndYear($con, array($region),array($year));
                    
                    $ccYear = date('Y');
                    $pRate = $p->getPRateByRegionAndYearIBMS($con, array($region), array($ccYear));
                    $pRateSel = $p->getPRateByRegionAndYearIBMS($con, array($region), array($ccYear));
                }                
            }    
        }else{            
            if ($currency[0]['name'] == "USD") {
                $pRate = 1.0;
                $pRateSel = $pRate;
            }else{
                $pRate = $p->getPRateByRegionAndYear($con,array($region),array($keyYear));
                $pRateSel = $p->getPRateByRegionAndYear($con,array($region),array($year));
            }            
        }

        switch ($table) {
            case 'ytd':
                $columns = array("sales_representant_office_id","agency_group_id", "year", "month");
                $columnsValue = array($region,$typeSelect, $year, $month);
                $value .= "_revenue_prate";
                break;

            case 'plan_by_brand':

                $columns = array("sales_office_id", "source", "type_of_revenue", "sales_rep_id", "year", "month", "currency_id");
                $columnsValue = array($region, strtoupper($source), $value, $typeSelect, $year, $month, 4);
                $value = "revenue";
                break;

            /*
            case 'cmaps':
                $columns = array("brand_id", "year", "month");
                $columnsValue = array($brand, $year, $month);
                break;           

            case 'mini_header':
                $sql = new sql();

                $columns = array("sales_representant_office_id","campaign_currency_id","brand_id", "year", "month");
                $columnsValue = array($region, $currency[0]['id'], $brand, $year, $month);

                $value .= "_revenue";
                break;

            case 'digital':

                $columns = array("region_id", "brand_id", "year", "month");
                $columnsValue = array($region, $brand, $year, $month);
                $value .= "_revenue";
                if ($brand == '9') {
                    $where = "WHERE ( month = \"".$month."\" ) 
                                           AND ( year =  \" $year \")
                                           AND (region_id = \"".$region."\")
                                           AND (brand_id != '10')";
                }else{
                    $where = "WHERE ( month = \"".$month."\" ) 
                                           AND ( year =  \" $year \")
                                           AND (region_id = \"".$region."\")
                                           AND (brand_id = '".$brand."')";
                }

                break;

            
            */
            default:
                $columns = false;
                break;
        }

        if (!$columns) {
            $rtr = false;
        }else{
            $sql = new sql();

            $as = "sum";

            if ($table != "digital") {
                $where = $sql->where($columns, $columnsValue);
            }            

            if($table == "digital"){
                $table = "fw_digital";
            }

            $join = "LEFT JOIN agency a ON (y.agency_id = a.ID)
                     LEFT JOIN agency_group ag ON (a.agency_group_id = ag.ID)
                    ";

            if($join){
                $table .= " y";
            }

            $selectSum = $sql->selectSum($con, $value, $as, $table, $join, $where);
            
            $tmp = $sql->fetchSum($selectSum, $as)["sum"];

            if($table == "cmaps"){                          
                $rtr = $tmp/$pRate;
            }else if($table == "plan_by_brand"){                          
                $rtr = $tmp*$pRateSel;
            }else{
                $rtr = $tmp*$pRate;
            }
        }
        return $rtr;        
    }
}
