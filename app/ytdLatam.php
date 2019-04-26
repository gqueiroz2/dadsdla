<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Management;
use App\origin;
use App\region;
use App\brand;
use App\sql;
use App\salesRep;
use App\pRate;


class ytdLatam extends Management{

	public function handler($con,$sheet){
		$sql = new sql();/*
		$tmpSheet = $this->putIndex($sheet,'YTD');
		for ($t=0; $t < sizeof($tmpSheet); $t++) { 
			$agencyUnits[$t] = $tmpSheet[$t]['Agency'];
			$clientUnits[$t] = $tmpSheet[$t]['Client'];
		}

		sort($clientUnits);
		sort($agencyUnits);

		$clientUnits = array_values(array_unique($clientUnits));
		$agencyUnits = array_values(array_unique($agencyUnits));

		$vlau = $this->checkForMissMatches($con,"client",$clientUnits);
		*/
/*
		var_dump($agencyUnits);
		var_dump($clientUnits);
*/


		/*
		$tmpSheet = $this->matrixIndex($con,$sheet);
		
		var_dump($tmpSheet);
			
		for ($t=0; $t < sizeof($tmpSheet); $t++) { 
			//$bool = $this->insertFromExcel($con,$sql,$tmpSheet[$t]);			
		}
		*/
		
		
	}

	public function insertFromExcel($con,$sql,$sheet){

		$table = "ytd";

		$columns = "campaign_sales_office_id,
				    sales_representant_office_id,
				    brand_id,
				    sales_rep_id,
				    client_id,
				    agency_id,
				    campaign_currency_id,
				    year,
				    month,
				    brand_feed,
				    client_product,
				    order_reference,
				    campaign_reference,
				    spot_duration,
				    impression_duration,
				    num_spot,
				    gross_revenue,
				    net_revenue,
				    net_net_revenue,
				    gross_revenue_prate,
				    net_revenue_prate,
				    net_net_revenue_prate
				   ";

	   $values = "'".$sheet['Campaign Sales Office']."',
				  '".$sheet['Sales Rep Sales Office']."',
				  '".$sheet['Channel Brand']."',
				  '".$sheet['Sales Rep']."',
				  '".$sheet['Client']."',
				  '".$sheet['Agency']."',
				  '".$sheet['Campaign Currency']."',
				  '".$sheet['Calendar Year']."',
				  '".$sheet['Calendar Month']."',				  
				  '".$sheet['Channel Feed']."',
				  '".$sheet['Client Product']."',				  
				  '".$sheet['Order Reference']."',
				  '".$sheet['Campaign Reference']."',
				  '".$sheet['Spot Duration']."',				  
				  '".$sheet['Impression Duration (Seconds)']."',
				  '".$sheet['Num of Spot Impressions']."',
				  '".$sheet['Revenue (Campaign Currency)']."',
				  '".$sheet['Net Revenue (Campaign Currency)']."',
				  '".$sheet['Net Net Revenue (Campaign Currency)']."',
				  '".$sheet['Revenue (Current Plan Rate)']."',
				  '".$sheet['Net Revenue (Current Plan Rate)']."',
				  '".$sheet['Net Net Revenue (Current Plan Rate)']."'";

		$bool = $sql->insert($con,$table,$columns,$values);

		var_dump($bool['bool']);
		echo $bool['msg']."<br>";

	}

	public function matrixIndex($con,$sheet){
		$r = new region();
		$b = new brand();
		$sr = new salesRep();
		$pr = new pRate();
		$cli = new client();
		$ag = new agency();

		$region = $r->getRegion($con,false);
		$brandUnit = $b->getBrandUnit($con,false);
		$salesRepUnit = $sr->getSalesRepUnit($con,false);
		$currency = $pr->getCurrency($con,false);
		$clientUnit = $cli->getClientUnit($con,false);
		$agencyUnit = $ag->getAgencyUnit($con,false);

		
		$fMatrix = $tmpSheet;

		//função para tratar informações internas da matrix de entrada (YTD)
		
		for ($s=0; $s <sizeof($tmpSheet); $s++) { 
		    
			for ($r=0; $r < sizeof($region); $r++) { 
				if($region[$r]['name'] == $fMatrix[$s]["Campaign Sales Office"]){
					$campaignSalesOfficeID = $region[$r]['id'];
				}

				if( $region[$r]['name'] == $fMatrix[$s]["Sales Rep Sales Office"] ){
					$salesRepSalesOfficeID = $region[$r]['id'];
				}
			}

			for ($b=0; $b < sizeof($brandUnit); $b++) { 
				
				if( $brandUnit[$b]['brandUnit'] == $fMatrix[$s]["Channel Brand"] ){
					$brandID = $brandUnit[$b]['brandID'];
				}

			}

			for ($sru=0; $sru < sizeof($salesRepUnit); $sru++) { 
				
				if( $salesRepUnit[$sru]['salesRepUnit'] == $fMatrix[$s]["Sales Rep"] ){
					$brandID = $salesRepUnit[$sru]['salesRepID'];
				}

			}

			for ($c=0; $c < sizeof($currency); $c++) { 
				
				if( $currency[$c]['name'] == $fMatrix[$s]["Campaign Currency"] ){
					$currencyID = $currency[$c]['id'];
				}

			}

			for ($cc=0; $cc < sizeof($clientUnit); $cc++) { 
				
				if($clientUnit[$cc]['clientUnit'] == $fMatrix[$s]["Client"]){
					$clientID = $clientUnit[$cc]['clientID'];
				}

			}

			for ($cc=0; $cc < sizeof($agencyUnit); $cc++) { 
				
				if($agencyUnit[$cc]['agencyUnit'] == $fMatrix[$s]["Agency"]){
					$agencyID = $agencyUnit[$cc]['agencyID'];
				}

			}

			$fMatrix[$s]["Campaign Sales Office"] = $campaignSalesOfficeID;
			$fMatrix[$s]["Sales Rep Sales Office"] = $salesRepSalesOfficeID;
			$fMatrix[$s]['Channel Brand'] = $brandID;
			$fMatrix[$s]["Sales Rep"] = $brandID;
			$fMatrix[$s]["Client"] = $clientID;
			$fMatrix[$s]["Agency"] = $agencyID;
		    $fMatrix[$s]["Calendar Month"] = str_replace(' ','',$tmpSheet[$s]["Calendar Month"]);// remove espaços do calendar month		    
		    $fMatrix[$s]["Calendar Month"] = substr($tmpSheet[$s]["Calendar Month"], 0,  (strlen($fMatrix[$s]["Calendar Month"]) - 4));//remove o ano do calendar month		    
		    $fMatrix[$s]["Campaign Currency"] = $currencyID;
		    $fMatrix[$s]["Impression Duration (Seconds)"] = floatval($tmpSheet[$s]["Impression Duration (Seconds)"]);		    	
		    $fMatrix[$s]["Num of Spot Impressions"] = intval($tmpSheet[$s]["Num of Spot Impressions"]);
		    $fMatrix[$s]["Revenue (Campaign Currency)"] = $this->fixExcelNumber($tmpSheet[$s]["Revenue (Campaign Currency)"]);
			$fMatrix[$s]["Net Revenue (Campaign Currency)"] =$this->fixExcelNumber($tmpSheet[$s]["Net Revenue (Campaign Currency)"]);	
			$fMatrix[$s]["Net Net Revenue (Campaign Currency)"] =$this->fixExcelNumber($tmpSheet[$s]["Net Net Revenue (Campaign Currency)"]);
			$fMatrix[$s]["Revenue (Current Plan Rate)"] =$this->fixExcelNumber2($tmpSheet[$s]["Revenue (Current Plan Rate)"]);
			$fMatrix[$s]["Net Revenue (Current Plan Rate)"] = $this->fixExcelNumber2($tmpSheet[$s]["Net Revenue (Current Plan Rate)"]);
			$fMatrix[$s]["Net Net Revenue (Current Plan Rate)"] = $this->fixExcelNumber2($tmpSheet[$s]["Net Net Revenue (Current Plan Rate)"]);
		}

		return $fMatrix;
	}
  
}
