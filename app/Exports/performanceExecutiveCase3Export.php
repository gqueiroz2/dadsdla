<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class performanceExecutiveCase3Export implements FromView, WithEvents, ShouldAutoSize, WithTitle {
    
    protected $view;
	protected $data;
    protected $type;

	protected $headStyle = [
		'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => [
                'rgb' => '0070c0',
            ],
        ],
	    'font' => [
	        'bold' => true,
	        'name' => 'Verdana',
	        'size' => 12,
	        'color' => array('rgb' => 'FFFFFF')
	    ],
	    'alignment' => [
	        'horizontal' => 'center',
	        'vertical' => 'center',
	        'wrapText' => true
	    ],
    ];

    protected $nameStyle = [
		'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => [
                'rgb' => '0f243e',
            ],
        ],
	    'font' => [
	        'bold' => true,
	        'name' => 'Verdana',
	        'size' => 10,
	        'color' => array('rgb' => 'FFFFFF')
	    ],
	    'alignment' => [
	        'horizontal' => 'center',
	        'vertical' => 'center',
	        'wrapText' => true
	    ],
    ];

    protected $bodyCenter = [
        'font' => [
            'name' => 'Verdana',
            'size' => 10,
        ],
        'alignment' => [
            'horizontal' => 'center',
            'vertical' => 'center',
            'wrapText' => true
        ],
    ];

    protected $t1 = [
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => [
                'rgb' => '0070c0',
            ],
        ],
        'font' => [
        	'bold' => true,
            'name' => 'Verdana',
            'size' => 10,
            'color' => array('rgb' => 'FFFFFF')
        ],
        'alignment' => [
            'horizontal' => 'center',
            'vertical' => 'center',
            'wrapText' => true
        ],
    ];

    protected $t2 = [
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => [
                'rgb' => '1E90FF',
            ],
        ],
        'font' => [
        	'bold' => true,
            'name' => 'Verdana',
            'size' => 10,
            'color' => array('rgb' => 'FFFFFF')
        ],
        'alignment' => [
            'horizontal' => 'center',
            'vertical' => 'center',
            'wrapText' => true
        ],
    ];

    protected $oth = [
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => [
                'rgb' => '004b84',
            ],
        ],
        'font' => [
        	'bold' => true,
            'name' => 'Verdana',
            'size' => 10,
            'color' => array('rgb' => 'FFFFFF')
        ],
        'alignment' => [
            'horizontal' => 'center',
            'vertical' => 'center',
            'wrapText' => true
        ],
    ];

    protected $tt = [
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => [
                'rgb' => '0f243e',
            ],
        ],
        'font' => [
        	'bold' => true,
            'name' => 'Verdana',
            'size' => 10,
            'color' => array('rgb' => 'FFFFFF')
        ],
        'alignment' => [
            'horizontal' => 'center',
            'vertical' => 'center',
            'wrapText' => true
        ],
    ];

    public function __construct($view, $data, $type){
        $this->view = $view;
        $this->data = $data;
        $this->type = $type;
    }

    public function view(): View{
    	$size = 84/sizeof($this->data['mtx']['month']);

    	return view($this->view, ['data' => $this->data, 'size' => $size]);
    }

    public function title(): string{
        return "Tier and Month";
    }

    public function tierName($name){
        
        if ($name == "T1") {
            return $this->t1;
        }elseif ($name == "T2") {
            return $this->t2;
        }elseif ($name == "TOTH") {
            return $this->oth;
        }elseif ($name == "TT") {
            return $this->tt;
        }
    }

    /**
    * @return array
    */
    public function registerEvents(): array{


    	return [
    		AfterSheet::class => function(AfterSheet $event){
                $cellRange = "A1";
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->headStyle);

                $number = 3;

                array_push($this->data['mtx']['tier'], "TT");
                $sizeTier = sizeof($this->data['mtx']['tier']);

                $ini = "A";
               	$end = chr(ord($ini) + (sizeof($this->data['mtx']['month'])+2));

                for ($s=0; $s < sizeof($this->data['mtx']['salesRep']); $s++) {
                	$cellRange = "A".$number;

                	$event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->nameStyle);

                    if ($this->type != "Excel") {

                        if ($s > 0) {
                            $cell = "A".($number-2);   
                            $event->sheet->getDelegate()->setBreak($cell, \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::BREAK_ROW);

                            $cell = "A".($number-1);
                            $event->sheet->getCell($cell)->setValue($this->data['mtx']['region']." - Executive ".$this->data['mtx']['year']." (".$this->data['mtx']['currency']."/".$this->data['mtx']['valueView'].") - BKGS");                            

                            $event->sheet->getDelegate()->getStyle($cell)->applyFromArray($this->headStyle);
                        }

                        $cellRange = "A".($number-1).":".$end.($number-1);
                        $event->sheet->getDelegate()->mergeCells($cellRange);

                        $cellRange = "A".($number+1).":".$end.($number+1);
                        $event->sheet->getDelegate()->mergeCells($cellRange);
                    }

                	$c = 0;
                	for ($t=0; $t < $sizeTier; $t++) { 
                		$cellRange = "A".($number+2+$c).":A".($number+6+$c);
                		$event->sheet->getDelegate()->mergeCells($cellRange);

                        if ($this->type != "Excel") {
                            $cellMerge = "A".($number+7+$c).":".$end.($number+7+$c);
                            $event->sheet->getDelegate()->mergeCells($cellMerge);                            
                        }

                		$cellRange = "A".($number+2+$c);
	                	$cell = $event->sheet->getCell($cellRange)->getValue();
	                	$event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->tierName($this->data['mtx']['tier'][$t]));

	                	for ($l=0; $l < 5; $l++) {
	                		$cellRange = $ini.($number+2+$l+$c).":".$end.($number+2+$l+$c);
	                		$event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->bodyCenter);

	                		if ($l == 1 || $l == 2 || $l == 3) {
	                			$event->sheet->getStyle($cellRange)->getNumberFormat()->applyFromArray(array('formatCode' => "#,##0"));	
	                		}else{
	                			$event->sheet->getStyle($cellRange)->getNumberFormat()->applyFromArray(array('formatCode' => "0%"));	
	                		}
	                	}

                		$c += 6;
                	}

                	$number += (($sizeTier*5)+3+($sizeTier-1));
                }

                if ($this->type != "Excel") {
                    $event->sheet->getDelegate()->getPageSetup()
                        ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE)
                        ->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A3);
                }
            }
    	];
    }
}
