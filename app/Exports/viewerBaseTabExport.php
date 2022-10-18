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
			'horizontal' => 'left',
			'vertical' => 'center',
			'wrapText' => true
		],
	];

	protected $indexStyle = [
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
	]; 

	protected $totalStyle = [
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
	];

	protected $linePair = [
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

	protected $lineOdd = [
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
				if ($this->data['source'] == 'cmaps') {
					$cellRange = 'A1';
					$event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->headStyle);

					$cellRange = 'A2:N2';
					$event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->indexStyle);

					$cellRange = 'A3:N3';
					$event->sheet->getdelegate()->getStyle($cellRange)->applyFromArray($this->totalStyle);

					$letter = 'N';

					for ($d = 0; $d < sizeof($this->data['mtx']); $d++) { 
						$cellRange = "A".($d+4).":".$letter.($d+4);
						if (($d+3) % 2 == 0) {
							$event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->linePair);
						}else{
							$event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->lineOdd);
						}
					}
				}elseif ($this->data['source'] == 'bts'){
					$cellRange = 'A1';
					$event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->headStyle);

					$cellRange = 'A2:L2';
					$event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->indexStyle);

					$cellRange = 'A3:L3';
					$event->sheet->getdelegate()->getStyle($cellRange)->applyFromArray($this->totalStyle);

					$letter = 'L';

					for ($d = 0; $d < sizeof($this->data['mtx']); $d++) { 
						$cellRange = "A".($d+4).":".$letter.($d+4);
						if (($d+3) % 2 == 0) {
							$event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->linePair);
						}else{
							$event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->lineOdd);
						}
					}
				}elseif ($this->data['source'] == 'sf'){
					$cellRange = 'A1';
					$event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->headStyle);

					$cellRange = 'A2:M2';
					$event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->indexStyle);

					$cellRange = 'A3:M3';
					$event->sheet->getdelegate()->getStyle($cellRange)->applyFromArray($this->totalStyle);

					$letter = 'M';

					for ($d = 0; $d < sizeof($this->data['mtx']); $d++) { 
						$cellRange = "A".($d+4).":".$letter.($d+4);
						if (($d+3) % 2 == 0) {
							$event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->linePair);
						}else{
							$event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->lineOdd);
						}
					}
				}elseif ($this->data['source'] == 'aleph') {
					$cellRange = 'A1';
					$event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->headStyle);

					$cellRange = 'A2:L2';
					$event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->indexStyle);

					$cellRange = 'A3:L3';
					$event->sheet->getdelegate()->getStyle($cellRange)->applyFromArray($this->totalStyle);

					$letter = 'L';

					for ($d = 0; $d < sizeof($this->data['mtx']); $d++) { 
						$cellRange = "A".($d+4).":".$letter.($d+4);
						if (($d+3) % 2 == 0) {
							$event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->linePair);
						}else{
							$event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->lineOdd);
						}
					}
				}elseif () {
					$cellRange = 'A1';
					$event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->headStyle);

					$cellRange = 'A2:K2';
					$event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->indexStyle);

					$cellRange = 'A3:K3';
					$event->sheet->getdelegate()->getStyle($cellRange)->applyFromArray($this->totalStyle);

					$letter = 'K';

					for ($d = 0; $d < sizeof($this->data['mtx']); $d++) { 
						$cellRange = "A".($d+4).":".$letter.($d+4);
						if (($d+3) % 2 == 0) {
							$event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->linePair);
						}else{
							$event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->lineOdd);
						}
					}
				}

				if ($this->type != "Excel") {

                    $event->sheet->getDelegate()->getPageSetup()
                        ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE)
                        ->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
                }
			},
		];
	}

	public function columnFormats(): array{

		if ($this->data['userRegion'] == 'Miami' ||$this->data['userRegion'] == 'Mexico' ) {
			if ($this->data['source'] == 'cmaps' ) {
				return[
				'J' => '0%',
				'M' => '#.##0',
				'N' => '#.##0'
				];
			}elseif ($this->data['source'] == 'bts') {
				return[
				'K' => '#.##0',
				'L' => '#.##0'
				];
			}elseif ($this->data['source'] == 'sf') {
				return[
				'L' => '#.##0',
				'M' => '#.##0'
				];
			}
		}else{
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
				'J' => '#,##0',
				'K' => '#,##0'
				];
			}
		}
		
	}		
}


