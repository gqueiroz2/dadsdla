<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class allBrandsExport implements FromView, WithEvents, ShouldAutoSize, WithTitle {
    
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

    protected $bodyHeadStyle = [
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

                $cellRange = "A2:I2";
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->bodyHeadStyle);

                for ($b=0; $b < sizeof($this->data['brand']); $b++) { 
                	$cellRange = "A".($b+2).":I".($b+2);
                	if (($b+2) % 2 == 0) {
                		$event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->lineBodyOdd);
                	}else{
                		$event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->lineBodyPair);
                	}
                }

                $cellRange = "A".(sizeof($this->data['brand'])+2).":I".(sizeof($this->data['brand'])+2);
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->lastLineBody);
            },
        ];
    }

    public function title(): string{
        return "ranking brand";
    }
}
