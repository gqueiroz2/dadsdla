<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class newExport implements FromView, WithEvents, ShouldAutoSize, WithTitle, WithColumnFormatting, WithStrictNullComparison {
    
    protected $view;
	protected $data;
	protected $dataTotal;	
	protected $dataNew;
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

    public function __construct($view, $data, $dataTotal, $dataNew, $names, $type){
		$this->view = $view;
	    $this->data = $data;
	    $this->dataTotal = $dataTotal;
	    $this->dataNew = $dataNew;
	    $this->names = $names;
        $this->type = $type;
	}

	public function view(): View{

        $c = 0;
    	return view($this->view, ['data' => $this->data, 'dataTotal' => $this->dataTotal, 'dataNew' => $this->dataNew, 'names' => $this->names, 'type' => $this->type, 'c' => $c]);
    }

    /**
    * @return array
    */
    public function registerEvents(): array{
        
        return [
            AfterSheet::class => function(AfterSheet $event){
                $cellRange = "A1";
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->headStyle);

                if ($this->names['type'] == "client") {
                    $letter = "J";
                }else{
                    $letter = "I";
                }

                $cellRange = "A2:".$letter."2";
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->headStyle);
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(10);

                if ($this->type == "PDF") {
                    for ($d=0; $d < sizeof($this->data[0]); $d++) { 
                        $cellRange = "A".($d+3).":".$letter.($d+3);
                        if (($d+3) % 2 == 0) {
                            $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->lineBodyOdd);
                        }else{
                            $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->lineBodyPair);
                        }

                        if ($this->names['type'] != "client") {
                            $cell = $event->sheet->getCell("E".($d+3))->getValue();

                            if (is_numeric($cell)) {
                                $event->sheet->getCell("E".($d+3))->setValue($cell/100);
                            }
                        }else{
                            $cell = $event->sheet->getCell("E".($d+3))->getValue();

                            if (is_numeric($cell)) {
                                $event->sheet->getCell("E".($d+3))->setValue($cell/100);
                            }
                        }
                    }

                    if (sizeof($this->data[0]) > 41) {
                        $c = 0;
                        for ($d=0; $d < (sizeof($this->data[0])+(intval(sizeof($this->data[0])/40))); $d++) {
                            
                            $c++;
                            if ($c == 40) {
                                $cell = "A".($d+3);
                                $event->sheet->getDelegate()->setBreak($cell, \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::BREAK_ROW);
                                
                                for ($l=ord("A"), $i = 0; $l <= ord($letter); $l++, $i++) {
                                    $cell = chr($l).($d+4);
                                    $event->sheet->getCell($cell)->setValue($this->data[$i][0]);
                                    $event->sheet->getDelegate()->getStyle($cell)->applyFromArray($this->headStyle);
                                    $event->sheet->getDelegate()->getStyle($cell)->getFont()->setSize(10);
                                }

                                $c = 1;
                            }
                        }
                    }

                    if ((sizeof($this->data[0])+(intval(sizeof($this->data[0])/40))+1) == 42) {
                        $cellRange = "A".(sizeof($this->data[0])+(intval(sizeof($this->data[0])/40))+1).":".$letter.(sizeof($this->data[0])+(intval(sizeof($this->data[0])/40))+1);
                        $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->lastLineBody);
                    }else{
                        $cellRange = "A".(sizeof($this->data[0])+(intval(sizeof($this->data[0])/40))+2).":".$letter.(sizeof($this->data[0])+(intval(sizeof($this->data[0])/40))+2);
                        $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->lastLineBody);
                    }

                }else{
                    for ($d=0; $d < sizeof($this->data[0]); $d++) { 
                        $cellRange = "A".($d+3).":".$letter.($d+3);
                        if (($d+3) % 2 == 0) {
                            $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->lineBodyOdd);
                        }else{
                            $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->lineBodyPair);
                        }

                        if ($this->names['type'] != "client") {
                            $cell = $event->sheet->getCell("E".($d+3))->getValue();

                            if (is_numeric($cell)) {
                                $event->sheet->getCell("E".($d+3))->setValue($cell/100);
                            }
                        }else{
                            $cell = $event->sheet->getCell("E".($d+3))->getValue();

                            if (is_numeric($cell)) {
                                $event->sheet->getCell("E".($d+3))->setValue($cell/100);
                            }
                        }
                    }

                    $cellRange = "A".(sizeof($this->data[0])+2).":".$letter.(sizeof($this->data[0])+2);
                    $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->lastLineBody);
                }

                if ($this->type != "Excel") {
                    $event->sheet->getDelegate()->getPageSetup()
                        ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE)
                        ->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
                }
            },
        ];
    }

    public function title(): string{

    	$a = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûüýýþÿŔŕ?';
        $b = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuuyybyRr-';
        $nome = strtr($this->dataNew[1], utf8_decode($a), $b);
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
                'D' => '#,##0',
                'E' => '#,##0',
                'F' => '0%',
                'G' => '#,##0',
                'H' => '#,##0',
                'I' => '#,##0'
            ];
        }else{
            return [
                'C' => '#,##0',
                'D' => '#,##0',
                'E' => '0%',
                'F' => '#,##0',
                'G' => '#,##0',
                'H' => '#,##0'
            ];
        }
    }
}
