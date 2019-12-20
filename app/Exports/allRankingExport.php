<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class allRankingExport implements FromView, WithEvents, ShouldAutoSize, WithTitle, WithColumnFormatting {
    
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

    protected $head2Style = [
        'font' => [
            'italic' => true,
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

                $cellRange = "A2";
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->head2Style);

                $cellRange = "A3";
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->head2Style);

                if ($this->data['type'] == "agency") {
                	$letter = "J";
                }else{
                	$letter = "I";
                }

                $cellRange = "A5:".$letter."5";
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->headStyle);
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(10);

                if ($this->type == "PDF") {
                    for ($b=0; $b < ($this->data['nPos']+intval($this->data['nPos']/40)); $b++) { 
                        $cellRange = "A".($b+(5+1)).":".$letter.($b+(5+1));
                        if (($b+(5+1)) % 2 == 0) {
                            $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->lineBodyOdd);
                        }else{
                            $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->lineBodyPair);
                        }
                    
                        if ($this->data['type'] == "agency") {
                            $cell = $event->sheet->getCell("J".($b+5))->getValue();

                            if (is_numeric($cell)) {
                                $event->sheet->getCell("J".($b+5))->setValue($cell/100);
                            }
                        }else{
                            $cell = $event->sheet->getCell("I".($b+5))->getValue();

                            if (is_numeric($cell)) {
                                $event->sheet->getCell("I".($b+5))->setValue($cell/100);
                            }
                        }
                    }

                    if ($this->data['type'] == "agency") {
                        $cell = $event->sheet->getCell("J".($this->data['nPos'][0]+5))->getValue();

                        if (is_numeric($cell)) {
                            $event->sheet->getCell("J".($this->data['nPos'][0]+5))->setValue($cell/100);
                        }
                    }else{
                        $cell = $event->sheet->getCell("I".($this->data['nPos'][0]+5))->getValue();

                        if (is_numeric($cell)) {
                            $event->sheet->getCell("I".($this->data['nPos'][0]+5))->setValue($cell/100);
                        }
                    }

                    if ($this->data['nPos'] > 41) {
                        $c = 0;
                        for ($d=0; $d < ($this->data['nPos']+intval($this->data['nPos']/40)); $d++) { 
                            $c++;
                            if ($c == 40) {
                                $cell = "A".($d+6);
                                $event->sheet->getDelegate()->setBreak($cell, \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::BREAK_ROW);
                                for ($l=ord("A"), $i = 0; $l <= ord($letter); $l++, $i++) {
                                    $cell = chr($l).($d+7);
                                    $event->sheet->getCell($cell)->setValue($this->data['mtx'][$i][0]);
                                    $event->sheet->getDelegate()->getStyle($cell)->applyFromArray($this->headStyle);
                                    $event->sheet->getDelegate()->getStyle($cell)->getFont()->setSize(10);
                                }

                                $c = -1;
                            }
                        }   
                    }
                    
                    if (($this->data['nPos']+intval($this->data['nPos']/40)) == 42) {
                        $cellRange = "A".($this->data['nPos']+intval($this->data['nPos']/40)+4).":".
                        $letter.($this->data['nPos']+intval($this->data['nPos']/40)+4);
                        $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->lastLineBody);
                    }else{
                        $cellRange = "A".($this->data['nPos']+intval($this->data['nPos']/40)+5).":".
                        $letter.($this->data['nPos']+intval($this->data['nPos']/40)+5);
                        $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->lastLineBody);
                    }
                }else{
                    for ($b=0; $b < sizeof($this->data['mtx'][0]); $b++) { 
                        $cellRange = "A".($b+(5+1)).":".$letter.($b+(5+1));
                        if (($b+(5+1)) % 2 == 0) {
                            $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->lineBodyOdd);
                        }else{
                            $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->lineBodyPair);
                        }

                        if ($this->data['type'] == "agency") {
                            $cell = $event->sheet->getCell("J".($b+5))->getValue();

                            if (is_numeric($cell)) {
                                $event->sheet->getCell("J".($b+5))->setValue($cell/100);
                            }
                        }else{
                            $cell = $event->sheet->getCell("I".($b+5))->getValue();

                            if (is_numeric($cell)) {
                                $event->sheet->getCell("I".($b+5))->setValue($cell/100);
                            }
                        }
                    }

                    if ($this->data['type'] == "agency") {
                        $cell = $event->sheet->getCell("J".(sizeof($this->data['mtx'][0])+5))->getValue();

                        if (is_numeric($cell)) {
                            $event->sheet->getCell("J".(sizeof($this->data['mtx'][0])+5))->setValue($cell/100);
                        }
                    }else{
                        $cell = $event->sheet->getCell("I".(sizeof($this->data['mtx'][0])+5))->getValue();

                        if (is_numeric($cell)) {
                            $event->sheet->getCell("I".(sizeof($this->data['mtx'][0])+5))->setValue($cell/100);
                        }
                    }

                    $cellRange = "A".(sizeof($this->data['mtx'][0])+5).":".$letter.(sizeof($this->data['mtx'][0])+5);
                    $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->lastLineBody);
                }

                if ($this->type != "Excel") {

                    if ($this->data['type'] == "agency") {
                        $cellRange = "A4:J4";
                    }else{
                        $cellRange = "A4:I4";
                    }

                    $event->sheet->getDelegate()->mergeCells($cellRange);

                    $event->sheet->getDelegate()->getPageSetup()
                        ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE)
                        ->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A3);
                }
            },
        ];
    }

    public function title(): string{
        return "ranking";
    }

    public function columnFormats(): array{

    	if ($this->data['type'] == "agency") {

    		return [
	            'F' => '#,##0',
	            'G' => '#,##0',
	            'H' => '#,##0',
	            'I' => '#,##0',
	            'J' => '0%'
        	];
    	}else{
    		return [
	            'E' => '#,##0',
	            'F' => '#,##0',
	            'G' => '#,##0',
	            'H' => '#,##0',
	            'I' => '0%'
        	];
    	}
    }
}
