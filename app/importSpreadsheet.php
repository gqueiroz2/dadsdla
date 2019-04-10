<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\rootSpreadsheet;
use App\dataBase;
use App\queries;
use App\documentsHead;

class importSpreadsheet extends Model
{
	


	public function import($matrix){
		
		$DH = new documentsHead();
		$RSS = new rootSpreadsheet();
		$queries = new queries();

		//Funciona só com YTD

		$matrix = $RSS->clearSpreadSheet($matrix);

		var_dump($matrix);

		$type = $DH->findHead($matrix);
		
		// Ainda falta adicionar novos tipos de documentos, por enquanto só funcionando com YTD

		switch ($type) {
			case 'YTD':
				$mtx = $this->ytdlatam($matrix);
				$queries->insertYtd($mtx);
				break;			
			default:
				$mtx = false;
				break;
		}

		return $mtx;
	}


	public function ytdlatam($matrix){


		$RSS = new rootSpreadsheet();

		$mtx = $RSS->putIndexes($matrix,'YTD');

		//função para tratar informações internas da matrix de entrada (YTD)
		for ($i=0; $i <sizeof($mtx); $i++) { 
		    
		    $mtx[$i]["Calendar Month"] = str_replace(' ','',$mtx[$i]["Calendar Month"]);// remove espaços do calendar month
		    
		    $mtx[$i]["Calendar Month"] = substr($mtx[$i]["Calendar Month"], 0,  (strlen($mtx[$i]["Calendar Month"]) - 4));//remove o ano do calendar month
		    	
		    $mtx[$i]["Impression Duration (Seconds)"] = floatval($mtx[$i]["Impression Duration (Seconds)"]);
		    	
		    $mtx[$i]["Num of Spot Impressions"] = intval($mtx[$i]["Num of Spot Impressions"]);

		    $mtx[$i]["Revenue (Campaign Currency)"] = $RSS->fixExcelNumber($mtx[$i]["Revenue (Campaign Currency)"]);

			$mtx[$i]["Net Revenue (Campaign Currency)"] =$RSS->fixExcelNumber($mtx[$i]["Net Revenue (Campaign Currency)"]);
	
			$mtx[$i]["Net Net Revenue (Campaign Currency)"] =$RSS->fixExcelNumber($mtx[$i]["Net Net Revenue (Campaign Currency)"]);

			$mtx[$i]["Revenue (Current Plan Rate)"] =$RSS->fixExcelNumber2($mtx[$i]["Revenue (Current Plan Rate)"]);

			$mtx[$i]["Net Revenue (Current Plan Rate)"] = $RSS->fixExcelNumber2($mtx[$i]["Net Revenue (Current Plan Rate)"]);

			$mtx[$i]["Net Net Revenue (Current Plan Rate)"] = $RSS->fixExcelNumber2($mtx[$i]["Net Net Revenue (Current Plan Rate)"]);

		}

		//$mtx = $RSS->filterMonthYear($mtx);

		$mtx = $this->putIdYtd($mtx);


		return $mtx;
	}


	public function putIdYtd($matrix){

		$db = new dataBase();
		$queries = new queries();


		/*
			NENHUM ID ESTÁ CERTO AQUI, APOS TODAS INFORMAÇÕES FOREM INSERIDAS O CODIGO DEVE SER REVISADO
			NENHUM ID ESTÁ CERTO AQUI, APOS TODAS INFORMAÇÕES FOREM INSERIDAS O CODIGO DEVE SER REVISADO
			NENHUM ID ESTÁ CERTO AQUI, APOS TODAS INFORMAÇÕES FOREM INSERIDAS O CODIGO DEVE SER REVISADO
			NENHUM ID ESTÁ CERTO AQUI, APOS TODAS INFORMAÇÕES FOREM INSERIDAS O CODIGO DEVE SER REVISADO
			NENHUM ID ESTÁ CERTO AQUI, APOS TODAS INFORMAÇÕES FOREM INSERIDAS O CODIGO DEVE SER REVISADO
			NENHUM ID ESTÁ CERTO AQUI, APOS TODAS INFORMAÇÕES FOREM INSERIDAS O CODIGO DEVE SER REVISADO
			NENHUM ID ESTÁ CERTO AQUI, APOS TODAS INFORMAÇÕES FOREM INSERIDAS O CODIGO DEVE SER REVISADO
			NENHUM ID ESTÁ CERTO AQUI, APOS TODAS INFORMAÇÕES FOREM INSERIDAS O CODIGO DEVE SER REVISADO
			NENHUM ID ESTÁ CERTO AQUI, APOS TODAS INFORMAÇÕES FOREM INSERIDAS O CODIGO DEVE SER REVISADO
			NENHUM ID ESTÁ CERTO AQUI, APOS TODAS INFORMAÇÕES FOREM INSERIDAS O CODIGO DEVE SER REVISADO
			NENHUM ID ESTÁ CERTO AQUI, APOS TODAS INFORMAÇÕES FOREM INSERIDAS O CODIGO DEVE SER REVISADO
			NENHUM ID ESTÁ CERTO AQUI, APOS TODAS INFORMAÇÕES FOREM INSERIDAS O CODIGO DEVE SER REVISADO
			NENHUM ID ESTÁ CERTO AQUI, APOS TODAS INFORMAÇÕES FOREM INSERIDAS O CODIGO DEVE SER REVISADO
			NENHUM ID ESTÁ CERTO AQUI, APOS TODAS INFORMAÇÕES FOREM INSERIDAS O CODIGO DEVE SER REVISADO
			NENHUM ID ESTÁ CERTO AQUI, APOS TODAS INFORMAÇÕES FOREM INSERIDAS O CODIGO DEVE SER REVISADO
			NENHUM ID ESTÁ CERTO AQUI, APOS TODAS INFORMAÇÕES FOREM INSERIDAS O CODIGO DEVE SER REVISADO
			NENHUM ID ESTÁ CERTO AQUI, APOS TODAS INFORMAÇÕES FOREM INSERIDAS O CODIGO DEVE SER REVISADO
			NENHUM ID ESTÁ CERTO AQUI, APOS TODAS INFORMAÇÕES FOREM INSERIDAS O CODIGO DEVE SER REVISADO
			NENHUM ID ESTÁ CERTO AQUI, APOS TODAS INFORMAÇÕES FOREM INSERIDAS O CODIGO DEVE SER REVISADO
			NENHUM ID ESTÁ CERTO AQUI, APOS TODAS INFORMAÇÕES FOREM INSERIDAS O CODIGO DEVE SER REVISADO
			NENHUM ID ESTÁ CERTO AQUI, APOS TODAS INFORMAÇÕES FOREM INSERIDAS O CODIGO DEVE SER REVISADO
			NENHUM ID ESTÁ CERTO AQUI, APOS TODAS INFORMAÇÕES FOREM INSERIDAS O CODIGO DEVE SER REVISADO
			NENHUM ID ESTÁ CERTO AQUI, APOS TODAS INFORMAÇÕES FOREM INSERIDAS O CODIGO DEVE SER REVISADO
			NENHUM ID ESTÁ CERTO AQUI, APOS TODAS INFORMAÇÕES FOREM INSERIDAS O CODIGO DEVE SER REVISADO
			NENHUM ID ESTÁ CERTO AQUI, APOS TODAS INFORMAÇÕES FOREM INSERIDAS O CODIGO DEVE SER REVISADO
			NENHUM ID ESTÁ CERTO AQUI, APOS TODAS INFORMAÇÕES FOREM INSERIDAS O CODIGO DEVE SER REVISADO
		*/

		$temp = $queries->getRegion();


		for ($i=0; $i <sizeof($matrix) ; $i++) { 
			for ($j=0; $j <sizeof($temp["nome"]); $j++) { 
				if ($matrix[$i]["Campaign Sales Office"] == $temp["nome"][$j]) {
					$matrix[$i]["Campaign Sales Office ID"] = $temp["id"][$j];
				}else{
					$matrix[$i]["Campaign Sales Office ID"] = 0;
				}
				if($matrix[$i]["Sales Rep Sales Office"] == $temp["nome"][$j]){
					$matrix[$i]["Sales Rep Sales Office ID"] = $temp["id"][$j];
				}else{
					$matrix[$i]["Sales Rep Sales Office ID"] = 0;
				}
			}
		}

		$temp = $queries->getBrands();

		for ($i=0; $i <sizeof($matrix) ; $i++) { 
			for ($j=0; $j <sizeof($temp["nome"]); $j++) { 
				if ($matrix[$i]["Channel Brand"] == $temp["nome"][$j]) {
					$matrix[$i]["Channel Brand ID"] = $temp["id"][$j];
				}else{
					$matrix[$i]["Channel Brand ID"] = 0;
				}
			}
		}		

		$temp = $queries->getSalesRep($matrix);

		for ($i=0; $i <sizeof($matrix) ; $i++) { 
			for ($j=0; $j <sizeof($temp) ; $j++) { 
				if ($matrix[$i]["Sales Rep"] == $temp[$j]) {
					$matrix[$i]["Sales Rep ID"] = $j+1;
				}else{
					$matrix[$i]["Sales Rep ID"] = 0;
				}
			}
		}

		$temp = $queries->getClients($matrix);

		for ($i=0; $i <sizeof($matrix) ; $i++) { 
			for ($j=0; $j <sizeof($temp) ; $j++) { 
				if ($matrix[$i]["Client"] == $temp[$j]) {
					$matrix[$i]["Client ID"] = $j+1;
				}else{
					$matrix[$i]["Client ID"] = 0;
				}
			}
		}

		$temp = $queries->getAgency($matrix);


		for ($i=0; $i <sizeof($matrix) ; $i++) { 
			for ($j=0; $j <sizeof($temp) ; $j++) { 
				if ($matrix[$i]["Agency"] == $temp[$j]) {
					$matrix[$i]["Agency ID"] = $j+1;
				}else{
					$matrix[$i]["Agency ID"] = 0;
				}
			}
		}

		$temp = $queries->getCampaingCurrency($matrix);

		for ($i=0; $i <sizeof($matrix) ; $i++) { 
			for ($j=0; $j <sizeof($temp) ; $j++) { 
				if ($matrix[$i]["Campaign Currency"] == $temp[$j]) {
					$matrix[$i]["Campaign Currency ID"] = $j+1;
				}else{
					$matrix[$i]["Campaign Currency ID"] = 0;
				}
			}
		}

		return $matrix;

	}
}
