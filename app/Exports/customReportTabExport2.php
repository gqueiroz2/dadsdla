<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class customReportTabExport2 implements FromView,WithEvents, ShouldAutoSize, WithTitle, WithColumnFormatting {

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
		
		return 'AE';
	}

	public function registerEvents(): array{
		return [
			AfterSheet::class => function(AfterSheet $event){

				$event->sheet->setShowGridlines(false);
			},
		];

	}

	public function columnFormats(): array{

		return[

		];
	}
}
