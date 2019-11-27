<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class viewerBaseTabExport implements FromView,WithEvents, ShouldAutoSize, WithTitle{

	protected $view;
	protected $data;

	protected $headStyle = [
		'font' => [
			'bold' => true;
			'name' => 'Verdana',
			'size' => 12,
			'color' => array('rgb'=> 'FFFFF')
		],
		'alignment' => [
			'horizontal' => 'center',
			'ventical' => 'center',
			'wrapText' => 'true'
		],
	];

	protected $bodyCenter = [
		'font' => [
			'name' => 'Verdana',
			'size' => 10,
		],
		'alignment' => [
			'horizontal' => 'center',
			'vertical' => 'center',
			'wrapText' => true
		],
	];

	public function __construct($view,$data){
		$this->view = $view;
		$this->data = $data;
	}

	public function view(): View{
		return view($this->view, ['data' => $this->data]);
	}

	public function title(): string{
		return 'Viewer - Base';
	}

	public function registerEvents(): array{

		return [
			AfterSheet::class => function(AfterSheet $event){
				$cellRange = 'A1';
				$event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->headStyle);

				
			}
		]
	}

}


