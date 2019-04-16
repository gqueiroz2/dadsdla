<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\dataBase;
use App\importSpreadsheet;
use App\queries;

class matchingClientAgency extends Model
{
	
	public function match($name){
		
		$db = new Database();
		$conn = $db->openConnection('DLA');

		$queries = new queries();

		switch ($name) {
			case 'Agency':
				$data = verifyMatch($conn, 'Agency', 'agency_unit');
				break;
			
			case 'Client':
				$data = verifyMatch($conn, 'Client', 'client_unit');
				break;
			
			default:
				$data = false;
				break;
		}
	}

	public function verifyMatch($conn, $name, $table){

		$importedData = new importSpreadsheet();
		$sheetData = $data->SpreadsheetHandler($importedData->base());
		$exist = array();

		for ($i=0; $i < sizeof($sheetData); $i++) {
			$aux = $sheetData[$i][$name];
			$sql = "SELECT ID, name FROM $table WHERE name='".$aux."'";

			$result = $conn->query($sql);

			if ($result->num_rows > 0) {
				while ($row = $result->fetch_assoc()) {
					array_push($exist, $row["name"]);
				}
			}
		}

		(sizeof($exist) > 0) ? return $exist : return false;
	}

}