<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class resumeTabExport implements FromView,WithEvents, ShouldAutoSize, WithTitle, WithColumnFormatting {

    protected $view;
    protected $data;

    protected $style = [
        'alignment' => [
            'horizontal' => 'center',
            'vertical' => 'center'
        ],
    ];    

    public function __construct($view, $data, $type){
        $this->view = $view;
        $this->data = $data;
    }

    public function view(): View{
        return view($this->view, ['data' => $this->data]);
    }
    
    public function title(): string{
        
        return 'Resume';
    }

    public function registerEvents(): array{
        return [
            AfterSheet::class => function(AfterSheet $event){

                $event->sheet->setShowGridlines(false);
                
                //$event->sheet->getNamedRange('Table1');
                
            },
        ];

    }

    public function columnFormats(): array{
            return[
                'B' => "#,##0", 
                'C' => "#,##0", 
                'D' => "#,##0",
                'E' => "#,##0",
                'F' => "#,##0",
                'G' => "#,##0",
                'H' => "#,##0" ,
                'I' => "0%"                 
            ];
    }
}
