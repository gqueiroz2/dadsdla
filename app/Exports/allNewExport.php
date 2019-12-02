<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class allNewExport implements FromView, WithEvents, ShouldAutoSize, WithTitle, WithColumnFormatting {
    
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
                	$letter = "J";
                }else{
                	$letter = "I";
                }

                $cellRange = "A".$pos.":".$letter.$pos;
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->headStyle);
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(10);

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
            },
        ];
    }

    public function title(): string{
        return "ranking new";
    }

    public function columnFormats(): array{

    	if ($this->data['type'] == "agency") {

    		return [
	            'D' => '#,##0',
	            'E' => '#,##0',
	            'F' => '#0%',
	            'G' => '#,##0',
	            'H' => '#,##0',
	            'I' => '#,##0'
        	];
    	}else{

    		return [
	            'C' => '#,##0',
	            'D' => '#,##0',
	            'E' => '#0%',
	            'F' => '#,##0',
	            'G' => '#,##0',
	            'H' => '#,##0'
        	];
    	}
    }
}
