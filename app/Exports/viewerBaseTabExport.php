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

	protected $headStyle = [
		'font' => [
			'bold' => true,
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

	protected $linePair = [
		'font' => [
			'bold' => true,
			'name' => 'Verdana',
			'size' => 10,
			'color' => array('rgb' => '0000000')
		],
		'alignment' => [
			'horizontal' => 'center',
			'vertical' => 'center',
			'wrapText' => true
		],
		'fill' => [
			'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
			'startColor' => [
				'argb' => 'f9fbfd',
			],
		],
	];

	protected $lineOdd = [
		'font' => [
			'bold' => true,
			'name' => 'Verdana',
			'size' => 10,
			'color' => array('rgb' => '0000000')
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

	public function __construct($view, $data){
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

				$cellRange = 'A2:N2';
				$event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->headStyle);
				$event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(9);

				$letter = 'N';

				for ($d = 0; $d < sizeof($this->data['mtx']); $d++) { 
					$cellRange = "A".($d+3).":".$letter.($d+3);
					if (($d+3) % 2 == 0) {
						$event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->linePair);
					}else{
						$event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->lineOdd);
					}
				}
			},
		];
	}

	public function columnFormats(): array{

		return[
			'K' => '#0%',
			'N' => '#,##0'
		];
	}
}


