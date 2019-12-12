<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class performanceExecutiveCase4Export implements FromView, WithEvents, ShouldAutoSize, WithTitle {
    
    protected $view;
	protected $data;

	protected $headStyle = [
		'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => [
                'argb' => '0070c0',
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
                'argb' => '0f243e',
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

    protected $dc = [
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => [
                'argb' => '0070c0',
            ],
        ],
        'font' => [
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

    protected $hh = [
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => [
                'argb' => 'ff6600',
            ],
        ],
        'font' => [
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

    protected $dk = [
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => [
                'argb' => 'ffff00',
            ],
        ],
        'font' => [
            'name' => 'Verdana',
            'size' => 12
        ],
        'alignment' => [
            'horizontal' => 'center',
            'vertical' => 'center',
            'wrapText' => true
        ],
    ];

    protected $ap = [
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => [
                'argb' => '009933',
            ],
        ],
        'font' => [
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

    protected $tlc = [
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => [
                'argb' => 'ff0000',
            ],
        ],
        'font' => [
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

    protected $id = [
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => [
                'argb' => '000000',
            ],
        ],
        'font' => [
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

    protected $dt = [
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => [
                'argb' => '002060',
            ],
        ],
        'font' => [
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

    protected $fn = [
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => [
                'argb' => 'ff0000',
            ],
        ],
        'font' => [
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

    protected $onl = [
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => [
                'argb' => '6600ff',
            ],
        ],
        'font' => [
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

    protected $vix = [
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => [
                'argb' => '004b84',
            ],
        ],
        'font' => [
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

    protected $oth = [
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => [
                'argb' => '808080',
            ],
        ],
        'font' => [
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

    protected $hgtv = [
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => [
                'argb' => '88cc00',
            ],
        ],
        'font' => [
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

    protected $dn = [
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => [
                'argb' => '0f243e',
            ],
        ],
        'font' => [
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

    public function __construct($view,$data){
    	$this->view = $view;
    	$this->data = $data;
    }

    public function view(): View{
    	$size = 84/sizeof($this->data['mtx']['month']);

    	return view($this->view, ['data' => $this->data, 'size' => $size]);
    }

    public function title(): string{
        return "Brand and Month";
    }

    public function brandName($name){
        
        if ($name == "DC") {
            return $this->dc;
        }elseif ($name == "HH") {
            return $this->hh;
        }elseif ($name == "DK") {
            return $this->dk;
        }elseif ($name == "AP") {
            return $this->ap;
        }elseif ($name == "TLC") {
            return $this->tlc;
        }elseif ($name == "ID") {
            return $this->id;
        }elseif ($name == "DT") {
            return $this->dt;
        }elseif ($name == "FN") {
            return $this->fn;
        }elseif ($name == "ONL") {
            return $this->onl;
        }elseif ($name == "VIX") {
            return $this->vix;
        }elseif ($name == "OTH") {
            return $this->oth;
        }elseif ($name == "HGTV") {
            return $this->hgtv;
        }elseif ($name == "DN") {
            return $this->dn;
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

            	array_push($this->data['mtx']['brand'], array(13, "DN"));
                $sizeBrand = sizeof($this->data['mtx']['brand']);

                $ini = "B";
               	$end = chr(ord($ini) + sizeof($this->data['mtx']['month'])+3);

                for ($s=0; $s < sizeof($this->data['mtx']['salesRep']); $s++) {
                	$cellRange = "A".$number;

                	$event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->nameStyle);

                	$c = 0;
                	for ($t=0; $t < $sizeBrand; $t++) { 
                		$cellRange = "A".($number+2+$c).":A".($number+6+$c);
                		$event->sheet->getDelegate()->mergeCells($cellRange);

                		$cellRange = "A".($number+2+$c);
	                	$cell = $event->sheet->getCell($cellRange)->getValue();
	                	$event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->brandName($this->data['mtx']['brand'][$t][1]));

	                	for ($l=0; $l < 5; $l++) {
	                		$cellRange = $ini.($number+2+$l+$c).":".$end.($number+2+$l+$c);
	                		$event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->bodyCenter);

	                		if ($l == 1 || $l == 2 || $l == 3) {
	                			$event->sheet->getStyle($cellRange)->getNumberFormat()->applyFromArray(array('formatCode' => "#,##0"));	
	                		}else{
	                			$event->sheet->getStyle($cellRange)->getNumberFormat()->applyFromArray(array('formatCode' => "#0%"));	
	                		}
	                	}

                		$c += 6;
                	}

                	$number += (($sizeBrand*5)+3+($sizeBrand-1));
                }
            }
    	];
    }
}
