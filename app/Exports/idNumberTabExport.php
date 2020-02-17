<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;

class idNumberTabExport implements FromView, ShouldAutoSize, WithTitle, WithEvents{

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
                'rgb' => '0f243e',
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
                'rgb' => 'f9fbfd',
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

	public function __construct($view,$data){
		$this->view = $view;
		$this->data = $data;
	}

	public function view(): View{
		return view($this->view, ['data'=> $this->data]);
	}

	public function registerEvents(): array{

		return [
			AfterSheet::class => function(AfterSheet $event){
				$letter = 'B';

				for ($n=0; $n <sizeof($this->data['names']); $n++) { 
					$cellRange = "A".($n+1).":".$letter.($n+1);
					$event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->headStyle);	
				}

				$cellRange = "A2:".$letter."2";
				$event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->headStyle);

				for ($i=0; $i < sizeof($this->data['idNumber']); $i++) {
				 	for ($j=0; $j < sizeof($this->data['idNumber'][$i])+3; $j++) {
	                    $cellRange = "A".($j+3).":".$letter.($j+3);
	               	    if (($j+3) % 2 == 0) {
	                       $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->lineBodyPair);
	                    }else{
	                       $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->lineBodyOdd);
	                    }
	                }
                }
			},

		];
	}

	public function title(): string{
        return "ID Number";
    }

}
