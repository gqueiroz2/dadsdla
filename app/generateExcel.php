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
use App\pRate;

class generateExcel extends Model {
    
	public function formatValuesArray($value, $table){
		
		if ($table == "TARGET" || $table == "CORPORATE" || $table == "ACTUAL") {
			$rtr = array("revenue");
		}elseif ($table == "ytd") {
			$rtr = array($value."_revenue", $value."_revenue_prate");
		}elseif($table == "cmaps"){
			$rtr = array($value, "discount");
		}else{
			$rtr = array($value."_revenue", "agency_commission_percentage", "commission");
		}

		return $rtr;
	}

	public function inArray($array, $value){
		
		for ($a=0; $a < sizeof($array); $a++) { 
			if ($value == $array[$a]) {
				return 1;
			}
		}

		return 0;

	}

	public function month($spreadsheet, $mtx, $plan, $currency, $value, $year, $region, $title, $titlePlan, $values, $plans){

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

		
		$head = $region." - ".ucfirst($title)." : BKGS - ".$year." (".$currency[0]['name']."/".strtoupper($value).")";
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setTitle('TV');
		$sheet = $this->generateTV($sheet, $head, $mtx[0], $values, $headStyle, $bodyStyle, $months, $currency[0]['name']);

		$spreadsheet->createSheet();

		$head = $region." - ".ucfirst($title)." : Digital - ".$year." (".$currency[0]['name']."/".strtoupper($value).")";
		$values = $this->formatValuesArray($value, "digital");
		
		$spreadsheet->setActiveSheetIndex(1);
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setTitle('Digital');
		$sheet = $this->generateDigital($sheet, $head, $mtx[1], $values, $headStyle, $bodyStyle, $months, $currency[0]['name']);

		$spreadsheet->createSheet();

		$head = $region." - ".ucfirst($title)." : By Brand (".ucfirst($titlePlan).") - ".$year." (".$currency[0]['name']."/".strtoupper($value).")";
		$values = $this->formatValuesArray($value, $titlePlan);

		$spreadsheet->setActiveSheetIndex(2);
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setTitle('Plan By Brand');
		$sheet = $this->generatePlan($sheet, $head, $plan[0], $values, $headStyle, $bodyStyle, $months, $currency[0]['name']);

		$spreadsheet->setActiveSheetIndex(0);

		return $sheet;
	}

	public function generateTV($sheet, $head, $mtx, $values, $headStyle, $bodyStyle, $months, $currency){

		$startLetter = 'A';
		$letter = $startLetter;

		$sheet->setCellValue($letter.'1', $head);
		$sheet->getStyle($letter.'1')->getFont()->setSize(20);

		if (is_array($mtx)) {
			$headNames = array();

			foreach ($mtx[0] as $key => $val) {
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
	        
	        for ($m=0; $m < sizeof($mtx); $m++) {
	        	for ($v=0; $v < sizeof($mtx[$m]); $v++) {
	        		if ($this->inArray($values, $headNames[$v])) {
	        			if ($headNames[$v] == "discount") {
	        				if ($mtx[$m][$headNames[$v]] == 0) {
	        					$sheet->setCellValue($letter.$number, $mtx[$m][$headNames[$v]]);
	        				}else{
	        					$sheet->setCellValue($letter.$number, $mtx[$m][$headNames[$v]]/100);
	        					$sheet->getStyle($letter.$number)->getNumberFormat()->setFormatCode('#%');
	        				}
	        			}else{
	        				$sheet->setCellValue($letter.$number, $mtx[$m][$headNames[$v]]);
	        				$sheet->getStyle($letter.$number)->getNumberFormat()->setFormatCode('#,##0.00');
	        			}
	        		}else{

	        			if ($headNames[$v] == "month") {
							$sheet->setCellValue($letter.$number, $months[$mtx[$m][$headNames[$v]]-1][2]);
	        			}elseif ($headNames[$v] == "campaign_currency") {
	        				$sheet->setCellValue($letter.$number, $currency);
	        			}else{
	        				$sheet->setCellValue($letter.$number, $mtx[$m][$headNames[$v]]);	
	        			}
	        		}

	        		$sheet->getStyle($letter.$number)->applyFromArray($bodyStyle);	
	        		$letter++;
	        	}
	        	$number++;
	        	$letter = $startLetter;

	        }
		}else{
			$sheet->setCellValue($letter.'3', "Don't have TV values");
			$sheet->getStyle($letter.'3')->getFont()->setSize(20);
		}

		return $sheet;
	}


	public function generateDigital($sheet, $head, $mtx, $values, $headStyle, $bodyStyle, $months, $currency){

		$startLetter = 'A';
		$letter = $startLetter;

		$sheet->setCellValue($letter.'1', $head);
		$sheet->getStyle($letter.'1')->getFont()->setSize(20);

		if (is_array($mtx)) {
			
			$headNames = array();

			foreach ($mtx[0] as $key => $val) {
				array_push($headNames, $key);

	            $sheet->setCellValue($letter.'3', $key);
	            $sheet->getStyle($letter.'3')->applyFromArray($headStyle);
	            $letter++;    
	        }

	        $letter = $startLetter;
	        $number = 4;

	        for ($m=0; $m < sizeof($mtx); $m++) {
	        	for ($v=0; $v < sizeof($mtx[$m]); $v++) {
	        		if ($this->inArray($values, $headNames[$v])) {
	        			if ($headNames[$v] == "agency_commission_percentage") {
	        				if ($mtx[$m][$headNames[$v]] == 0) {
	        					$sheet->setCellValue($letter.$number, $mtx[$m][$headNames[$v]]);
	        				}else{
	        					$sheet->setCellValue($letter.$number, $mtx[$m][$headNames[$v]]);
	        					$sheet->getStyle($letter.$number)->getNumberFormat()->setFormatCode('#%');
	        				}
	        			}else{
	        				$sheet->setCellValue($letter.$number, $mtx[$m][$headNames[$v]]);
	        				$sheet->getStyle($letter.$number)->getNumberFormat()->setFormatCode('#,##0.00');
	        			}
	        		}else{

	        			if ($headNames[$v] == "month") {
							$sheet->setCellValue($letter.$number, $months[$mtx[$m][$headNames[$v]]-1][2]);	        				
	        			}elseif ($headNames[$v] == "currency") {
	        				$sheet->setCellValue($letter.$number, $currency);
	        			}else{
	        				$sheet->setCellValue($letter.$number, $mtx[$m][$headNames[$v]]);	
	        			}
	        		}

	        		$sheet->getStyle($letter.$number)->applyFromArray($bodyStyle);	
	        		$letter++;
	        	}
	        	$number++;
	        	$letter = $startLetter;

	        }
		}else{
			$sheet->setCellValue($letter.'3', "Don't have digital values");
			$sheet->getStyle($letter.'3')->getFont()->setSize(20);
		}
		

        return $sheet;
	}


	public function generatePlan($sheet, $head, $mtx, $values, $headStyle, $bodyStyle, $months, $currency){
		
		$startLetter = 'A';
		$letter = $startLetter;

		$sheet->setCellValue($letter.'1', $head);
		$sheet->getStyle($letter.'1')->getFont()->setSize(20);

		if (is_array($mtx)) {
			$headNames = array();

			foreach ($mtx[0] as $key => $val) {
				array_push($headNames, $key);

	            $sheet->setCellValue($letter.'3', $key);
	            $sheet->getStyle($letter.'3')->applyFromArray($headStyle);
	            $letter++;    
	        }

	        $letter = $startLetter;
	        $number = 4;

	        for ($m=0; $m < sizeof($mtx); $m++) {
	        	for ($v=0; $v < sizeof($mtx[$m]); $v++) {
	        		if ($this->inArray($values, $headNames[$v])) {
	    				$sheet->setCellValue($letter.$number, $mtx[$m][$headNames[$v]]);
	    				$sheet->getStyle($letter.$number)->getNumberFormat()->setFormatCode('#,##0.00');
	        		}else{
	        			if ($headNames[$v] == "month") {
							$sheet->setCellValue($letter.$number, $months[$mtx[$m][$headNames[$v]]-1][2]);	        				
	        			}elseif ($headNames[$v] == "currency") {
	        				$sheet->setCellValue($letter.$number, $currency);
	        			}else{
	        				$sheet->setCellValue($letter.$number, $mtx[$m][$headNames[$v]]);	
	        			}
	        		}

	        		$sheet->getStyle($letter.$number)->applyFromArray($bodyStyle);	
	        		$letter++;
	        	}
	        	$number++;
	        	$letter = $startLetter;

	        }	
		}else{
			$sheet->setCellValue($letter.'3', "Don't have plan by brand values");
			$sheet->getStyle($letter.'3')->getFont()->setSize(20);
		}

		return $sheet;
	}

	public function selectData($con, $region, $years, $brands, $form, $currency, $value){

		for ($b=0; $b < sizeof($brands); $b++) { 
			$brand_id[$b] = $brands[$b]['id'];
		}

		array_push($brand_id, '13');
		array_push($brand_id, '14');
		array_push($brand_id, '15');
		array_push($brand_id, '16');

		$sql = new sql();

		if ($form == "TARGET" || $form == "CORPORATE" || $form == "ACTUAL") {

			$cols = array("sales_office_id", "year", "brand_id", "source", "currency_id", "type_of_revenue");
			$colsValue = array($region, $years, $brand_id, $form, '4', $value);

			$where = $sql->where($cols, $colsValue);

			$p = new planByBrand();

			$values = $p->getWithFilter($con, $where, $currency, $region);

		}elseif ($form == "ytd") {
			
			$cols = array("sales_representant_office_id", "year", "brand_id");
			$colsValue = array($region, $years, $brand_id);

			$where = $sql->where($cols, $colsValue);

			$y = new ytd();

			$values = $y->getWithFilter($con, $value, $currency, $region, $where);

		}else{

			$cols = array("year", "brand_id");
			$colsValue = array($years, $brand_id);

			$where = $sql->where($cols, $colsValue);

			$c = new cmaps();

			$values = $c->getWithFilter($con, $value, $region, $currency, $where);
		}

		if ($form != "TARGET" && $form != "CORPORATE" && $form != "ACTUAL") {
			$cols = array("d.region_id", "year", "brand_id");
			$colsValue = array($region, $years, $brand_id);

			$where = $sql->where($cols, $colsValue);

			$d = new digital();

			$valuesDigital = $d->getWithFilter($con, $value, $where, $currency, $region);

		}else{
			$valuesDigital = null;
		}

		return array($values, $valuesDigital);

	}

}
