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

class brandExport implements FromView, WithEvents, ShouldAutoSize, WithTitle, WithColumnFormatting, WithStrictNullComparison {
    
	protected $view;
	protected $data;
	protected $dataTotal;
	protected $dataType;
	protected $dataBrand;
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

    public function __construct($view, $data, $dataTotal, $dataType, $dataBrand, $names, $type){
		$this->view = $view;
	    $this->data = $data;
	    $this->dataTotal = $dataTotal;
	    $this->dataType = $dataType;
	    $this->dataBrand = $dataBrand;
	    $this->names = $names;
        $this->type = $type;
	}

	public function view(): View{
		if ($this->dataType == "agency") {
			$sizeCols = 8;
			$pos = 5;
		}else{
			$sizeCols = 7;
			$pos = 4;
		}

        $c = 0;

    	return view($this->view, ['data' => $this->data, 'dataTotal' => $this->dataTotal, 'dataType' => $this->dataType, 'dataBrand' => $this->dataBrand, 'names' => $this->names, 'sizeCols' => $sizeCols, 'pos' => $pos, 'type' => $this->type, 'c' => $c]);
    }

    /**
    * @return array
    */
    public function registerEvents(): array{
        
        return [
            AfterSheet::class => function(AfterSheet $event){
                $cellRange = "A1";
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->headStyle);
                
                if ($this->dataType == "agency") {
                	$letter = "H";
                }else{
                	$letter = "G";
                }

                $cellRange = "A2:".$letter."2";
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->headStyle);
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(10);

                if ($this->type == "PDF") {

                    for ($d=0; $d < (sizeof($this->data[0])+(intval(sizeof($this->data[0])/40))); $d++) {
                        $cellRange = "A".($d+3).":".$letter.($d+3);
                        if (($d+3) % 2 == 0) {
                            $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->lineBodyOdd);
                        }else{
                            $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->lineBodyPair);
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
                                $c = -2;
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
        return "ranking brand - ".$this->dataBrand;
    }

    public function columnFormats(): array{
        
        if ($this->dataType == "agency") {
            return [
                'C' => '#,##0',
                'D' => '#,##0',
                'E' => '#,##0',
                'F' => '0%'
            ];
        }else{
            return [
                'B' => '#,##0',
                'C' => '#,##0',
                'D' => '#,##0',
                'E' => '0%'
            ];
        }
    }
}
