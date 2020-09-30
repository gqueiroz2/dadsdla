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


class bvTabExport implements FromView,WithEvents, WithTitle/*, WithCharts*/ {
    
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

					//$cell = "A34";
					//$event->sheet->getDelegate()->setBreak($cell, \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::BREAK_ROW);
/*
					$event->sheet->setShowGridlines(false);
					$event->sheet->getDelegate()->getPageSetup()
	                        ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE)
	                        ->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);

						
				$spreadsheet = new Spreadsheet();
						//create an excel worksheet and add some data for chart
						$worksheet = $spreadsheet->getActiveSheet();
						$var = $worksheet->fromArray([
						 ['', 2010, 2011, 2012],
						 ['Q1', 12, 15, 21],
						 ['Q2', 56, 73, 86],
						 ['Q3', 52, 61, 69],
						 ['Q4', 30, 32, 0],
						]);

						//Set the Labels for each data series we want to plot
						// Datatype
						// Cell reference for data
						// Format Code
						// Number of datapoints in series
						// Data values
						// Data Marker
						$dataSeriesLabels = [
						 new \PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues('String', 'Worksheet!$G$5', null, 1), //  2011
						];

						//Set the X-Axis Labels
						// Datatype
						// Cell reference for data
						// Format Code
						// Number of datapoints in series
						// Data values
						// Data Marker
						$xAxisTickValues = [
						 new \PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues('String', 'Worksheet!$M$5:$M$8', null, 4), //  Q1 to Q4
						];

						//Set the Data values for each data series we want to plot
						// Datatype
						// Cell reference for data
						// Format Code
						// Number of datapoints in series
						// Data values
						// Data Marker
						$dataSeriesValues = [
						 new \PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues('Number', 'Worksheet!G$6:$G$9', null, 4),
						];

						//  Build the dataseries
						$series = new \PhpOffice\PhpSpreadsheet\Chart\DataSeries(
						 \PhpOffice\PhpSpreadsheet\Chart\DataSeries::TYPE_PIECHART, // plotType
						 null, // plotGrouping (Pie charts don't have any grouping)
						 range(0, count($dataSeriesValues) - 1), // plotOrder
						 $dataSeriesLabels, // plotLabel
						 $xAxisTickValues, // plotCategory
						 $dataSeriesValues          // plotValues
						);

						//  Set up a layout object for the Pie chart
						$layout = new \PhpOffice\PhpSpreadsheet\Chart\Layout();
						$layout->setShowVal(true);
						$layout->setShowPercent(true);

						//  Set the series in the plot area
						$plotArea = new \PhpOffice\PhpSpreadsheet\Chart\PlotArea($layout, [$series]);
						//  Set the chart legend
						$legend = new \PhpOffice\PhpSpreadsheet\Chart\Legend(\PhpOffice\PhpSpreadsheet\Chart\Legend::POSITION_RIGHT, null, false);

						$title = new \PhpOffice\PhpSpreadsheet\Chart\Title('Test Pie Chart');

						//  Create the chart
						$chart = new Chart(
						 'chart', // name
						 $title, // title
						 $legend, // legend
						 $plotArea, // plotArea
						 true, // plotVisibleOnly
						 'gap', // displayBlanksAs
						 null, // xAxisLabel
						 null   // yAxisLabel    - Pie charts don't have a Y-Axis
						);

						//Set the position where the chart should appear in the worksheet
						$chart->setTopLeftPosition('G5');
						$chart->setBottomRightPosition('H20');

						//Add the chart to the worksheet
						$worksheet->addChart($chart);

						//Save Excel 2007 file
						/*
						$filename ='Test';
						$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
						$writer->setIncludeCharts(true);
						*/
						$Excel_writer = new Xlsx($spreadsheet);

						ob_end_clean();
						$Excel_writer->setIncludeCharts(true);
						$Excel_writer->save('php://output');*/
			},
		];
	}


}
