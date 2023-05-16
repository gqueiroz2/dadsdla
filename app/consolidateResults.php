<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\brand;


class consolidateResults extends Model{

    public function typeSelectN($con,$re,$region){

        for ($r=0; $r < sizeof($region); $r++) { 
            $tmp[$r] = $re->getRegion($con,array($region[$r]))[0];
        }

        return $tmp;

    }

    public function constructOffice($con,$currency,$month,$region,$value,$years,$company){
    	$b = new brand();

    	for ($c=0; $c < sizeof($company); $c++) { 
       		if ($company[$c] == 'dc') {
       			$company[$c] = '1';
       		}elseif ($company[$c] == 'spt') {
       			$company[$c] = '2';
       		}else{
       			$company[$c] = '3';
       		}
        }  		
    	
    	$brand = $b->getBrandByGroup($con,$company);
    	//var_dump($region);
        $form = "bts";
        $cYear = $years[0];
        $pYear = $years[1];    
        
        
        if ($pYear >= '2022' && $region[0] == '1') {
        	for ($r=0; $r < sizeof($region); $r++) { 
            	for ($m=0; $m < sizeof($month); $m++) {  
	                //$currentAleph[$r][$m] = $this->defineValuesOffice($con, "aleph", $currency, $month[$m][1], $region[$r], $value, $cYear,null,null,$company); 
	                //$previousAleph[$r][$m] = $this->defineValuesOffice($con, "aleph", $currency, $month[$m][1], $region[$r], $value, $pYear,null,null,$company); 
	                $currentWBD[$r][$m] = $this->defineValuesOffice($con, "wbd", $currency, $month[$m][1], $region[$r], $value, $cYear,null,null,$company);               	
	                $currentAdSales[$r][$m] = $currentWBD[$r][$m];
	                $previousWBD[$r][$m] = $this->defineValuesOffice($con, "wbd", $currency, $month[$m][1], $region[$r], $value,$pYear,null,null,$company);
	                $previousAdSales[$r][$m] = $previousWBD[$r][$m];
	            	$currentTarget[$r][$m] = $this->defineValuesOffice($con, "plan_by_brand", $currency, $month[$m][1], $region[$r], $value, $cYear, "TARGET",$brand,$company);
	            	$currentCorporate[$r][$m] = $this->defineValuesOffice($con, "plan_by_brand", $currency, $month[$m][1], $region[$r], $value, $cYear, "CORPORATE",$brand,$company);
	            	$currentSAP[$r][$m] = $this->defineValuesOffice($con, "plan_by_brand", $currency, $month[$m][1], $region[$r], $value, $cYear, "ACTUAL",$brand,$company);
	            	$previousSAP[$r][$m] = $this->defineValuesOffice($con, "plan_by_brand", $currency, $month[$m][1], $region[$r], $value, $pYear, "ACTUAL",$brand,$company);

	            	for ($c=0; $c <sizeof($company); $c++) { 

            			$currentCompany[$r][$m][$c] = $this->defineValuesOfficeCompany($con, "wbd", $currency, $month[$m][1], $region[$r], $value, $cYear,null,null,$company[$c]);
            			$previousCompany[$r][$m][$c] = $this->defineValuesOfficeCompany($con, "wbd", $currency, $month[$m][1], $region[$r], $value,$pYear,null,null,$company[$c]);
		            	$currentTargetCompany[$r][$m][$c] = $this->defineValuesOfficeCompany($con, "plan_by_brand", $currency, $month[$m][1], $region[$r], $value, $cYear, "TARGET",$brand,$company[$c]);
		            	$currentCorporateCompany[$r][$m][$c] = $this->defineValuesOfficeCompany($con, "plan_by_brand", $currency, $month[$m][1], $region[$r], $value, $cYear, "CORPORATE",$brand,$company[$c]);
		            	$currentSAPCompany[$r][$m][$c] = $this->defineValuesOfficeCompany($con, "plan_by_brand", $currency, $month[$m][1], $region[$r], $value, $cYear, "ACTUAL",$brand,$company[$c]);
		            	$previousSAPCompany[$r][$m][$c] = $this->defineValuesOfficeCompany($con, "plan_by_brand", $currency, $month[$m][1], $region[$r], $value, $pYear, "ACTUAL",$brand,$company[$c]);
		            
            		}	
	          	}
	        }
        }else{
        	for ($r=0; $r < sizeof($region); $r++) { 
            	for ($m=0; $m < sizeof($month); $m++) {  
	                $currentAleph[$r][$m] = $this->defineValuesOffice($con, "aleph", $currency, $month[$m][1], $region[$r], $value, $cYear,null,null,$company); 
	                $previousAleph[$r][$m] = $this->defineValuesOffice($con, "aleph", $currency, $month[$m][1], $region[$r], $value, $pYear,null,null,$company); 
	                $currentYtd[$r][$m] = $this->defineValuesOffice($con, "ytd", $currency, $month[$m][1], $region[$r], $value, $cYear,null,null,$company);               	
	                $currentAdSales[$r][$m] = $currentAleph[$r][$m] + $currentYtd[$r][$m];
	                $previousYtd[$r][$m] = $this->defineValuesOffice($con, "ytd", $currency, $month[$m][1], $region[$r], $value,$pYear,null,null,$company);
	                $previousAdSales[$r][$m] = $previousAleph[$r][$m] + $previousYtd[$r][$m];
	            	$currentTarget[$r][$m] = $this->defineValuesOffice($con, "plan_by_brand", $currency, $month[$m][1], $region[$r], $value, $cYear, "TARGET",$brand,$company);
	            	$currentCorporate[$r][$m] = $this->defineValuesOffice($con, "plan_by_brand", $currency, $month[$m][1], $region[$r], $value, $cYear, "CORPORATE",$brand,$company);
	            	$currentSAP[$r][$m] = $this->defineValuesOffice($con, "plan_by_brand", $currency, $month[$m][1], $region[$r], $value, $cYear, "ACTUAL",$brand,$company);
	            	$previousSAP[$r][$m] = $this->defineValuesOffice($con, "plan_by_brand", $currency, $month[$m][1], $region[$r], $value, $pYear, "ACTUAL",$brand,$company);	                
            	}
        	}	
        }
        //var_dump($totalCurrent);

        $rtr = array( 
                      "typeSelect" => $region,
                      "currentAdSales" => $currentAdSales,
                      "previousAdSales" => $previousAdSales,
                      "currentTarget" => $currentTarget,
                      "currentCorporate" => $currentCorporate,
                      "currentSAP" => $currentSAP,
                      "previousSAP" => $previousSAP,
                      "currentCompany" => $currentCompany,
					  "previousCompany" => $previousCompany,
					  "currentTargetCompany" => $currentTargetCompany, 
					  "currentCorporateCompany" => $currentCorporateCompany,
					  "currentSAPCompany" => $currentSAPCompany,
					  "previousSAPCompany" => $previousSAPCompany
        );
        //var_dump($rtr['currentCorporateCompany']);
        return $rtr;
        
    }

	public function construct($con,$currency,$month,$type,$typeSelect,$region,$value,$brand,$report){
		$form = "bts";
		$year = date('Y');
		$pYear = $year - 1;
		$b = new brand();
		switch ($type) {
			case 'brand':	
			//var_dump($typeSelect);

				if ($year >= '2022' || $pYear >= '2022' && $region == '1') {
					for ($b=0; $b < sizeof($typeSelect); $b++) {							
			            for ($m=0; $m < sizeof($month); $m++) { 
			            	
			            	//var_dump("=====");
			            	
			                if ($typeSelect[$b][1] != 'ONL' && $typeSelect[$b][1] != 'VIX') {
	                            //$currentAleph[$b][$m] = $this->defineValuesBrand($con, "aleph", $currency, $typeSelect[$b][0], $month[$m][1], $region, $value,$year);
	                            //$previousAleph[$b][$m] = $this->defineValuesBrand($con, "aleph", $currency, $typeSelect[$b][0], $month[$m][1], $region, $value,$pYear);
			                    $currentWBD[$b][$m] = $this->defineValuesBrand($con, "wbd", $currency, $typeSelect[$b][0], $month[$m][1], $region, $value,$year,false,false,$report);
	                            $currentAdSales[$b][$m] = $currentWBD[$b][$m];
	                            //$previousAleph[$b][$m] = $this->defineValuesBrand($con, "aleph", $currency, $typeSelect[$b][0], $month[$m][1], $region, $value,$pYear,$report);
			                    $previousWBD[$b][$m] = $this->defineValuesBrand($con, "wbd", $currency, $typeSelect[$b][0], $month[$m][1], $region, $value,$pYear,false,false,$report);
	                            $previousAdSales[$b][$m] = $previousWBD[$b][$m];
			                }else{
			                    $currentAdSales[$b][$m] = $this->defineValuesBrand($con, "ytd", $currency, $typeSelect[$b][0], $month[$m][1], $region, $value, $year,false,false,$report);
	                            $currentAdSales[$b][$m] = $this->defineValuesBrand($con, "ytd", $currency, $typeSelect[$b][0], $month[$m][1], $region, $value, $year,false,false,$report);
			                    $previousAdSales[$b][$m] = $this->defineValuesBrand($con, "ytd", $currency, $typeSelect[$b][0], $month[$m][1], $region, $value,$pYear,false,false,$report);                    
			                }        
			                $currentTarget[$b][$m] = $this->defineValuesBrand($con, "plan_by_brand", $currency, $typeSelect[$b][0], $month[$m][1], $region, $value, $year, "TARGET", $typeSelect[$b][2],$report);
			                $currentCorporate[$b][$m] = $this->defineValuesBrand($con, "plan_by_brand", $currency, $typeSelect[$b][0], $month[$m][1], $region, $value, $year, "CORPORATE",$typeSelect[$b][2],$report);
			                $currentSAP[$b][$m] = $this->defineValuesBrand($con, "plan_by_brand", $currency, $typeSelect[$b][0], $month[$m][1], $region, $value, $year, "ACTUAL",$typeSelect[$b][2],$report);
			                $previousSAP[$b][$m] = $this->defineValuesBrand($con, "plan_by_brand", $currency, $typeSelect[$b][0], $month[$m][1], $region, $value, $pYear, "ACTUAL",$typeSelect[$b][2],$report);
			            }
			        }
			    }else{
			    	for ($b=0; $b < sizeof($typeSelect); $b++) {		           
			            for ($m=0; $m < sizeof($month); $m++) { 
			            	
			            	//var_dump("=====");
			            	
			                if ($typeSelect[$b][1] != 'ONL' && $typeSelect[$b][1] != 'VIX') {
	                            $currentAleph[$b][$m] = $this->defineValuesBrand($con, "aleph", $currency, $typeSelect[$b][0], $month[$m][1], $region, $value,$year,false,false,$report);
	                            $previousAleph[$b][$m] = $this->defineValuesBrand($con, "aleph", $currency, $typeSelect[$b][0], $month[$m][1], $region, $value,$pYear,false,false,$report);
			                    $currentYtd[$b][$m] = $this->defineValuesBrand($con, "ytd", $currency, $typeSelect[$b][0], $month[$m][1], $region, $value,$year,false,false,$report);
	                            $currentAdSales[$b][$m] = $currentAleph[$b][$m] + $currentYtd[$b][$m];
	                            $previousAleph[$b][$m] = $this->defineValuesBrand($con, "aleph", $currency, $typeSelect[$b][0], $month[$m][1], $region, $value,$pYear,false,false,$report);
			                    $previousYtd[$b][$m] = $this->defineValuesBrand($con, "ytd", $currency, $typeSelect[$b][0], $month[$m][1], $region, $value,$pYear,false,false,$report);
	                            $previousAdSales[$b][$m] = $previousAleph[$b][$m] + $previousYtd[$b][$m];
			                }else{
			                    $currentAdSales[$b][$m] = $this->defineValuesBrand($con, "ytd", $currency, $typeSelect[$b][0], $month[$m][1], $region, $value, $year,false,false,$report);
	                            $currentAdSales[$b][$m] = $this->defineValuesBrand($con, "ytd", $currency, $typeSelect[$b][0], $month[$m][1], $region, $value, $year,false,false,$report);
			                    $previousAdSales[$b][$m] = $this->defineValuesBrand($con, "ytd", $currency, $typeSelect[$b][0], $month[$m][1], $region, $value,$pYear,false,false,$report);                    
			                }        
			                $currentTarget[$b][$m] = $this->defineValuesBrand($con, "plan_by_brand", $currency, $typeSelect[$b][0], $month[$m][1], $region, $value, $year, "TARGET", $typeSelect[$b][2],$report);
			                $currentCorporate[$b][$m] = $this->defineValuesBrand($con, "plan_by_brand", $currency, $typeSelect[$b][0], $month[$m][1], $region, $value, $year, "CORPORATE",$typeSelect[$b][2],$report);
			                $currentSAP[$b][$m] = $this->defineValuesBrand($con, "plan_by_brand", $currency, $typeSelect[$b][0], $month[$m][1], $region, $value, $year, "ACTUAL",$typeSelect[$b][2],$report);
			                $previousSAP[$b][$m] = $this->defineValuesBrand($con, "plan_by_brand", $currency, $typeSelect[$b][0], $month[$m][1], $region, $value, $pYear, "ACTUAL",$typeSelect[$b][2],$report);
			            }
			        }
			    }
                //var_dump($test);
                //var_dump($currentAdSales);
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

				if ($year >= '2022' || $pYear == '2022' && $region == '1') {
					for ($b=0; $b < sizeof($typeSelect); $b++) { 
			            for ($m=0; $m < sizeof($month); $m++) {
	                        //$currentAleph[$b][$m] = $this->defineValuesAdvertiser($con, "aleph", $currency, $typeSelect[$b]['id'], $month[$m][1], $region, $value,$year,$report);
	                        //$previousAleph[$b][$m] = $this->defineValuesAdvertiser($con, "aleph", $currency, $typeSelect[$b]['id'], $month[$m][1], $region, $value,$pYear,$report);            	
		                    $currentWBD[$b][$m] = $this->defineValuesAdvertiser($con, "wbd", $currency, $typeSelect[$b]['id'], $month[$m][1], $region, $value, $year,false,$report);
		                    $previousWBD[$b][$m] = $this->defineValuesAdvertiser($con, "wbd", $currency, $typeSelect[$b]['id'], $month[$m][1], $region, $value,$pYear,false,$report);    
	                        $currentAdSales[$b][$m] = $currentWBD[$b][$m];
	                        $previousAdSales[$b][$m] = $previousWBD[$b][$m]; 
			                $currentTarget[$b][$m] = 0.0;//$this->defineValuesAdvertiser($con, "plan_by_brand", $currency, $typeSelect[$b]['id'], $month[$m][1], $region, $value, $year, "TARGET");
			                $currentCorporate[$b][$m] = 0.0;//$this->defineValuesAdvertiser($con, "plan_by_brand", $currency, $typeSelect[$b]['id'], $month[$m][1], $region, $value, $year, "CORPORATE");
			                $currentSAP[$b][$m] = 0.0;//$this->defineValuesAdvertiser($con, "plan_by_brand", $currency, $typeSelect[$b]['id'], $month[$m][1], $region, $value, $year, "ACTUAL");
			                $previousSAP[$b][$m] = 0.0;//$this->defineValuesAdvertiser($con, "plan_by_brand", $currency, $typeSelect[$b]['id'], $month[$m][1], $region, $value, $pYear, "ACTUAL");
			                
			            }
			        }
			    }else{
			    	$currentAleph[$b][$m] = $this->defineValuesAdvertiser($con, "aleph", $currency, $typeSelect[$b]['id'], $month[$m][1], $region, $value,$year,false,$report);
                    $previousAleph[$b][$m] = $this->defineValuesAdvertiser($con, "aleph", $currency, $typeSelect[$b]['id'], $month[$m][1], $region, $value,$pYear,false,$report);            	
                    $currentYtd[$b][$m] = $this->defineValuesAdvertiser($con, "ytd", $currency, $typeSelect[$b]['id'], $month[$m][1], $region, $value, $year,false,$report);
                    $previousYtd[$b][$m] = $this->defineValuesAdvertiser($con, "ytd", $currency, $typeSelect[$b]['id'], $month[$m][1], $region, $value,$pYear,false,$report);    
                    $currentAdSales[$b][$m] = $currentAleph[$b][$m] + $currentYtd[$b][$m];
                    $previousAdSales[$b][$m] = $previousAleph[$b][$m] + $previousYtd[$b][$m]; 
	                $currentTarget[$b][$m] = 0.0;//$this->defineValuesAdvertiser($con, "plan_by_brand", $currency, $typeSelect[$b]['id'], $month[$m][1], $region, $value, $year, "TARGET");
	                $currentCorporate[$b][$m] = 0.0;//$this->defineValuesAdvertiser($con, "plan_by_brand", $currency, $typeSelect[$b]['id'], $month[$m][1], $region, $value, $year, "CORPORATE");
	                $currentSAP[$b][$m] = 0.0;//$this->defineValuesAdvertiser($con, "plan_by_brand", $currency, $typeSelect[$b]['id'], $month[$m][1], $region, $value, $year, "ACTUAL");
	                $previousSAP[$b][$m] = 0.0;//$this->defineValuesAdvertiser($con, "plan_by_brand", $currency, $typeSelect[$b]['id'], $month[$m][1], $region, $value, $pYear, "ACTUAL");
			                
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
				if ($year >= '2022' || $pYear == '2022' && $region == '1') {
					for ($b=0; $b < sizeof($typeSelect); $b++) { 
			            for ($m=0; $m < sizeof($month); $m++) {
	                        //$currentAleph[$b][$m] = $this->defineValuesAdvertiser($con, "aleph", $currency, $typeSelect[$b]['id'], $month[$m][1], $region, $value,$year,$report);
	                        //$previousAleph[$b][$m] = $this->defineValuesAdvertiser($con, "aleph", $currency, $typeSelect[$b]['id'], $month[$m][1], $region, $value,$pYear,$report);            	
		                    $currentWBD[$b][$m] = $this->defineValuesAgency($con, "wbd", $currency, $typeSelect[$b]['id'], $month[$m][1], $region, $value, $year,false,$report);
		                    $previousWBD[$b][$m] = $this->defineValuesAgency($con, "wbd", $currency, $typeSelect[$b]['id'], $month[$m][1], $region, $value,$pYear,false,$report);    
	                        $currentAdSales[$b][$m] = $currentWBD[$b][$m];
	                        $previousAdSales[$b][$m] = $previousWBD[$b][$m]; 
			                $currentTarget[$b][$m] = 0.0;//$this->defineValuesAgency($con, "plan_by_brand", $currency, $typeSelect[$b]['id'], $month[$m][1], $region, $value, $year, "TARGET");
			                $currentCorporate[$b][$m] = 0.0;//$this->defineValuesAgency($con, "plan_by_brand", $currency, $typeSelect[$b]['id'], $month[$m][1], $region, $value, $year, "CORPORATE");
			                $currentSAP[$b][$m] = 0.0;//$this->defineValuesAgency($con, "plan_by_brand", $currency, $typeSelect[$b]['id'], $month[$m][1], $region, $value, $year, "ACTUAL");
			                $previousSAP[$b][$m] = 0.0;//$this->defineValuesAgency($con, "plan_by_brand", $currency, $typeSelect[$b]['id'], $month[$m][1], $region, $value, $pYear, "ACTUAL");
			                
			            }
			        }
			    }else{
			    	$currentAleph[$b][$m] = $this->defineValuesAgency($con, "aleph", $currency, $typeSelect[$b]['id'], $month[$m][1], $region, $value,$year,false,$report);
                    $previousAleph[$b][$m] = $this->defineValuesAgency($con, "aleph", $currency, $typeSelect[$b]['id'], $month[$m][1], $region, $value,$pYear,false,$report);            	
                    $currentYtd[$b][$m] = $this->defineValuesAgency($con, "ytd", $currency, $typeSelect[$b]['id'], $month[$m][1], $region, $value, $year,false,$report);
                    $previousYtd[$b][$m] = $this->defineValuesAgency($con, "ytd", $currency, $typeSelect[$b]['id'], $month[$m][1], $region, $value,$pYear,false,$report);    
                    $currentAdSales[$b][$m] = $currentAleph[$b][$m] + $currentYtd[$b][$m];
                    $previousAdSales[$b][$m] = $previousAleph[$b][$m] + $previousYtd[$b][$m]; 
	                $currentTarget[$b][$m] = 0.0;//$this->defineValuesAgency($con, "plan_by_brand", $currency, $typeSelect[$b]['id'], $month[$m][1], $region, $value, $year, "TARGET");
	                $currentCorporate[$b][$m] = 0.0;//$this->defineValuesAgency($con, "plan_by_brand", $currency, $typeSelect[$b]['id'], $month[$m][1], $region, $value, $year, "CORPORATE");
	                $currentSAP[$b][$m] = 0.0;//$this->defineValuesAgency($con, "plan_by_brand", $currency, $typeSelect[$b]['id'], $month[$m][1], $region, $value, $year, "ACTUAL");
	                $previousSAP[$b][$m] = 0.0;//$this->defineValuesAgency($con, "plan_by_brand", $currency, $typeSelect[$b]['id'], $month[$m][1], $region, $value, $pYear, "ACTUAL");
			                
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
              if ($year >= '2022' || $pYear == '2022' && $region == '1') {
					for ($b=0; $b < sizeof($typeSelect); $b++) { 
			            for ($m=0; $m < sizeof($month); $m++) {
	                        //$currentAleph[$b][$m] = $this->defineValuesAgencyGroup($con, "aleph", $currency, $typeSelect[$b]['id'], $month[$m][1], $region, $value,$year,false,$report);
	                        //$previousAleph[$b][$m] = $this->defineValuesAgencyGroup($con, "aleph", $currency, $typeSelect[$b]['id'], $month[$m][1], $region, $value,$pYear,false,$report);            	
		                    $currentWBD[$b][$m] = $this->defineValuesAgencyGroup($con, "wbd", $currency, $typeSelect[$b]['id'], $month[$m][1], $region, $value, $year,false,$report);
		                    $previousWBD[$b][$m] = $this->defineValuesAgencyGroup($con, "wbd", $currency, $typeSelect[$b]['id'], $month[$m][1], $region, $value,$pYear,false,$report);    
	                        $currentAdSales[$b][$m] = $currentWBD[$b][$m];
	                        $previousAdSales[$b][$m] = $previousWBD[$b][$m]; 
			                $currentTarget[$b][$m] = 0.0;//$this->defineValuesAgencyGroup($con, "plan_by_brand", $currency, $typeSelect[$b]['id'], $month[$m][1], $region, $value, $year, "TARGET");
			                $currentCorporate[$b][$m] = 0.0;//$this->defineValuesAgencyGroup($con, "plan_by_brand", $currency, $typeSelect[$b]['id'], $month[$m][1], $region, $value, $year, "CORPORATE");
			                $currentSAP[$b][$m] = 0.0;//$this->defineValuesAgencyGroup($con, "plan_by_brand", $currency, $typeSelect[$b]['id'], $month[$m][1], $region, $value, $year, "ACTUAL");
			                $previousSAP[$b][$m] = 0.0;//$this->defineValuesAgencyGroup($con, "plan_by_brand", $currency, $typeSelect[$b]['id'], $month[$m][1], $region, $value, $pYear, "ACTUAL");
			                
			            }
			        }
			    }else{
			    	$currentAleph[$b][$m] = $this->defineValuesAgencyGroup($con, "aleph", $currency, $typeSelect[$b]['id'], $month[$m][1], $region, $value,$year,false,$report);
                    $previousAleph[$b][$m] = $this->defineValuesAgencyGroup($con, "aleph", $currency, $typeSelect[$b]['id'], $month[$m][1], $region, $value,$pYear,false,$report);            	
                    $currentYtd[$b][$m] = $this->defineValuesAgencyGroup($con, "ytd", $currency, $typeSelect[$b]['id'], $month[$m][1], $region, $value, $year,false,$report);
                    $previousYtd[$b][$m] = $this->defineValuesAgencyGroup($con, "ytd", $currency, $typeSelect[$b]['id'], $month[$m][1], $region, $value,$pYear,false,$report);    
                    $currentAdSales[$b][$m] = $currentAleph[$b][$m] + $currentYtd[$b][$m];
                    $previousAdSales[$b][$m] = $previousAleph[$b][$m] + $previousYtd[$b][$m]; 
	                $currentTarget[$b][$m] = 0.0;//$this->defineValuesAgencyGroup($con, "plan_by_brand", $currency, $typeSelect[$b]['id'], $month[$m][1], $region, $value, $year, "TARGET");
	                $currentCorporate[$b][$m] = 0.0;//$this->defineValuesAgencyGroup($con, "plan_by_brand", $currency, $typeSelect[$b]['id'], $month[$m][1], $region, $value, $year, "CORPORATE");
	                $currentSAP[$b][$m] = 0.0;//$this->defineValuesAgencyGroup($con, "plan_by_brand", $currency, $typeSelect[$b]['id'], $month[$m][1], $region, $value, $year, "ACTUAL");
	                $previousSAP[$b][$m] = 0.0;//$this->defineValuesAgencyGroup($con, "plan_by_brand", $currency, $typeSelect[$b]['id'], $month[$m][1], $region, $value, $pYear, "ACTUAL");
			                
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
				
				if ($year >= '2022' || $pYear == '2022' && $region == '1') {
					for ($b=0; $b < sizeof($typeSelect); $b++) { 
			            for ($m=0; $m < sizeof($month); $m++) {
	                        //$currentAleph[$b][$m] = $this->defineValuesAE($con, "aleph", $currency, $typeSelect[$b]['id'], $month[$m][1], $region, $value,$year);
	                        //$previousAleph[$b][$m] = $this->defineValuesAE($con, "aleph", $currency, $typeSelect[$b]['id'], $month[$m][1], $region, $value,$pYear);            	
		                    $currentWBD[$b][$m] = $this->defineValuesAE($con, "wbd", $currency, $typeSelect[$b], $month[$m][1], $region, $value, $year,false,$report);
		                    $previousWBD[$b][$m] = $this->defineValuesAE($con, "wbd", $currency, $typeSelect[$b], $month[$m][1], $region, $value,$pYear,false,$report);    
	                        $currentAdSales[$b][$m] = $currentWBD[$b][$m];
	                        $previousAdSales[$b][$m] = $previousWBD[$b][$m]; 
			                $currentTarget[$b][$m] = $this->defineValuesAE($con, "plan_by_sales", $currency, $typeSelect[$b], $month[$m][1], $region, $value, $year, "TARGET");
			                $currentCorporate[$b][$m] = $this->defineValuesAE($con, "plan_by_brand", $currency, $typeSelect[$b], $month[$m][1], $region, $value, $year, "CORPORATE");
			                $currentSAP[$b][$m] = $this->defineValuesAE($con, "plan_by_brand", $currency, $typeSelect[$b], $month[$m][1], $region, $value, $year, "ACTUAL");
			                $previousSAP[$b][$m] = $this->defineValuesAE($con, "plan_by_brand", $currency, $typeSelect[$b], $month[$m][1], $region, $value, $pYear, "ACTUAL");
			                
			            }
			        }
			    }else{
			    	$currentAleph[$b][$m] = $this->defineValuesAE($con, "aleph", $currency, $typeSelect[$b], $month[$m][1], $region, $value,$year,false,$report);
                    $previousAleph[$b][$m] = $this->defineValuesAE($con, "aleph", $currency, $typeSelect[$b], $month[$m][1], $region, $value,$pYear,false,$report);            	
                    $currentYtd[$b][$m] = $this->defineValuesAE($con, "ytd", $currency, $typeSelect[$b], $month[$m][1], $region, $value, $year,false,$report);
                    $previousYtd[$b][$m] = $this->defineValuesAE($con, "ytd", $currency, $typeSelect[$b], $month[$m][1], $region, $value,$pYear,false,$report);    
                    $currentAdSales[$b][$m] = $currentAleph[$b][$m] + $currentYtd[$b][$m];
                    $previousAdSales[$b][$m] = $previousAleph[$b][$m] + $previousYtd[$b][$m]; 
	                $currentTarget[$b][$m] = $this->defineValuesAE($con, "plan_by_sales", $currency, $typeSelect[$b], $month[$m][1], $region, $value, $year, "TARGET");
	                $currentCorporate[$b][$m] = $this->defineValuesAE($con, "plan_by_brand", $currency, $typeSelect[$b], $month[$m][1], $region, $value, $year, "CORPORATE");
	                $currentSAP[$b][$m] = $this->defineValuesAE($con, "plan_by_brand", $currency, $typeSelect[$b], $month[$m][1], $region, $value, $year, "ACTUAL");
	                $previousSAP[$b][$m] = $this->defineValuesAE($con, "plan_by_brand", $currency, $typeSelect[$b], $month[$m][1], $region, $value, $pYear, "ACTUAL");
			                
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
		//var_dump($rtr);
		return $rtr;
	}

	public function defineValuesAE($con, $table, $currency, $typeSelect, $month, $region, $value, $keyYear, $source=false,$report=false){
        $p = new pRate();

        $year = $keyYear;
        $valueView = $value;
        //var_dump($table);

        
        if ($report == 'DLA') {
	            if ($currency[0]['name'] == "USD") {
	                if($table == "aleph" || $table == 'wbd'){
	                	if ($year <= 2022) {
	                		$pRate = 4.99;
	                		$pRateSel = 4.99;	
	                	}else{
	                		$pRate = $p->getPRateByRegionAndYear($con,array('1'),array($keyYear));
	                    	$pRateSel = $p->getPRateByRegionAndYear($con,array('1'),array($year));
	                	}                    	
	                }else{
	                    $pRate = 1.0;
	                    $pRateSel = $pRate;
	                }
	            }else{
	                if($table == "cmaps" || $table == 'wbd' || $table == 'aleph'){
	                    $pRate = 1.0;
	                    $pRateSel = $pRate;
	                }else{                    
	                    $ccYear = date('Y');
	                    $pRate = $p->getPRateByRegionAndYearIBMS($con, array($region), array($ccYear));
	                    $pRateSel = $p->getPRateByRegionAndYearIBMS($con, array($region), array($ccYear));
	                }                
	            }    
        }else{

	        if ($table != "plan_by_brand" && $table != "digital" && $table != 'plan_by_sales') {
	            if ($currency[0]['name'] == "USD") {
	                if($table == "cmaps"){
	                    $pRate = $p->getPRateByRegionAndYear($con, array($region),array($keyYear));
	                    $pRateSel = $p->getPRateByRegionAndYear($con, array($region),array($year));
	                }elseif($table == "aleph" || $table == 'wbd'){
	                	if ($year <= 2022) {
	                		$pRate = 4.99;
	                		$pRateSel = 4.99;	
	                	}else{
	                		$pRate = $p->getPRateByRegionAndYear($con, array($region),array($keyYear));
	                    	$pRateSel = $p->getPRateByRegionAndYear($con, array($region),array($year));
	                	}   
	                } 

	            }else{

	                if($table == "cmaps" || $table == "aleph" || $table == 'wbd'){
	                    $pRate = 1.0;
	                    $pRateSel = $pRate;
	                }else{
	                    
	                    $pRate = $p->getPRateByRegionAndYear($con, array($region),array($keyYear));
	                    $pRateSel = $p->getPRateByRegionAndYear($con, array($region),array($year));
	                    
	 
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

            case 'aleph':
                
                $columns = array("sales_office_id","year", "month","current_sales_rep_id");                
                $columnsValue = array($region,$year,$month,$typeSelect);
                    
                $value .= '_revenue';
                
                break;
            case 'wbd':
                
                $columns = array("year", "month","current_sales_rep_id");                
                $columnsValue = array($year,$month,$typeSelect);
                    
                $value .= '_value';
                
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
            }elseif($table == "wbd"){  
                $rtr = $tmp/$pRate;
            }elseif ($table == "ytd") {
                $rtr = $tmp*$pRate;
            }else if($table == "plan_by_brand" || $table == 'plan_by_sales'){                          
                $rtr = $tmp*$pRate;
            }else{
                if ($valueView == 'net') {
                    $rtr = ($tmp*0.80)/$pRate;
                }else{
                    $rtr = $tmp/$pRate;
                }
            }
        }
        
        return $rtr;		
    }  

    public function defineValuesAdvertiser($con, $table, $currency, $typeSelect, $month, $region, $value, $keyYear, $source=false, $report=false){
        $p = new pRate();

        $year = $keyYear;
        $valueView = $value;

       if ($report == 'DLA') {
	            if ($currency[0]['name'] == "USD") {
	                if($table == "aleph" || $table == 'wbd'){
	                	if ($year <= 2022) {
	                		$pRate = 4.99;
	                		$pRateSel = 4.99;	
	                	}else{
	                		$pRate = $p->getPRateByRegionAndYear($con, array('1'),array($keyYear));
	                    	$pRateSel = $p->getPRateByRegionAndYear($con,array('1'),array($year));
	                	}                    	
	                }else{
	                    $pRate = 1.0;
	                    $pRateSel = $pRate;
	                }
	            }else{
	                if($table == "cmaps" || $table == 'wbd' || $table == 'aleph'){
	                    $pRate = 1.0;
	                    $pRateSel = $pRate;
	                }else{                    
	                    $ccYear = date('Y');
	                    $pRate = $p->getPRateByRegionAndYearIBMS($con, array($region), array($ccYear));
	                    $pRateSel = $p->getPRateByRegionAndYearIBMS($con, array($region), array($ccYear));
	                }                
	            }    
        }else{
        	 if ($table != "plan_by_brand" && $table != "digital") {
	            if ($currency[0]['name'] == "USD") {
	                if($table == "cmaps"){
	                    $pRate = $p->getPRateByRegionAndYear($con, array($region),array($keyYear));
	                    $pRateSel = $p->getPRateByRegionAndYear($con, array($region),array($year));
	                }elseif($table == "aleph" || $table == 'wbd'){
	                	if ($year <= 2022) {
	                		$pRate = 4.99;
	                		$pRateSel = 4.99;	
	                	}else{
	                		$pRate = $p->getPRateByRegionAndYear($con, array($region),array($keyYear));
	                    	$pRateSel = $p->getPRateByRegionAndYear($con, array($region),array($year));
	                	}
	                }    
	            }else{
	                if($table == "cmaps" || $table == 'aleph' || $table == 'wbd'){
	                    $pRate = 1.0;
	                    $pRateSel = $pRate;
	                }else{
	                  
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
             case 'aleph':
                
                $columns = array("sales_office_id","year", "month","client_id");                
                $columnsValue = array($region,$year,$month,$typeSelect);                
                    
                $value .= '_revenue';
                
                break;
            case 'wbd':
                
                $columns = array("year", "month","client_id");                
                $columnsValue = array($year,$month,$typeSelect);                
                    
                $value .= '_value';
                
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
            }elseif($table == "wbd"){  
                $rtr = $tmp/$pRate;
            }elseif ($table == "ytd") {
                $rtr = $tmp*$pRate;
            }else if($table == "plan_by_brand"){                          
                $rtr = $tmp*$pRateSel;
            }else{
                if ($valueView == 'net') {
                    $rtr = ($tmp*0.80)/$pRate;
                }else{
                    $rtr = $tmp/$pRate;
                }                
            }           
        }
        return $rtr;		
    }      

    public function defineValuesAgency($con, $table, $currency, $typeSelect, $month, $region, $value, $keyYear, $source=false,$report=false){
        $p = new pRate();
        //r_dump($typeSelect);
        $year = $keyYear;
        $valueView = $value;


        if ($report == 'DLA') {
	            if ($currency[0]['name'] == "USD") {
	                if($table == "aleph" || $table == 'wbd'){
	                	if ($year <= 2022) {
	                		$pRate = 4.99;
	                		$pRateSel = 4.99;	
	                	}else{
	                		$pRate = $p->getPRateByRegionAndYear($con,array('1'),array($keyYear));
	                    	$pRateSel = $p->getPRateByRegionAndYear($con,array('1'),array($year));
	                	}                    	
	                }else{
	                    $pRate = 1.0;
	                    $pRateSel = $pRate;
	                }
	            }else{
	                if($table == "cmaps" || $table == 'wbd' || $table == 'aleph'){
	                    $pRate = 1.0;
	                    $pRateSel = $pRate;
	                }else{                    
	                    $ccYear = date('Y');
	                    $pRate = $p->getPRateByRegionAndYearIBMS($con, array($region), array($ccYear));
	                    $pRateSel = $p->getPRateByRegionAndYearIBMS($con, array($region), array($ccYear));
	                }                
	            }    
        }else{
        	if ($table != "plan_by_brand" && $table != "digital") {
	            if ($currency[0]['name'] == "USD") {
	                if($table == "cmaps"){
	                    $pRate = $p->getPRateByRegionAndYear($con, array($region),array($keyYear));
	                    $pRateSel = $p->getPRateByRegionAndYear($con, array($region),array($year));
	                }elseif($table == "aleph" || $table == 'wbd'){
	                	if ($year <= 2022) {
	                		$pRate = 4.99;
	                		$pRateSel = 4.99;	
	                	}else{
	                		$pRate = $p->getPRateByRegionAndYear($con, array($region),array($keyYear));
	                    	$pRateSel = $p->getPRateByRegionAndYear($con, array($region),array($year));
	                	}  
	                }  
	            }else{
	                if($table == "cmaps" || $table == "aleph" || $table == 'wbd'){
	                    $pRate = 1.0;
	                    $pRateSel = $pRate;
	                }else{
	                  
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
            case 'aleph':
                
                $columns = array("sales_office_id","year", "month","agency_id");                
                $columnsValue = array($region,$year,$month,$typeSelect);                
                    
                $value .= '_revenue';
                
                break;
            case 'wbd':
                
                $columns = array("year", "month","agency_id");                
                $columnsValue = array($year,$month,$typeSelect);                
                    
                $value .= '_value';
                
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
            }elseif($table == "wbd"){  
                $rtr = $tmp/$pRate;
            }elseif ($table == "ytd") {
                $rtr = $tmp*$pRate;
            }else if($table == "plan_by_brand"){                          
                $rtr = $tmp*$pRateSel;
            }else{
                if ($valueView == 'net') {
                    $rtr = ($tmp*0.80)/$pRate;
                }else{
                    $rtr = $tmp/$pRate;
                }                
            } 
        }
        return $rtr;		
    }      

    public function defineValuesAgencyGroup($con, $table, $currency, $typeSelect, $month, $region, $value, $keyYear, $source=false,$report=false){
        $p = new pRate();

        $year = $keyYear;
        $valueView = $value;

        if ($report == 'DLA') {
	            if ($currency[0]['name'] == "USD") {
	                if($table == "aleph" || $table == 'wbd'){
	                	if ($year <= 2022) {
	                		$pRate = 4.99;
	                		$pRateSel = 4.99;	
	                	}else{
	                		$pRate = $p->getPRateByRegionAndYear($con,array('1'),array($keyYear));
	                    	$pRateSel = $p->getPRateByRegionAndYear($con,array('1'),array($year));
	                	}                    	
	                }else{
	                    $pRate = 1.0;
	                    $pRateSel = $pRate;
	                }
	            }else{
	                if($table == "cmaps" || $table == 'wbd' || $table == 'aleph'){
	                    $pRate = 1.0;
	                    $pRateSel = $pRate;
	                }else{                    
	                    $ccYear = date('Y');
	                    $pRate = $p->getPRateByRegionAndYearIBMS($con, array($region), array($ccYear));
	                    $pRateSel = $p->getPRateByRegionAndYearIBMS($con, array($region), array($ccYear));
	                }                
	            }    
        }else{
        	if ($table != "plan_by_brand" && $table != "digital") {
	            if ($currency[0]['name'] == "USD") {
	                if($table == "cmaps"){
	                    $pRate = $p->getPRateByRegionAndYear($con, array($region),array($keyYear));
	                    $pRateSel = $p->getPRateByRegionAndYear($con, array($region),array($year));
	                }elseif($table == "aleph" || $table == 'wbd'){
	                	if ($year <= 2022) {
	                		$pRate = 4.99;
	                		$pRateSel = 4.99;	
	                	}else{
	                		$pRate = $p->getPRateByRegionAndYear($con, array($region),array($keyYear));
	                    	$pRateSel = $p->getPRateByRegionAndYear($con, array($region),array($year));
	                	}   
	                } 
	            }else{
	                if($table == "cmaps" || $table == 'aleph' || $table == 'wbd'){
	                    $pRate = 1.0;
	                    $pRateSel = $pRate;
	                }else{
	                    
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

            case 'aleph':
                
                $columns = array("sales_office_id","year", "month","agency_group_id");                
                $columnsValue = array($region,$year,$month,$typeSelect);                
                    
                $value .= '_revenue';
                
                break;

            case 'wbd':
                
                $columns = array("year", "month","agency_group_id");                
                $columnsValue = array($year,$month,$typeSelect);                
                    
                $value .= '_value';
                
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
            //var_dump($table);
            if($table == "cmaps y"){  
                $rtr = $tmp/$pRate;
            }elseif($table == "wbd y"){  
                $rtr = $tmp/$pRate;
            }elseif ($table == "ytd y") {
                $rtr = $tmp*$pRate;
            }else if($table == "plan_by_brand"){                          
                $rtr = $tmp*$pRateSel;
            }else{
                if ($valueView == 'net') {

                    $rtr = ($tmp*0.80)/$pRate;
                }else{
                    $rtr = $tmp/$pRate;
                }                
            } 
        }
        return $rtr;        
    }

    public function defineValuesOfficeCompany($con, $table, $currency, $month, $region, $value, $keyYear, $source=false,$brand=false, $company){

        $sql = new sql();
        $p = new pRate();
        //var_dump($company);
        $year = $keyYear;
        $valueView = $value;
        //var_dump($value);
        if ($table != "plan_by_brand" && $table != "digital") {
            if ($currency == "4") {
                if($table == "cmaps"){
                    $pRate = $p->getPRateByRegionAndYear($con, array($region),array($keyYear));
                    $pRateSel = $p->getPRateByRegionAndYear($con, array($region),array($year));
                }elseif($table == "aleph" || $table == 'wbd'){
                	if ($year <= 2022) {
                		$pRate = 4.99;
                		$pRateSel = 4.99;	
                	}else{
                		$pRate = $p->getPRateByRegionAndYear($con, array($region),array($keyYear));
                    	$pRateSel = $p->getPRateByRegionAndYear($con, array($region),array($year));
                	}               		
              	}else{
              		$pRate = 1.0;
              		$pRateSel = $pRate;
              	}           	        	

            }else{

                if($table == "cmaps" || $table == 'aleph' || $table == 'wbd'){
                    $pRate = 1.0;
                    $pRateSel = $pRate;

                }else{                       
                    $pRate = $p->getPRateByRegionAndYearIBMS($con, array($region), array($keyYear));
                    $pRateSel = $p->getPRateByRegionAndYearIBMS($con, array($region), array($keyYear));
                }                
            }    
        }else{   

            if ($currency == "4") {
                $pRate = 1.0;
                $pRateSel = $pRate;
            }else{
                $pRate = $p->getPRateByRegionAndYear($con,array($region),array($keyYear));
                $pRateSel = $p->getPRateByRegionAndYear($con,array($region),array($keyYear)); 
            }           
        } 
        //var_dump($company);       

        switch ($table) {

            case 'cmaps':
                if (sizeof($company) == 1) {
                    if ($company == 'dc' ) {
                        $columns = array("brand_id", "year", "month", "b.brand_group_id");
                        $columnsValue = array($brand, $year, $month,"1");
                    }elseif ($company = 'spt') {
                        $columns = array("brand_id", "year", "month", "b.brand_group_id");
                        $columnsValue = array($brand, $year, $month,"2");
                    }elseif ($company = 'wm') {
                        $columns = array("brand_id", "year", "month", "b.brand_group_id");
                        $columnsValue = array($brand, $year, $month,"3");
                    }
                }else{
                    $columns = array("brand_id", "year", "month");
                    $columnsValue = array($brand, $year, $month);
                }
                $join = "LEFT JOIN brand b ON (c.brand_id = b.ID)";
                
                $table .= " c";
                break;

            case 'ytd':  
	       		
       			$columns = array("y.sales_representant_office_id","y.year", "y.month", "b.brand_group_id");                
        		$columnsValue = array($region,$year,$month, $company);  		                                                       

                $value .= "_revenue_prate";
                $join = "LEFT JOIN brand b ON (y.brand_id = b.ID)";
                $table .= " y";
                $where = $sql->where($columns, $columnsValue);
                break;

            case 'aleph':
            	
       			$columns = array("a.sales_office_id","a.year", "a.month", "b.brand_group_id");                
        		$columnsValue = array($region,$year,$month, $company);	       		                              
                
                if ($value == 'net') {
                    $value = "gross_revenue";
                }else{
                    $value .= '_revenue';
                }
                
                $join = "LEFT JOIN brand b ON (a.brand_id = b.ID)";
                $table .= " a";
                $where  = $sql->where($columns, $columnsValue);
                break;

            case 'wbd':
            	
       			$columns = array("w.year", "w.month", "b.brand_group_id");                
        		$columnsValue = array($year,$month, $company);	       		                              
                
                if($value == 'net net' && $currency == '4'){
                	$value = 'gross_value';
                }elseif ($value == 'net net' && $currency == "1") {
                	$value = 'net_value';
                }else{
                	$value .='_value';	
                }
                                
                $join = "LEFT JOIN brand b ON (w.brand_id = b.ID)";
                $table .= " w";
                $where  = $sql->where($columns, $columnsValue);
                break;

            case 'plan_by_brand':
        		$columns = array("pbb.sales_office_id","pbb.source","pbb.type_of_revenue","pbb.year","pbb.month","b.brand_group_id");
           		$columnsValue = array($region,strtoupper($source),$value,$year,$month, $company);
                	
                $value = "revenue";
                $join = "left join brand b ON (pbb.brand_id = b.ID)";
                $table .= " pbb";
                $where = $sql->where($columns, $columnsValue);
                //var_dump($where);
                break;

            default:
                $columns = false;
                $join = null;
                break;
        }

        if (!$columns) {
            $rtr = false;
        }else{
            $sql = new sql();

            $as = "sum";

            $selectSum = $sql->selectSum($con, $value, $as, $table, $join, $where);

            $tmp = $sql->fetchSum($selectSum, $as)["sum"];
            //var_dump($tmp);

            if($table == "cmaps c" || $table == 'wbd w' || $table == 'aleph a'){     
            	if ($table == 'aleph a'){
            		if ($valueView == 'net') {
                    	$rtr = ($tmp*0.80)/$pRate;
                	}else{
                    	$rtr = $tmp/$pRate;
                	}   
            	}else{
            		if($value == 'net net' && $currency == '4'){
                		$rtr = ($tmp*0.14162005)/$pRate;
	                }elseif ($value == 'net net' && $currency == "1") {
	                	$rtr = ($tmp*0.8915)/$pRate;
	                }else{
	                	$rtr = $tmp/$pRate;
	                }            		
            	} 
            }else if($table == "plan_by_brand pbb"){ 
            	if ($currency == "4") {
	                $pRate = 1.0;
	                $pRateSel = $pRate;
	                $rtr = $tmp*$pRate;   
            	}else{
        			if ($year  <= 2022) {
        				if ($company == '3') {
	        				$pRateSel = 4.99;
	        				$rtr = $tmp*$pRateSel;                    
	        			}else{
	           				$rtr = $tmp*$pRateSel;	
	        			}
        			}else{
        				$rtr = $tmp*$pRateSel;
        			}            			            		      
            	}  
            }else{
                $rtr = $tmp*$pRate;
            }
        }
        //var_dump($rtr);
        return $rtr;
     
    }

    public function defineValuesOffice($con, $table, $currency, $month, $region, $value, $keyYear, $source=false,$brand=false, $company){

        $sql = new sql();
        $p = new pRate();
        //var_dump($company);
        $year = $keyYear;
        $valueView = $value;
        //var_dump($value);
        if ($table != "plan_by_brand" && $table != "digital") {
            if ($currency == "4") {
                if($table == "cmaps"){
                    $pRate = $p->getPRateByRegionAndYear($con, array($region),array($keyYear));
                    $pRateSel = $p->getPRateByRegionAndYear($con, array($region),array($year));
                }elseif($table == "aleph" || $table == 'wbd'){
                	if ($year <= 2022) {
                		$pRate = 4.99;
                		$pRateSel = 4.99;	
                	}else{
                		$pRate = $p->getPRateByRegionAndYear($con, array($region),array($keyYear));
                    	$pRateSel = $p->getPRateByRegionAndYear($con, array($region),array($year));
                	}               		
              	}else{
              		$pRate = 1.0;
              		$pRateSel = $pRate;
              	}           	        	

            }else{

                if($table == "cmaps" || $table == 'aleph' || $table == 'wbd'){
                    $pRate = 1.0;
                    $pRateSel = $pRate;

                }else{                       
                    $pRate = $p->getPRateByRegionAndYearIBMS($con, array($region), array($keyYear));
                    $pRateSel = $p->getPRateByRegionAndYearIBMS($con, array($region), array($keyYear));
                }                
            }    
        }else{   

            if ($currency == "4") {
                $pRate = 1.0;
                $pRateSel = $pRate;
            }else{
                $pRate = $p->getPRateByRegionAndYear($con,array($region),array($keyYear));
                $pRateSel = $p->getPRateByRegionAndYear($con,array($region),array($keyYear)); 
            }           
        } 
        //var_dump($company);       

        switch ($table) {

            case 'cmaps':
                if (sizeof($company) == 1) {
                    if ($company[0] == 'dc' ) {
                        $columns = array("brand_id", "year", "month", "b.brand_group_id");
                        $columnsValue = array($brand, $year, $month,"1");
                    }elseif ($company[0] = 'spt') {
                        $columns = array("brand_id", "year", "month", "b.brand_group_id");
                        $columnsValue = array($brand, $year, $month,"2");
                    }elseif ($company[0] = 'wm') {
                        $columns = array("brand_id", "year", "month", "b.brand_group_id");
                        $columnsValue = array($brand, $year, $month,"3");
                    }
                }else{
                    $columns = array("brand_id", "year", "month");
                    $columnsValue = array($brand, $year, $month);
                }
                $join = "LEFT JOIN brand b ON (c.brand_id = b.ID)";
                
                $table .= " c";
                break;

            case 'ytd':  
	       		
       			$columns = array("y.sales_representant_office_id","y.year", "y.month", "b.brand_group_id");                
        		$columnsValue = array($region,$year,$month, $company);  		                                                       

                $value .= "_revenue_prate";
                $join = "LEFT JOIN brand b ON (y.brand_id = b.ID)";
                $table .= " y";
                $where = $sql->where($columns, $columnsValue);
                break;

            case 'aleph':
            	
       			$columns = array("a.sales_office_id","a.year", "a.month", "b.brand_group_id");                
        		$columnsValue = array($region,$year,$month, $company);	       		                              
                
                if ($value == 'net') {
                    $value = "gross_revenue";
                }else{
                    $value .= '_revenue';
                }
                
                $join = "LEFT JOIN brand b ON (a.brand_id = b.ID)";
                $table .= " a";
                $where  = $sql->where($columns, $columnsValue);
                break;

            case 'wbd':
            	
       			$columns = array("w.year", "w.month", "b.brand_group_id");                
        		$columnsValue = array($year,$month, $company);	       		                              
                
                if($value == 'net net' && $currency == '4'){
                	$value = 'gross_value';
                }elseif ($value == 'net net' && $currency == "1") {
                	$value = 'net_value';
                }else{
                	$value .='_value';	
                }                    
                
                $join = "LEFT JOIN brand b ON (w.brand_id = b.ID)";
                $table .= " w";
                $where  = $sql->where($columns, $columnsValue);
                break;

            case 'plan_by_brand':
        		$columns = array("pbb.sales_office_id","pbb.source","pbb.type_of_revenue","pbb.year","pbb.month","b.brand_group_id");
           		$columnsValue = array($region,strtoupper($source),$value,$year,$month, $company);
                	
                $value = "revenue";
                $join = "left join brand b ON (pbb.brand_id = b.ID)";
                $table .= " pbb";
                $where = $sql->where($columns, $columnsValue);
                //var_dump($where);
                break;

            default:
                $columns = false;
                $join = null;
                break;
        }

        if (!$columns) {
            $rtr = false;
        }else{
            $sql = new sql();

            $as = "sum";

            $selectSum = $sql->selectSum($con, $value, $as, $table, $join, $where);

            $tmp = $sql->fetchSum($selectSum, $as)["sum"];
            //var_dump($tmp);

            if($table == "cmaps c" || $table == 'wbd w' || $table == 'aleph a'){     
            	if ($table == 'aleph a'){
            		if ($valueView == 'net') {
                    	$rtr = ($tmp*0.80)/$pRate;
                	}else{
                    	$rtr = $tmp/$pRate;
                	}   
            	}else{
            		if($value == 'net net' && $currency == '4'){
                		$rtr = ($tmp*0.14162005)/$pRate;
	                }elseif ($value == 'net net' && $currency == "1") {
	                	$rtr = ($tmp*0.8915)/$pRate;
	                }else{
	                	$rtr = $tmp/$pRate;
	                }     
            	}  
            }else if($table == "plan_by_brand pbb"){ 
            	if ($currency == "4") {
	                $pRate = 1.0;
	                $pRateSel = $pRate;
	                $rtr = $tmp*$pRate;   
            	}else{
            		for ($c=0; $c <sizeof($company); $c++) { 
            			if ($year  <= 2022) {
            				if ($company[$c] == '3') {
		        				$pRateSel = 4.99;
		        				$rtr = $tmp*$pRateSel;                    
		        			}else{
		           				$rtr = $tmp*$pRateSel;	
		        			}
            			}else{
            				$rtr = $tmp*$pRateSel;
            			}            			            		      
            		} 
            	}  
            }else{
                $rtr = $tmp*$pRate;
            }
        }
        //var_dump($rtr);
        return $rtr;
     
    }
    
	public function defineValuesBrand($con, $table, $currency, $brand, $month, $region, $value, $keyYear, $source=false,$brandGroup=false, $report=false){

        $p = new pRate();
        $year = $keyYear;
        $join = null;


       	if ($report == 'DLA') {
	            if ($currency[0]['name'] == "USD") {
	                if($table == "aleph" || $table == 'wbd'){
	                	if ($year <= 2022) {
	                		$pRate = 4.99;
	                		$pRateSel = 4.99;	
	                	}else{
	                		$pRate = $p->getPRateByRegionAndYear($con,array('1'),array($keyYear));
	                    	$pRateSel = $p->getPRateByRegionAndYear($con,array('1'),array($year));
	                	}                    	
	                }else{
	                    $pRate = 1.0;
	                    $pRateSel = $pRate;
	                }
	            }else{
	                if($table == "cmaps" || $table == 'wbd' || $table == 'aleph'){
	                    $pRate = 1.0;
	                    $pRateSel = $pRate;
	                }else{                    
	                    $ccYear = date('Y');
	                    $pRate = $p->getPRateByRegionAndYearIBMS($con, array($region), array($ccYear));
	                    $pRateSel = $p->getPRateByRegionAndYearIBMS($con, array($region), array($ccYear));
	                }                
	            }    
        }else{
        	if ($table != "plan_by_brand" && $table != "digital") {
	            if ($currency[0]['name'] == "USD") {
	                if($table == "cmaps"){
	                    $pRate = $p->getPRateByRegionAndYear($con, array($region),array($keyYear));
	                    $pRateSel = $p->getPRateByRegionAndYear($con, array($region),array($year));
	                }elseif($table == "aleph" || $table == 'wbd'){
	                	if ($year <= 2022) {
	                		$pRate = 4.99;
	                		$pRateSel = 4.99;	
	                	}else{
	                		$pRate = $p->getPRateByRegionAndYear($con, array($region),array($keyYear));
	                    	$pRateSel = $p->getPRateByRegionAndYear($con, array($region),array($year));
	                	}                    	
	                }else{
	                    $pRate = 1.0;
	                    $pRateSel = $pRate;
	                }
	            }else{
	                if($table == "cmaps" || $table == 'wbd' || $table == 'aleph'){
	                    $pRate = 1.0;
	                    $pRateSel = $pRate;
	                }else{                    
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
	            	$ccYear = $year;
	                $pRate = $p->getPRateByRegionAndYear($con,array($region),array($ccYear));
	                $pRateSel = $p->getPRateByRegionAndYear($con,array($region),array($ccYear));
	                //var_dump($pRateSel);
	            }            
	        }	
        } 


        switch ($table) {

            case 'cmaps':
                $columns = array("brand_id", "year", "month");
                
                $columnsValue = array($brand, $year, $month);
                break;

            case 'wbd':
                $columns = array('brand_id', 'year','month');

                $columnsValue = array($brand, $year, $month);

                $value .= "_value";

                break;

            case 'aleph':
                $columns = array('sales_office_id','brand_id', 'year','month');

                $columnsValue = array($region,$brand, $year, $month);

                $value = "gross_revenue";
                
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
                    //var_dump($brand);

                $columns = array("sales_office_id", "source", "type_of_revenue", "brand_id", "year", "month");
                $columnsValue = array($region, strtoupper($source), $value, $brand, $year, $month);
                $join = "LEFT JOIN brand b ON plan_by_brand.brand_id = b.ID";
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
            //var_dump($where);
            $selectSum = $sql->selectSum($con, $value, $as, $table, $join, $where);
            //var_dump($selectSum);
            //var_dump($sql->fetchSum($selectSum, $as));
            $tmp = $sql->fetchSum($selectSum, $as)["sum"];
            //var_dump($tmp);

            if($table == "cmaps" || $table == 'wbd' || $table == 'aleph'){                          
                $rtr = $tmp/$pRate;
            }else if($table == "plan_by_brand"){ 
            	if ($currency[0]['name'] == "USD") {
	                $pRate = 1.0;
	                $pRateSel = $pRate;
	                $rtr = $tmp*$pRate;   
            	}else{
            		if ($year <= 2022) {
            			if ($brandGroup == '3') {
	            			$pRateSel = 4.99;
	            			$rtr = $tmp*$pRateSel;                    
	            		}else{	            			
	            			$rtr = $tmp*$pRateSel;	
	            		}
            		}else{
            			$rtr = $tmp*$pRateSel;	
            		}
            		
            	}      
            //var_dump($rtr);           
            //var_dump($pRateSel);
            }else{
                $rtr = $tmp*$pRate;
            }           

        }
        //var_dump($rtr);
        return $rtr;
		
    }    



    public function addDN($mtx){
		
		for ($j=0; $j < 13; $j++) { 
			$mtxDN['previousAdSales'][$j] = 0.0;
			$mtxDN['previousSAP'][$j] = 0.0;
			$mtxDN['currentTarget'][$j] = 0.0;
			$mtxDN['currentAdSales'][$j] = 0.0;
			$mtxDN['currentSAP'][$j] = 0.0;	
			$mtxDN['currentCorporate'][$j] = 0.0;
			/*$mtxDN['currentCompany'][$j] = 0.0;
			$mtxDN['previousCompany'][$j] = 0.0;
			$mtxDN['currentTargetCompany'][$j] = 0.0;
			$mtxDN['currentCorporateCompany'][$j] = 0.0;
			$mtxDN['currentSAPCompany'][$j] = 0.0;
			$mtxDN['previousSAPCompany'][$j] = 0.0;*/

		}
		
		for ($j=0; $j < 13; $j++) { 
			for ($k=0; $k < sizeof($mtx['previousAdSales']); $k++) { 		
				$mtxDN['previousAdSales'][$j] += $mtx['previousAdSales'][$k][$j];
				$mtxDN['previousSAP'][$j] += $mtx['previousSAP'][$k][$j];
				$mtxDN['currentTarget'][$j] += $mtx['currentTarget'][$k][$j];
				$mtxDN['currentAdSales'][$j] += $mtx['currentAdSales'][$k][$j];
				$mtxDN['currentSAP'][$j] += $mtx['currentSAP'][$k][$j];
				$mtxDN['currentCorporate'][$j] += $mtx['currentCorporate'][$k][$j];	
				/*$mtxDN['currentCompany'][$j] += $mtx['currentCompany'][0][$j][$k];	
				$mtxDN['previousCompany'][$j] += $mtx['previousCompany'][0][$j][$k];	
				$mtxDN['currentTargetCompany'][$j] += $mtx['currentTargetCompany'][0][$j][$k];	
				$mtxDN['currentCorporateCompany'][$j] += $mtx['currentCorporateCompany'][0][$j][$k];	
				$mtxDN['currentSAPCompany'][$j] += $mtx['currentSAPCompany'][0][$j][$k];	
				$mtxDN['previousSAPCompany'][$j] += $mtx['previousSAPCompany'][0][$j][$k];*/
			}
		}
		return $mtxDN;
	}


	public function assemble($mtx){
		$pivot = date('m');
		$mtx = $this->addTotal($mtx);
		//$mtxCompany = $this->addTotalCompany($mtx);
		//$tmp = array_merge($mtx,$mtxCompany);
		//var_dump($mtxCompany);
		/*$mtx = $this->sumYTD($mtx,$pivot);
		$mtx = $this->addQuarters($mtx);*/
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
			array_push($mtx['currentTarget'][$i],$sum['currentTarget']['q4']);

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

	public function addTotalCompany($mtx,$company){
		
		
		for ($j=0; $j <sizeof($company); $j++) { 				
			
				$sum['currentCompany'][$j] = 0.0;
				$sum['previousCompany'][$j] = 0.0;
				$sum['currentTargetCompany'][$j] = 0.0;
				$sum['currentCorporateCompany'][$j] = 0.0;
				$sum['currentSAPCompany'][$j] = 0.0;
				$sum['previousSAPCompany'][$j] = 0.0;

			for ($i=0; $i <= 11; $i++) { 
				$sum['currentCompany'][$j] += $mtx['currentCompany'][0][$i][$j];	
				$sum['previousCompany'][$j] += $mtx['previousCompany'][0][$i][$j];	
				$sum['currentTargetCompany'][$j] += $mtx['currentTargetCompany'][0][$i][$j];	
				$sum['currentCorporateCompany'][$j] += $mtx['currentCorporateCompany'][0][$i][$j];	
				$sum['currentSAPCompany'][$j] += $mtx['currentSAPCompany'][0][$i][$j];	
				$sum['previousSAPCompany'][$j] += $mtx['previousSAPCompany'][0][$i][$j];
			}	
			

		}	

		 $mtx = array( "currentCompany" => $sum['currentCompany'],
					   "previousCompany" =>	$sum['previousCompany'],
					   "currentTargetCompany" => $sum['currentTargetCompany'],
					   "currentCorporateCompany" => $sum['currentCorporateCompany'],
					   "currentSAPCompany" => $sum['currentSAPCompany'],
					   "previousSAPCompany" => $sum['previousSAPCompany'] 			  
		        );
		//var_dump($mtx);
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
				//var_dump($mtx['previousAdSales'][$i][$j]);
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
