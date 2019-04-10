<?php

namespace App;


use App\documentsHead;
use Illuminate\Database\Eloquent\Model;

class queries extends Model
{

	protected $ytdDatabase = array('campaign_sales_office_id',
									'sales_representative_office_id',
									'year',
									'month',
									'brand_id',
									'brand_feed',
									'sales_rep_id',
									'client_id',
									'client_product',
									'agency_id',
									'order_reference',
									'campaign_reference',
									'spot_duration',
									'campaign_currency_id',
									'impression_duration',
									'num_spot',
									'gross_revenue',
									'net_revenue',
									'net_net_revenue',
									'gross_revenue_prate',
									'net_revenue_prate',
									'net_net_revenue_prate');

	protected $YTDHeadWithID = array('Campaign Sales Office ID',
								'Sales Rep Sales Office ID',
								'Calendar Year',
								'Calendar Month',
								'Channel Brand ID',
								'Channel Feed',
								'Sales Rep ID',
								'Client ID',
								'Client Product',
								'Agency ID',
								'Order Reference',
								'Campaign Reference',
								'Spot Duration',
								'Campaign Currency ID',
								'Impression Duration (Seconds)',
								'Num of Spot Impressions',
								'Revenue (Campaign Currency)',
								'Net Revenue (Campaign Currency)',
								'Net Net Revenue (Campaign Currency)',
								'Revenue (Current Plan Rate)',
								'Net Revenue (Current Plan Rate)',
								'Net Net Revenue (Current Plan Rate)');





	public function getRegion(){
		$select = "SELECT ID,name FROM region";

		$temp["nome"] = array("Brazil","Colombia","Argentina","Miami","Mexico"); //matrix temporaria de exemplod e como seria a saida
		$temp["id"] = array(1,2,3,4,5); //matrix temporaria de exemplod e como seria a saida
	
		return $temp;
	}


	public function getBrands(){
		$select = "SELECT ID,name FROM brand";


		$temp["nome"] = array("Discovery", "Discovery Home and Health", "Discovery Kids", "Animal Planet", "TLC", "ID", "Discovery Turbo");
		$temp["id"] = array(1,2,3,4,5,6,7);

		return $temp;
	}

	public function getSalesRep($matrix){


		//posso passar região se for necessario para ter mais certeza na busca

		$select = "SELECT ID,name FROM sales_rep";

		$salesRep = array();

		for ($i=0; $i <sizeof($matrix) ; $i++) { 
			$validator = 1;
			for ($j=0; $j <sizeof($salesRep) ; $j++) { 
				
				if ($matrix[$i]["Sales Rep"] == $salesRep[$j]) {
					$validator = 0;
					break;
				}

			}

			if ($validator == 1) {
				array_push($salesRep, $matrix[$i]["Sales Rep"]);
			}
		}

		return $salesRep;
	}


	public function getClients($matrix){

		/*	
			COMENTARIO IMPORTANTE PARA QUANDO FOR FAZER MUDANÇA
			
			DEVE SE PEGAR PRIMEIRO OS CLIENTES (FILHOS), QUE ESTÃO PRESENTES EM ALGUMA TABELA, COMPARAR, E DEVOLVER O ID DO PAI
	
			ENTÃO DEVE SE TER PREENCHIDO AS TABELAS DE CLIENTE FILHO E CLIENTE PAI PARA Q ESSA FUNÇÃO POSSA SER FINALIZADA
		*/

		$select = "SELECT ID,name FROM client";

		$clientList = array();

		for ($i=0; $i <sizeof($matrix) ; $i++) { 
			$validator = 1;
			for ($j=0; $j <sizeof($clientList) ; $j++) { 
				if ($matrix[$i]["Client"] == $clientList[$j]) {
					$validator = 0;
					break;
				}
			}

			if ($validator == 1) {
				array_push($clientList, $matrix[$i]["Client"]);
			}

		}

		return $clientList;
	}


	public function getAgency($matrix){

		$select = "SELECT ID,name FROM agency";

		$agencyList = array();

		for ($i=0; $i <sizeof($matrix) ; $i++) { 
			$validator = 1;
			for ($j=0; $j <sizeof($agencyList) ; $j++) { 
				if ($matrix[$i]["Agency"] == $agencyList[$j]) {
					$validator = 0;
					break;
				}
			}

			if ($validator == 1) {
				array_push($agencyList, $matrix[$i]["Agency"]);
			}

		}

		return $agencyList;
	}

	public function getCampaingCurrency($matrix){

		$select = "SELECT ID,name FROM currency";

		$currencyList = array();

		for ($i=0; $i <sizeof($matrix) ; $i++) { 
			$validator = 1;
			for ($j=0; $j <sizeof($currencyList) ; $j++) { 
				if ($matrix[$i]["Campaign Currency"] == $currencyList[$j]) {
					$validator = 0;
					break;
				}
			}

			if ($validator == 1) {
				array_push($currencyList, $matrix[$i]["Campaign Currency"]);
			}

		}

		return $currencyList;
	}

	public function insertYTD($matrix){
		//resposta = remover mes atual e adicionar só mes atual
		$DH = new documentsHead();

		$year = $matrix[0]["Calendar Year"];

		$delete = "DELETE FROM ytd_latam  WHERE year = '$year'";//Remove mes atual para atualizar informações 

		for ($i=0; $i <sizeof($matrix) ; $i++) { 
			$this->insert($matrix[$i],$this->ytdDatabase,$this->YTDHeadWithID,'ytd_latam');
		}
	}

	public function insert($matrix,$dbHead,$matrixHead,$table){
		$insert = "INSERT INTO $table ( $dbHead[0]";

		for ($i=1; $i <sizeof($dbHead) ; $i++) { 
			$insert .= ", $dbHead[$i]";
		}
		$temp = $matrix[$matrixHead[0]];
		$insert .= ") VALUES ('$temp";

		for ($i=1; $i < sizeof($matrixHead); $i++) { 
			$temp = $matrix[$matrixHead[$i]];
			$insert .= "', '$temp";
		}
		$insert .= "')";

	}

}
	