<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class performanceQuarterTabExport implements FromView, WithEvents, ShouldAutoSize, WithTitle {

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

    protected $t1 = [
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => [
                'argb' => '0070c0',
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
                'argb' => '1E90FF',
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

    protected $toth = [
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => [
                'argb' => '004b84',
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
                'argb' => '000080',
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
    	$c = 0;
    	return view($this->view, ['data' => $this->data, 'c' => $c]);
    }

    public function title(): string{
        return "Office";
    }

    public function tierName($name){
        
        if ($name == "T1") {
            return $this->t1;
        }elseif ($name == "T2") {
            return $this->t2;
        }elseif ($name == "TOTH") {
            return $this->toth;
        }elseif ($name == "TT") {
            return $this->tt;
        }
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

				$cellRange = "A2";
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->headStyle);                
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(10);

                $numberTier = 4;
                $numberBrand = 6;

                $sizeTier = sizeof($this->data['tiers']);

                if ($this->data['tiers'][sizeof($this->data['tiers'])-1] == "TT") {
                	array_push($this->data['auxTiers'], array("DN"));
                }

                for ($t=0; $t < sizeof($this->data['tiers']); $t++) {
                	$cellRange = "A".$numberTier;

                	$event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->tierName($this->data['tiers'][$t]));

                	for ($b=0; $b < sizeof($this->data['auxTiers'][$t]); $b++) {
                		$cellRange = "A".$numberBrand;

						$event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->brandName($this->data['auxTiers'][$t][$b]));

						$cellRange = "A".$numberBrand.":A".($numberBrand+4);
                		$event->sheet->getDelegate()->mergeCells($cellRange);

                		for ($l=0; $l <= 4; $l++) {
                			$cellRange = "B".($numberBrand+$l).":G".($numberBrand+$l);
                			$event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->bodyCenter);
                			if ($l == 4) {
                				$event->sheet->getStyle($cellRange)->getNumberFormat()->applyFromArray(array('formatCode' => "#0%"));
                			}else{
                				$event->sheet->getStyle($cellRange)->getNumberFormat()->applyFromArray(array('formatCode' => "#,##0"));
                			}
                		}

						$numberBrand += 6;

						if ($b == (sizeof($this->data['auxTiers'][$t]))-1) {
		                    $numberBrand += 2;
		                }
                	}

                	$numberTier += ((sizeof($this->data['auxTiers'][$t])*6)+2);
                }
            }
    	];
    }
}
