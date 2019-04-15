<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\dataBase;
use App\importSpreadsheet;
use App\queries;

class MatchingClientAgency extends Model
{
	
	public function MatchAgency(){

		$db = new Database();
		$conn = $db->openConnection('DLA');

		$data = new importSpreadsheet();
		$sheetData = $data->SpreadsheetHandler($data->base());

		$queries = new queries();
		$agency = $queries->getAgency($conn);

		for ($i=0; $i < ; $i++) { 
			# code...
		}
	}

}