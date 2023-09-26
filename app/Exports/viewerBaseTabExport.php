<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class viewerBaseTabExport implements FromView,WithEvents, ShouldAutoSize, WithTitle, WithColumnFormatting {

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
    ];

    protected $totalStyle = [
        'font' => [
            'bold' => true,
            'name' => 'Verdana',
            'size' => 12,
            'color' => array('rgb' => '000000')
        ],
        'alignment' => [
            'horizontal' => 'center',
            'vertical' => 'center',
            'wrapText' => true
        ],
    ];

    protected $bodyCenter = [
        'font' => [
        	'bold' => true,
            'name' => 'Verdana',
            'size' => 8,
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
		$c = 0;
		return view($this->view, ['data' => $this->data, 'type' => $this->type, 'c' => $c]);
	}

	public function title(): string{
		return 'Viewer - Base';
	}

	public function registerEvents(): array{

		return [
			AfterSheet::class => function(AfterSheet $event){
				$cellRange = "A1:P1";
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->headStyle);

                $cellRange = "A2:P2";
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->headStyle);
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(8);

                $cellRange = "A3:P3";
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->totalStyle);
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(8);
                
                $letter = "P";

                for ($d=0; $d < sizeof($this->data); $d++) { 
                	$cellRange = "A".($d+4).":".$letter.($d+4);
                    $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->bodyCenter);
                }
				$event->sheet->setShowGridlines(false);
			},
		];
	}

	public function columnFormats(): array{

		if ($this->data['source'] == 'cmaps' ) {
			return[
			'J' => '0%',
			'M' => '#,##0',
			'N' => '#,##0'
			];
		}elseif ($this->data['source'] == 'bts') {
			return[
			'K' => '#,##0',
			'L' => '#,##0'
			];
		}elseif ($this->data['source'] == 'sf') {
			return[
			'L' => '#,##0',
			'M' => '#,##0'
			];
		}elseif ($this->data['source'] == 'aleph') {
			return[
			'K' => '#,##0',
			'L' => '#,##0'
			];
		}elseif ($this->data['source'] == 'wbd') {
			return[
			'O' => '#,##0',
			'P' => '#,##0'
			];
		}
		
	}		
}


