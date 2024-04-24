<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class forecastTabExport implements FromView,WithEvents, ShouldAutoSize, WithTitle, WithColumnFormatting {

	protected $view;
	protected $data;

	public function __construct($view, $data, $type){
        $this->view = $view;
        $this->data = $data;
    }

    public function view(): View{
		return view($this->view, ['data' => $this->data]);
	}
    
	public function title(): string{
		
		return 'Monthly Forecast';
	}

	public function registerEvents(): array{
		return [
			AfterSheet::class => function(AfterSheet $event){

				$event->sheet->setShowGridlines(false);
				//$cellRange = "B10:AA10";
			},
		];

	}

	public function columnFormats(): array{

		/*if ($this->data['regionName'] == 'Miami' || $this->data['regionName'] == 'Mexico' ) {
			return[
				//'B' => "#.##",
				'C' => "#.##0", 
				'D' => "#.##0",
				'E' => "#.##0",
			   	'F' => "#.##0",
			   	'G' => "#.##0",
			    'H' => "#.##0",
			    'I' => "#.##0",
			    'J' => "#.##0",
			    'K' => "#.##0",
			    'L' => "#.##0",
			    'M' => "#.##0",
			    'N' => "#.##0",
			    'O' => "#.##0",
			    'P' => "#.##0",
			    'Q' => "#.##0",
			    'R' => "#.##0",
			    'T' => "#.##0",
			    'U' => "#.##0",
			    'V' => "#.##0",
			    'W' => "#.##0",
			    'X' => "#.##0",
			    'Y' => "#.##0",
			    'Z' => "#.##0",
			    'AA' => "#.##0"
			];
		}else{*/
			return[
				'I' => "#,##0"						   	
			];
		//}
	}
}

