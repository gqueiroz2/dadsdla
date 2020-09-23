<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCharts;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;
use PhpOffice\PhpSpreadsheet\Chart\Layout;


class bvTabExport implements FromView,WithEvents, ShouldAutoSize, WithTitle, WithColumnFormatting/*, WithCharts*/ {
    
   	public function __construct($view, $data, $typeExport){
        $this->view = $view;
        $this->data = $data;
        $this->typeExport = $typeExport;
    }

    public function view(): View{
		$c = 0;
		return view($this->view, ['data' => $this->data, 'typeExport' => $this->typeExport, 'c' => $c]);
	}

	public function title(): string{
		return 'Overview - BV';
	}

	public function registerEvents(): array{

		return [
			AfterSheet::class => function(AfterSheet $event){
				
				$number = 3;

				$event->sheet->getDelegate()->mergeCells("A3:F3");
				$event->sheet->getDelegate()->mergeCells("A7:F7");
				$event->sheet->getDelegate()->mergeCells("A11:F11");
				$event->sheet->getDelegate()->mergeCells("A15:F15");
				 $cell = "A".($number-2);
				if ($this->typeExport == "ExcelPDF") {

                    $event->sheet->getDelegate()->getPageSetup()
                    	->setHorizontalCentered(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::true)
                        ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE)
                        ->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
                }
			},
		];
	}

	public function columnFormats(): array{
		return[

		];
	}
}
