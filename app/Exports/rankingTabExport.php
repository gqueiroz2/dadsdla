<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class rankingTabExport implements FromView, WithEvents, ShouldAutoSize, WithTitle, WithColumnFormatting {
    
    protected $view;
	protected $data;
	protected $dataTotal;	
	protected $dataRanking;
	protected $names;
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

    public function __construct($view, $data, $dataTotal, $dataRanking, $names, $type){
		$this->view = $view;
	    $this->data = $data;
	    $this->dataTotal = $dataTotal;
	    $this->dataRanking = $dataRanking;
	    $this->names = $names;
        $this->type = $type;
	}

	public function view(): View{

        $c = 0;
    	return view($this->view, ['data' => $this->data, 'dataTotal' => $this->dataTotal, 'dataRanking' => $this->dataRanking, 'names' => $this->names, 'type' => $this->type, 'c' => $c]);
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

                if ($this->names['type'] == "client") {
                    $letter = "J";
                }else{
                    $letter = "I";
                }

                $cellRange = "A5:".$letter."5";
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->headStyle);
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(10);

                if ($this->type == "PDF") {
                    if (sizeof($this->data[0]) > 41) {
                        $val = 4;
                    }else{
                        $val = 0;
                    }

                    for ($d=0; $d < (sizeof($this->data[0])+$val); $d++) { 
                        $cellRange = "A".($d+6).":".$letter.($d+6);
                        if (($d+6) % 2 == 0) {
                            $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->lineBodyOdd);
                        }else{
                            $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->lineBodyPair);
                        }

                        if ($this->names['type'] == "client") {
                            $cell = $event->sheet->getCell("J".($d+6))->getValue();

                            if (is_numeric($cell)) {
                                $event->sheet->getCell("J".($d+6))->setValue($cell/100);
                            }
                        }else{
                            $cell = $event->sheet->getCell("I".($d+6))->getValue();

                            if (is_numeric($cell)) {
                                $event->sheet->getCell("I".($d+6))->setValue($cell/100);
                            }
                        }
                    }

                    if (sizeof($this->data[0]) > 41) {
                        $c = 0;
                        for ($d=0; $d < (sizeof($this->data[0])+(intval(sizeof($this->data[0])/40))); $d++) {
                            
                            $c++;
                            if ($c == 35) {
                                $cell = "A".($d+6);
                                $event->sheet->getDelegate()->setBreak($cell, \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::BREAK_ROW);
                                
                                for ($l=ord("A"), $i = 0; $l <= ord($letter); $l++, $i++) {
                                    $cell = chr($l).($d+7);
                                    $event->sheet->getCell($cell)->setValue($this->data[$i][0]);
                                    $event->sheet->getDelegate()->getStyle($cell)->applyFromArray($this->headStyle);
                                    $event->sheet->getDelegate()->getStyle($cell)->getFont()->setSize(10);
                                }

                                $c = -1;
                            }
                        }
                    }

                    if ((sizeof($this->data[0])+(intval(sizeof($this->data[0])/40))) == 42) {
                        $cellRange = "A".(sizeof($this->data[0])+(intval(sizeof($this->data[0])/40))+4).":".$letter.(sizeof($this->data[0])+(intval(sizeof($this->data[0])/40))+4);
                        $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->lastLineBody);
                    }else{
                        $cellRange = "A".(sizeof($this->data[0])+(intval(sizeof($this->data[0])/40))+5).":".$letter.(sizeof($this->data[0])+(intval(sizeof($this->data[0])/40))+5);
                        $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->lastLineBody);
                    }
                }else{
                    for ($d=0; $d < (sizeof($this->data[0])); $d++) { 
                        $cellRange = "A".($d+6).":".$letter.($d+6);
                        if (($d+6) % 2 == 0) {
                            $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->lineBodyOdd);
                        }else{
                            $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->lineBodyPair);
                        }

                        if ($this->names['type'] == "client") {
                            $cell = $event->sheet->getCell("J".($d+6))->getValue();

                            if (is_numeric($cell)) {
                                $event->sheet->getCell("J".($d+6))->setValue($cell/100);
                            }
                        }else{
                            $cell = $event->sheet->getCell("I".($d+6))->getValue();

                            if (is_numeric($cell)) {
                                $event->sheet->getCell("I".($d+6))->setValue($cell/100);
                            }
                        }
                    }

                    $cellRange = "A".(sizeof($this->data[0])+5).":".$letter.(sizeof($this->data[0])+5);
                    $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->lastLineBody);
                }
                if ($this->type != "Excel") {

                    if ($this->names['type'] == "client") {
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

    	$a = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûüýýþÿŔŕ?';
        $b = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuuyybyRr-';
        $nome = strtr($this->dataRanking[1], utf8_decode($a), $b);
        $nome = preg_replace("/[^0-9a-zA-Z\.\s+]+/",'',$nome);

   		if(strlen($nome) > 30){
   			$i = strpos($nome, " ");

			$nome = substr($nome, 0, $i);
   		}

   		return $nome;   
    }

    public function columnFormats(): array{

        if ($this->names['type'] == "client") {
            
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
