<?php

namespace App;


use App\documentsHead;
use Illuminate\Database\Eloquent\Model;
use App\dataBase;

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

	protected $tableNames = array('agency',
								'agency_group',
								'brand',
								'brand_unit',
								'client',
								'client_group',
								'client_unit',
								'cmaps',
								'currency',
								'digital',
								'forecast',
								'forecast_unit',
								'header',
								'mini_header',
								'origin',
								'pacing_report',
								'pacing_report_unit',
								'plan_by_brand',
								'plan_by_sales',
								'plan_source',
								'p_rate',
								'region',
								'rolling_forecast',
								'sales_rep',
								'sales_rep_group',
								'sales_rep_unit',
								'sap_digital_executive',
								'user',
								'user_types',
								'ytd');



	public function getRegion(){

		$sql = "SELECT ID,name FROM region";
		$result = $conn->query($sql);

		$region = array();

		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$region[$row["name"]] = $row["ID"];
			}
		}else{
			return false;
		}
	
		return $region;
	}

	public function truncateAll($con){

		$start = "SET FOREIGN_KEY_CHECKS = 0;";

		$tableNames = $this->tableNames;

		for ($i=0; $i <sizeof($this->tableNames) ; $i++) { 
			$middle[$i] = "TRUNCATE $tableNames[$i];";
		}

		$end = "SET FOREIGN_KEY_CHECKS = 1;";

		if($con->query($start) === true){
			var_dump("foi carai");
		}else{
			var_dump($con->error);
		}

		for ($i=0; $i <sizeof($middle) ; $i++) { 
			if($con->query($middle[$i]) === true){
				var_dump("foi carai");
			}else{
				var_dump($con->error);
			}
		}

		if($con->query($end) === true){
			var_dump("foi carai");
		}else{
			var_dump($con->error);
		}


	}

	public function getBrands(){
		$select = "SELECT ID,name FROM brand";

		$brand = array();

		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$brand[$row["name"]] = $row["ID"];
			}
		}else{
			return false;
		}
	
		return $brand;
	}

	public function getSalesRep($con){

		//posso passar região se for necessario para ter mais certeza na busca

		$sql = "SELECT ID,name FROM sales_rep ORDER BY name ASC";

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

	//recebe a conexão com o banco
	public function getClientUnit($conn){

		$sql = "SELECT ID, name FROM client_unit";

		$result = $conn->query($sql);

		$clientList = array();

		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$clientList[$row["ID"]] = $row["name"];
			}
		}else{
			return 0;
		}

		return $clientList;

	}


	//recebe a conexão com o banco
	public function getAgency($conn){

		$sql = "SELECT ID, name FROM agency";

		$result = $conn->query($sql);

		$agencyList = array();

		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$agencyList[$row["ID"]] = $row["name"];
			}
		}else{
			return 0;
		}

		return $agencyList;

	}

	//recebe a conexão com o banco
	public function getAgencyUnit($conn){

		$sql = "SELECT ID, name FROM agency_unit";

		$result = $conn->query($sql);

		$agencyList = array();

		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$agencyList[$row["ID"]] = $row["name"];
			}
		}else{
			return 0;
		}

		return $agencyList;

	}

	public function getCampaingCurrency($matrix){

		$sql = "SELECT ID,name FROM currency";
		$result = $conn->query($sql);

		$currency = array();

		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$currency[$row["name"]] = $row["ID"];
			}
		}else{
			return false;
		}

		return $currency;
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

		for ($i=1; $i <sizeof($dbHead); $i++) { 
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
	