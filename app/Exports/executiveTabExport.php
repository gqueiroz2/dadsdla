<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class executiveTabExport implements FromCollection{
    
    public function __construct($view,$data){
    	$this->view = $view;
    	$this->data = $data;
    }

    public function view(): View{
    	return view($this->view, ['data' => $this->data], ['size' => $this->size], ['x' => $this->x]);
    }
}
