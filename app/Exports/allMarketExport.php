<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class allMarketExport implements FromView, WithEvents, ShouldAutoSize, WithTitle, WithColumnFormatting {
    
    protected $view;
	protected $data;
    protected $type;

	protected $headStyle = [
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
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => [
                'rgb' => '0070c0',
            ],
        ],
    ];

    protected $lineBodyPair = [
        'font' => [
            'bold' => true,
            'name' => 'Verdana',
            'size' => 10,
            'color' => array('rgb' => '000000')
        ],
        'alignment' => [
            'horizontal' => 'center',
            'vertical' => 'center',
            'wrapText' => true
        ],
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => [
                'rgb' => 'dce6f1',
            ],
        ],
    ];

    protected $lineBodyOdd = [
        'font' => [
            'bold' => true,
            'name' => 'Verdana',
            'size' => 10,
            'color' => array('rgb' => '000000')
        ],
        'alignment' => [
            'horizontal' => 'center',
            'vertical' => 'center',
            'wrapText' => true
        ],
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => [
                'rgb' => 'c3d8ef',
            ],
        ],
    ];

    protected $lastLineBody = [
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
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => [
                'rgb' => '0f243e',
            ],
        ],
    ];

    public function __construct($view, $data, $type){
        $this->view = $view;
        $this->data = $data;
        $this->type = $type;
    }

    public function view(): View{

        $c = 0;
    	return view($this->view, ['data' => $this->data, 'type' => $this->type, 'c' => $c]);
    }

    /**
    * @return array
    */
    public function registerEvents(): array{
        
        return [
            AfterSheet::class => function(AfterSheet $event){
                $cellRange = "A1";
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->headStyle);

                $pos = 2;

                if ($this->data['type'] != "sector") {
                	$cellRange = "A".$pos;
                	$event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->headStyle);

                	$pos++;

                	$cellRange = "A".$pos;
                	$event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->headStyle);

                	$pos++;

                    $val = 4;
                }else{
                	$cellRange = "A".$pos;
                	$event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->headStyle);

                	$pos++;

                    $val = 3;
                }

                if ($this->data['type'] == "agency") {
                	$letter = "H";
                }else{
                	$letter = "G";
                }

                $cellRange = "A".$pos.":".$letter.$pos;
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->headStyle);
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(10);

                if ($this->type == "PDF") {
                    for ($b=0; $b < (sizeof($this->data['mtx'][0])+(intval(sizeof($this->data['mtx'][0])/40))); $b++) { 
                        $cellRange = "A".($b+($pos+1)).":".$letter.($b+($pos+1));
                        if (($b+($pos+1)) % 2 == 0) {
                            $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->lineBodyOdd);
                        }else{
                            $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->lineBodyPair);
                        }
                    }

                    if (sizeof($this->data['mtx'][0]) > 41) {
                        $c = 0;
                        for ($d=0; $d < (sizeof($this->data['mtx'][0])+(intval(sizeof($this->data['mtx'][0])/40))); $d++) { 
                            $c++;
                            if ($c == 42) {
                                $cell = "A".($d+3);
                                $event->sheet->getDelegate()->setBreak($cell, \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::BREAK_ROW);
                                for ($l=ord("A"), $i = 0; $l <= ord($letter); $l++, $i++) {
                                    $cell = chr($l).($d+4);
                                    $event->sheet->getCell($cell)->setValue($this->data['mtx'][$i][0]);
                                    $event->sheet->getDelegate()->getStyle($cell)->applyFromArray($this->headStyle);
                                    $event->sheet->getDelegate()->getStyle($cell)->getFont()->setSize(10);
                                }

                                $c = 1;
                            }
                        }   
                    }

                    if ((sizeof($this->data['mtx'][0])+(intval(sizeof($this->data['mtx'][0])/40))+$val) == 45) {
                        $cellRange = "A".(sizeof($this->data['mtx'][0])+(intval(sizeof($this->data['mtx'][0])/40))+$val-1).":".
                        $letter.(sizeof($this->data['mtx'][0])+(intval(sizeof($this->data['mtx'][0])/40))+$val-1);
                        $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->lastLineBody);   
                    }else{
                        $cellRange = "A".(sizeof($this->data['mtx'][0])+(intval(sizeof($this->data['mtx'][0])/40))+$val).":".
                        $letter.(sizeof($this->data['mtx'][0])+(intval(sizeof($this->data['mtx'][0])/40))+$val);
                        $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->lastLineBody);
                    }
                }else{
                    for ($b=0; $b < sizeof($this->data['mtx'][0]); $b++) { 
                        $cellRange = "A".($b+($pos+1)).":".$letter.($b+($pos+1));
                        if (($b+($pos+1)) % 2 == 0) {
                            $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->lineBodyOdd);
                        }else{
                            $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->lineBodyPair);
                        }
                    }

                    $cellRange = "A".(sizeof($this->data['mtx'][0])+$val).":".$letter.(sizeof($this->data['mtx'][0])+$val);
                    $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->lastLineBody);
                }

                if ($this->type != "Excel") {
                    $event->sheet->getDelegate()->getPageSetup()
                        ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE)
                        ->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A3);
                }
            },
        ];
    }

    public function title(): string{
        return "ranking market";
    }

    public function columnFormats(): array{

    	if ($this->data['type'] == "agency") {

    		return [
	            'D' => '#,##0',
	            'E' => '#,##0',
	            'F' => '0%',
	            'G' => '#,##0'
        	];
    	}else{

    		return [
	            'C' => '#,##0',
	            'D' => '#,##0',
	            'E' => '0%',
	            'F' => '#,##0'
        	];
    	}
    }
}
