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
                'argb' => '0070c0',
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
                'argb' => '0070c0',
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
                'argb' => 'dce6f1',
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
                'argb' => 'c3d8ef',
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
                'argb' => '0f243e',
            ],
        ],
    ];

    public function __construct($view, $data){
		$this->view = $view;
	    $this->data = $data;
	}

    public function view(): View{
    	return view($this->view, ['data' => $this->data]);
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
	            'J' => '#0%'
        	];
    	}else{
    		return [
	            'E' => '#,##0',
	            'F' => '#,##0',
	            'G' => '#,##0',
	            'H' => '#,##0',
	            'I' => '#0%'
        	];
    	}
    }
}
