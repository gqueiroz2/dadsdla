<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class performanceExecutiveCase2Export implements FromView, WithEvents, ShouldAutoSize, WithTitle {
    
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

    protected $dc = [
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => [
                'rgb' => '0070c0',
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
                'rgb' => 'ff6600',
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
                'rgb' => 'ffff00',
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
                'rgb' => '009933',
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
                'rgb' => 'ff0000',
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
                'rgb' => '000000',
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
                'rgb' => '002060',
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
                'rgb' => 'ff0000',
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
                'rgb' => '6600ff',
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
                'rgb' => '004b84',
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
                'rgb' => '808080',
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
                'rgb' => '88cc00',
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
                'rgb' => '0f243e',
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

    public function __construct($view, $data, $type){
        $this->view = $view;
        $this->data = $data;
        $this->type = $type;
    }

    public function view(): View{
    	$size = 64/sizeof($this->data['mtx']['quarters']);

    	return view($this->view, ['data' => $this->data, 'size' => $size]);
    }

    public function title(): string{
        return "Brand and Quarter";
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
               	$end = chr(ord($ini) + sizeof($this->data['mtx']['case2']['dnValue'][0])+1);

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
                    $b = 0;
                	for ($t=0; $t < $sizeBrand; $t++) {
                		$cellRange = "A".($number+2+$c).":A".($number+6+$c);
                		$event->sheet->getDelegate()->mergeCells($cellRange);

                        if ($this->type != "Excel") {
                            $cellRange = "A".($number+7+$c).":".$end.($number+7+$c);
                            $event->sheet->getDelegate()->mergeCells($cellRange);                            
                        }

                		$cellRange = "A".($number+2+$c);
	                	$cell = $event->sheet->getCell($cellRange)->getValue();
	                	$event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->brandName($this->data['mtx']['brand'][$t][1]));

	                	for ($l=0; $l < 5; $l++) {
	                		$cellRange = $ini.($number+2+$l+$c).":".$end.($number+2+$l+$c);
	                		$event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->bodyCenter);

	                		if ($l == 1 || $l == 2 || $l == 3) {
	                			$event->sheet->getStyle($cellRange)->getNumberFormat()->applyFromArray(array('formatCode' => "#,##0"));	
	                		}else{
	                			$event->sheet->getStyle($cellRange)->getNumberFormat()->applyFromArray(array('formatCode' => "0%"));	
	                		}
	                	}

                        if ($this->type != "Excel") {
                            $b++;

                            if ($b == 7) {
                                $cell = "A".($number+6+$c);
                                $event->sheet->getDelegate()->setBreak($cell, \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::BREAK_ROW);

                                $cellRange = "A".($number+7+$c).":".$end.($number+7+$c);
                                $event->sheet->getDelegate()->mergeCells($cellRange);

                                $cell = "A".($number+7+$c);
                                $event->sheet->getCell($cell)->setValue($this->data['mtx']['salesRep'][$s]['salesRep']);
                                $event->sheet->getDelegate()->getStyle($cell)->applyFromArray($this->nameStyle);
                                $b = 0;
                            }
                        }

                		$c += 6;
                	}

                	$number += (($sizeBrand*5)+3+($sizeBrand-1));
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
