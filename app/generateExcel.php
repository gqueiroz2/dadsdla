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
use App\base;

class generateExcel extends Model {
    
	public function formatValuesArray($value, $table){
		
		if ($table == "TARGET" || $table == "CORPORATE" || $table == "ACTUAL") {
			$rtr = array("revenue");
		}elseif ($table == "ytd") {
			$rtr = array($value."_revenue", $value."_revenue_prate");
		}elseif($table == "cmaps"){
			$rtr = array($value);
		}else{
			$rtr = array($value."_revenue");
		}

		return $rtr;
	}

	public function month($sheet, $mtx, $brands, $currency, $value, $year, $form, $region, $title, $values){

		$head = $region."- ".ucfirst($title)." : BKGS - ".$year." (".$currency[0]['name']."/".strtoupper($value).")";

		if ($mtx[0] && $mtx[1]) {
			$sheet = $this->generateTV($sheet, $head, $mtx, $values);
			$sheet->setTitle('TV');

			$sheet->createSheet('Digital');

			



		}elseif ($mtx[0] && !$mtx[1]) {
				
		}

		return $sheet;
	}

	public function inArray($array, $value){
		
		for ($a=0; $a < sizeof($array); $a++) { 
			if ($value == $array[$a]) {
				return 1;
			}
		}

		return 0;

	}

	public function generateTV($sheet, $head, $mtx, $values){
		
		$startLetter = 'A';
		$letter = $startLetter;

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

		$bodyStyle = [
		    'font' => [
		        'bold' => true,
		        'name' => 'Verdana',
		        'size' => 7,
		        'color' => array('rgb' => '000000')
		    ],
		    'alignment' => [
		        'horizontal' => 'center',
		        'vertical' => 'center',
		        'wrapText' => true
		    ],
		];

		$base = new base();
		$months = $base->month;

		$headNames = array();

		foreach ($mtx[0][0] as $key => $val) {
			array_push($headNames, $key);
			
			if ($key == "impression_duration") {
				$key = "impression_duration (Seconds)";
			}

            $sheet->setCellValue($letter.'3', $key);
            $sheet->getStyle($letter.'3')->applyFromArray($headStyle);
            $letter++;    
        }

        $letter = $startLetter;
        $number = 4;
        //var_dump($mtx[0]);
        
        for ($m=0; $m < sizeof($mtx[0]); $m++) {
        	for ($v=0; $v < sizeof($mtx[0][$m]); $v++) {
        		if ($this->inArray($values, $headNames[$v])) {
        			$sheet->setCellValue($letter.$number, number_format($mtx[0][$m][$headNames[$v]]));	
        		}else{

        			if ($headNames[$v] == "month") {
						$sheet->setCellValue($letter.$number, $months[$mtx[0][$m][$headNames[$v]]-1][2]);	        				
        			}else{
        				$sheet->setCellValue($letter.$number, $mtx[0][$m][$headNames[$v]]);	
        			}
        		}

        		$sheet->getStyle($letter.$number)->applyFromArray($bodyStyle);	
        		$letter++;
        	}
        	$number++;
        	$letter = $startLetter;

        }

		return $sheet;
	}

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
