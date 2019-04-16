<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\dataBase;
use App\importSpreadsheet;
use App\queries;

class MatchingClientAgency extends Model
{
	
	public function Match($name){
		
		$db = new Database();
		$conn = $db->openConnection('DLA');

		$queries = new queries();

		switch ($name) {
			case 'Agency':
				
				break;
			
			case 'Client':
				
				break;
			
			default:
				
				break;
		}
	}

	public function verifyMatch($conn, $name, $table){

		$importedData = new importSpreadsheet();
		$sheetData = $data->SpreadsheetHandler($importedData->base());


		for ($i=0; $i < sizeof($sheetData); $i++) {
			$aux = $sheetData[$i][$name];
			$sql = "SELECT id, name FROM $table WHERE name='".$aux."'";
		}
		
	}

}