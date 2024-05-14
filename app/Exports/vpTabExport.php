<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class vpTabExport implements  FromView,WithEvents, ShouldAutoSize, WithTitle, WithColumnFormatting {

    protected $view;
    protected $data;
    protected $type;


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
        return 'Manager  View';
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
            'G' => '#,##0'
            ];
        
        
    }       
}