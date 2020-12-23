<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithCharts;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;
use PhpOffice\PhpSpreadsheet\Chart\Layout;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class bvTabExport implements FromView,WithEvents, WithTitle, WithCharts {
    
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

	public function charts()
    {

    	//$phpexcel->setActiveSheetIndex(0);
 		//$sheet = $phpexcel->getActiveSheet();

        $labels     = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$A$1', null)];
        $categories = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$A$1:$A$2', null)];
        $values     = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Products!$C$2:$C$5', null)];

        $chart1 = new Chart(
            'chart',
            new Title('Test Pie Chart'),
            new Legend(),
            new PlotArea(null, [
                new DataSeries(DataSeries::TYPE_PIECHART, null, range(0, count($values) - 1), $labels, $categories, $values)
            ])
        );

        $chart1->setTopLeftPosition('G5');
        $chart1->setBottomRightPosition('P20');

        $sheet->addChart($chart1);

        return $sheet;
    }
	
	public function registerEvents(): array{

		return [
			AfterSheet::class => function(AfterSheet $event){

					//$cell = "A34";
					//$event->sheet->getDelegate()->setBreak($cell, \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::BREAK_ROW);

					$event->sheet->setShowGridlines(false);
					$event->sheet->getDelegate()->getPageSetup()
	                        ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE)
	                        ->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);

						
					$spreadsheet = new Spreadsheet();
					//create an excel worksheet and add some data for chart
					$worksheet = $spreadsheet->getActiveSheet();
					

						
			},
		];
	}


}
