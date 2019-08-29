<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\sql;
use App\planByBrand;
use App\ytd;
use App\cmaps;
use App\digital;

class generateExcel extends Model {
    
	/*public function month($sheet, $mtx, $brands, $months, $currency, $value, $year, $form, $region){

		$startLetter = 'A';
		$letter = $startLetter;

		$head = $region."- Monthly : ".$form." - ".$year." (".$currency[0]['name']."/".strtoupper($value).")";

		$sheet->setCellValue($letter.'1', $head);
		$sheet->getStyle($letter.'1')->getFont()->setSize(20);

		$headStyle = [
		    'font' => [
		        'bold' => true,
		        'name' => 'Verdana',
		        'size' => 7,
		        'color' => array('rgb' => 'FFFFFF')
		    ],
		    'alignment' => [
		        'horizontal' => 'center',
		        'vertical' => 'center',
		        'wrapText' => true
		    ],
		    'fill' => [
		        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
		        'startColor' => [
		            'argb' => '0070c0',
		        ],
		    ],
		];
		

		return $sheet;
	}*/

	public function selectDataMonth($con, $region, $year, $brands, $form, $currency, $value){
		
		for ($b=0; $b < sizeof($brands); $b++) { 
			$brand_id[$b] = $brands[$b][0];
		}

		$sql = new sql();

		if ($form == "TARGET" || $form == "CORPORATE" || $form == "ACTUAL") {

			$cols = array("sales_office_id", "year", "brand_id", "source", "currency_id", "type_of_revenue");
			$colsValue = array($region, $year, $brand_id, $form, $currency[0]['id'], $value);

			$where = $sql->where($cols, $colsValue);

			$p = new planByBrand();

			$values = $p->getWithFilter($con, $where, $currency, $region);

		}elseif ($form == "ytd") {
			
			$cols = array("sales_representant_office_id", "year", "brand_id");
			$colsValue = array($region, $year, $brand_id);

			$where = $sql->where($cols, $colsValue);

			$y = new ytd();

			$values = $y->getWithFilter($con, $value, $currency, $region, $where);

		}else{

			$cols = array("year", "brand_id");
			$colsValue = array($year, $brand_id);

			$where = $sql->where($cols, $colsValue);

			$c = new cmaps();

			$values = $c->getWithFilter($con, $value, $region, $currency, $where);
		}

		if ($form != "TARGET" && $form != "CORPORATE" && $form != "ACTUAL") {
			$cols = array("d.region_id", "year", "brand_id", "currency_id");
			$colsValue = array($region, $year, $brand_id, $currency[0]['id']);

			$where = $sql->where($cols, $colsValue);

			$d = new digital();

			$valuesDigital = $d->getWithFilter($con, $value, $where, $currency, $region);
		}else{
			$valuesDigital = null;
		}

		return array($values, $valuesDigital);

	}

}
