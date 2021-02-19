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

    public function defineValuesBrand($con, $table, $currency, $brand, $month, $region, $value, $keyYear, $source=false){

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

            case 'cmaps':
                $columns = array("brand_id", "year", "month");
                $columnsValue = array($brand, $year, $month);
                break;

            case 'ytd':
                $columns = array("sales_representant_office_id","brand_id", "year", "month");
                if($brand == 9){
                    $brandArray = array(9,13,14,15,16);
                    $columnsValue = array($region, $brandArray, $year, $month);
                }else{
                    $columnsValue = array($region, $brand, $year, $month);
                }
                $value .= "_revenue_prate";
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

            case 'plan_by_brand':

                $columns = array("sales_office_id", "source", "type_of_revenue", "brand_id", "year", "month", "currency_id");
                $columnsValue = array($region, strtoupper($source), $value, $brand, $year, $month, 4);
                $value = "revenue";
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
            }else if($table == "plan_by_brand"){                          
                $rtr = $tmp*$pRateSel;
            }else{
                $rtr = $tmp*$pRate;
            }           


        }

        return $rtr;
		
    }    




    public function addDN($mtx){
		
		for ($j=0; $j < 18; $j++) { 
			$mtxDN['previousAdSales'][$j] = 0.0;
			$mtxDN['previousSAP'][$j] = 0.0;
			$mtxDN['currentTarget'][$j] = 0.0;
			$mtxDN['currentAdSales'][$j] = 0.0;
			$mtxDN['currentSAP'][$j] = 0.0;
			$mtxDN['currentCorporate'][$j] = 0.0;
		}
		
		for ($j=0; $j < 18; $j++) { 
			for ($k=0; $k < sizeof($mtx['previousAdSales']); $k++) { 		
				$mtxDN['previousAdSales'][$j] += $mtx['previousAdSales'][$k][$j];
				$mtxDN['previousSAP'][$j] += $mtx['previousSAP'][$k][$j];
				$mtxDN['currentTarget'][$j] += $mtx['currentTarget'][$k][$j];
				$mtxDN['currentAdSales'][$j] += $mtx['currentAdSales'][$k][$j];
				$mtxDN['currentSAP'][$j] += $mtx['currentSAP'][$k][$j];
				$mtxDN['currentCorporate'][$j] += $mtx['currentCorporate'][$k][$j];					
			}
		}
		return $mtxDN;
	}


	public function assemble($mtx){
		$pivot = date('m');
		$mtx = $this->addTotal($mtx);
		$mtx = $this->sumYTD($mtx,$pivot);
		$mtx = $this->addQuarters($mtx);
		return $mtx;
	}

    public function newOrder($mtx){

        $typeSelect = $mtx["typeSelect"];
        $currentAdSales = $mtx["currentAdSales"];
        $previousAdSales = $mtx["previousAdSales"];
        $currentTarget = $mtx["currentTarget"];
        $currentCorporate = $mtx["currentCorporate"];
        $currentSAP = $mtx["currentSAP"];
        $previousSAP = $mtx["previousSAP"]; 

        for ($t=0; $t < sizeof($typeSelect); $t++) { 
            $newMtx[$t]['typeSelect'] = $typeSelect[$t];
            $newMtx[$t]['totalYear'] = $currentAdSales[$t][12];
            $newMtx[$t]['currentAdSales'] = $currentAdSales[$t];
            $newMtx[$t]['previousAdSales'] = $previousAdSales[$t];
            $newMtx[$t]['currentTarget'] = $currentTarget[$t];
            $newMtx[$t]['currentCorporate'] = $currentCorporate[$t];
            $newMtx[$t]['currentSAP'] = $currentSAP[$t];
            $newMtx[$t]['previousSAP'] = $previousSAP[$t];

        }

        usort($newMtx, array($this,'orderValueYear'));

        return $newMtx;
    }

    public static function orderValueYear($a, $b){
        if ($a == $b)
            return 0;
        
        return ($a['totalYear'] > $b['totalYear']) ? -1 : 1;
    }

	public function addQuarters($mtx){
		for ($i=0; $i < sizeof($mtx['previousAdSales']); $i++) { 
				$sum['previousAdSales']['q1'] = $mtx['previousAdSales'][$i][0] + $mtx['previousAdSales'][$i][1] + $mtx['previousAdSales'][$i][2];
				$sum['previousAdSales']['q2'] = $mtx['previousAdSales'][$i][3] + $mtx['previousAdSales'][$i][4] + $mtx['previousAdSales'][$i][5];
				$sum['previousAdSales']['q3'] = $mtx['previousAdSales'][$i][6] + $mtx['previousAdSales'][$i][7] + $mtx['previousAdSales'][$i][8];
				$sum['previousAdSales']['q4'] = $mtx['previousAdSales'][$i][9] + $mtx['previousAdSales'][$i][10] + $mtx['previousAdSales'][$i][11];

				$sum['previousSAP']['q1'] = $mtx['previousSAP'][$i][0] + $mtx['previousSAP'][$i][1] + $mtx['previousSAP'][$i][2];
				$sum['previousSAP']['q2'] = $mtx['previousSAP'][$i][3] + $mtx['previousSAP'][$i][4] + $mtx['previousSAP'][$i][5];
				$sum['previousSAP']['q3'] = $mtx['previousSAP'][$i][6] + $mtx['previousSAP'][$i][7] + $mtx['previousSAP'][$i][8];
				$sum['previousSAP']['q4'] = $mtx['previousSAP'][$i][9] + $mtx['previousSAP'][$i][10] + $mtx['previousSAP'][$i][11];

				$sum['currentTarget']['q1'] = $mtx['currentTarget'][$i][0] + $mtx['currentTarget'][$i][1] + $mtx['currentTarget'][$i][2];
				$sum['currentTarget']['q2'] = $mtx['currentTarget'][$i][3] + $mtx['currentTarget'][$i][4] + $mtx['currentTarget'][$i][5];
				$sum['currentTarget']['q3'] = $mtx['currentTarget'][$i][6] + $mtx['currentTarget'][$i][7] + $mtx['currentTarget'][$i][8];
				$sum['currentTarget']['q4'] = $mtx['currentTarget'][$i][9] + $mtx['currentTarget'][$i][10] + $mtx['currentTarget'][$i][11];

				$sum['currentAdSales']['q1'] = $mtx['currentAdSales'][$i][0] + $mtx['currentAdSales'][$i][1] + $mtx['currentAdSales'][$i][2];
				$sum['currentAdSales']['q2'] = $mtx['currentAdSales'][$i][3] + $mtx['currentAdSales'][$i][4] + $mtx['currentAdSales'][$i][5];
				$sum['currentAdSales']['q3'] = $mtx['currentAdSales'][$i][6] + $mtx['currentAdSales'][$i][7] + $mtx['currentAdSales'][$i][8];
				$sum['currentAdSales']['q4'] = $mtx['currentAdSales'][$i][9] + $mtx['currentAdSales'][$i][10] + $mtx['currentAdSales'][$i][11];

				$sum['currentSAP']['q1'] = $mtx['currentSAP'][$i][0] + $mtx['currentSAP'][$i][1] + $mtx['currentSAP'][$i][2];
				$sum['currentSAP']['q2'] = $mtx['currentSAP'][$i][3] + $mtx['currentSAP'][$i][4] + $mtx['currentSAP'][$i][5];
				$sum['currentSAP']['q3'] = $mtx['currentSAP'][$i][6] + $mtx['currentSAP'][$i][7] + $mtx['currentSAP'][$i][8];
				$sum['currentSAP']['q4'] = $mtx['currentSAP'][$i][9] + $mtx['currentSAP'][$i][10] + $mtx['currentSAP'][$i][11];

				$sum['currentCorporate']['q1'] = $mtx['currentCorporate'][$i][0] + $mtx['currentCorporate'][$i][1] + $mtx['currentCorporate'][$i][2];
				$sum['currentCorporate']['q2'] = $mtx['currentCorporate'][$i][3] + $mtx['currentCorporate'][$i][4] + $mtx['currentCorporate'][$i][5];
				$sum['currentCorporate']['q3'] = $mtx['currentCorporate'][$i][6] + $mtx['currentCorporate'][$i][7] + $mtx['currentCorporate'][$i][8];
				$sum['currentCorporate']['q4'] = $mtx['currentCorporate'][$i][9] + $mtx['currentCorporate'][$i][10] + $mtx['currentCorporate'][$i][11];



			array_push($mtx['previousAdSales'][$i],$sum['previousAdSales']['q1']);
			array_push($mtx['previousAdSales'][$i],$sum['previousAdSales']['q2']);
			array_push($mtx['previousAdSales'][$i],$sum['previousAdSales']['q3']);
			array_push($mtx['previousAdSales'][$i],$sum['previousAdSales']['q4']);

			array_push($mtx['previousSAP'][$i],$sum['previousSAP']['q1']);
			array_push($mtx['previousSAP'][$i],$sum['previousSAP']['q2']);
			array_push($mtx['previousSAP'][$i],$sum['previousSAP']['q3']);
			array_push($mtx['previousSAP'][$i],$sum['previousSAP']['q4']);
			
			array_push($mtx['currentTarget'][$i],$sum['currentTarget']['q1']);
			array_push($mtx['currentTarget'][$i],$sum['currentTarget']['q2']);
			array_push($mtx['currentTarget'][$i],$sum['currentTarget']['q3']);
			array_push($mtx['currentTarget'][$i],$sum['previousAdSales']['q4']);

			array_push($mtx['currentAdSales'][$i],$sum['currentAdSales']['q1']);
			array_push($mtx['currentAdSales'][$i],$sum['currentAdSales']['q2']);
			array_push($mtx['currentAdSales'][$i],$sum['currentAdSales']['q3']);
			array_push($mtx['currentAdSales'][$i],$sum['currentAdSales']['q4']);

			array_push($mtx['currentSAP'][$i],$sum['currentSAP']['q1']);
			array_push($mtx['currentSAP'][$i],$sum['currentSAP']['q2']);
			array_push($mtx['currentSAP'][$i],$sum['currentSAP']['q3']);
			array_push($mtx['currentSAP'][$i],$sum['currentSAP']['q4']);

			array_push($mtx['currentCorporate'][$i],$sum['currentCorporate']['q1']);
			array_push($mtx['currentCorporate'][$i],$sum['currentCorporate']['q2']);
			array_push($mtx['currentCorporate'][$i],$sum['currentCorporate']['q3']);
			array_push($mtx['currentCorporate'][$i],$sum['currentCorporate']['q4']);
		}	

		return $mtx;

	}

	public function sumYTD($mtx,$pivot){
		
		for ($i=0; $i < sizeof($mtx['previousAdSales']); $i++) { 
				$sum['previousAdSales'][$i] = 0.0;
				$sum['previousSAP'][$i] = 0.0;
				$sum['currentTarget'][$i] = 0.0;
				$sum['currentAdSales'][$i] = 0.0;
				$sum['currentSAP'][$i] = 0.0;
				$sum['currentCorporate'][$i] = 0.0;
			for ($j=0; $j < $pivot; $j++) { 
				$sum['previousAdSales'][$i] += $mtx['previousAdSales'][$i][$j];
				$sum['previousSAP'][$i] += $mtx['previousSAP'][$i][$j];
				$sum['currentTarget'][$i] += $mtx['currentTarget'][$i][$j];
				$sum['currentAdSales'][$i] += $mtx['currentAdSales'][$i][$j];
				$sum['currentSAP'][$i] += $mtx['currentSAP'][$i][$j];
				$sum['currentCorporate'][$i] += $mtx['currentCorporate'][$i][$j];		
			}	

			array_push($mtx['previousAdSales'][$i],$sum['previousAdSales'][$i]);
			array_push($mtx['previousSAP'][$i],$sum['previousSAP'][$i]);
			array_push($mtx['currentTarget'][$i],$sum['currentTarget'][$i]);
			array_push($mtx['currentAdSales'][$i],$sum['currentAdSales'][$i]);
			array_push($mtx['currentSAP'][$i],$sum['currentSAP'][$i]);
			array_push($mtx['currentCorporate'][$i],$sum['currentCorporate'][$i]);
		}	

		return $mtx;
	}

	public function addTotal($mtx){
		
		for ($i=0; $i < sizeof($mtx['previousAdSales']); $i++) { 
				$sum['previousAdSales'][$i] = 0.0;
				$sum['previousSAP'][$i] = 0.0;
				$sum['currentTarget'][$i] = 0.0;
				$sum['currentAdSales'][$i] = 0.0;
				$sum['currentSAP'][$i] = 0.0;
				$sum['currentCorporate'][$i] = 0.0;
			for ($j=0; $j < sizeof($mtx['previousAdSales'][$i]); $j++) { 
				$sum['previousAdSales'][$i] += $mtx['previousAdSales'][$i][$j];
				$sum['previousSAP'][$i] += $mtx['previousSAP'][$i][$j];
				$sum['currentTarget'][$i] += $mtx['currentTarget'][$i][$j];
				$sum['currentAdSales'][$i] += $mtx['currentAdSales'][$i][$j];
				$sum['currentSAP'][$i] += $mtx['currentSAP'][$i][$j];
				$sum['currentCorporate'][$i] += $mtx['currentCorporate'][$i][$j];		
			}	

			array_push($mtx['previousAdSales'][$i],$sum['previousAdSales'][$i]);
			array_push($mtx['previousSAP'][$i],$sum['previousSAP'][$i]);
			array_push($mtx['currentTarget'][$i],$sum['currentTarget'][$i]);
			array_push($mtx['currentAdSales'][$i],$sum['currentAdSales'][$i]);
			array_push($mtx['currentSAP'][$i],$sum['currentSAP'][$i]);
			array_push($mtx['currentCorporate'][$i],$sum['currentCorporate'][$i]);
		}	

		return $mtx;
	}
}
