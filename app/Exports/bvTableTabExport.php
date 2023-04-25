<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class bvTableTabExport implements FromView, ShouldAutoSize, WithTitle{
    protected $view;
    protected $data;
    public function __construct($view,$data){
        $this->view = $view;
        $this->data = $data;
    }
    public function view(): View{
        return view($this->view, ['data'=> $this->data]);
    }
    public function title(): String{
        return 'AVB %';
    }
}
