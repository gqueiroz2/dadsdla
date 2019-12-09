<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class yoyMonthTabExport implements FromView, WithEvents, ShouldAutoSize, WithTitle, WithColumnFormatting {
    
    protected $view;
	protected $data;

	protected $headStyle = [
	    'font' => [
	        'bold' => true,
	        'name' => 'Verdana',
	        'size' => 12,
	        'color' => array('rgb' => 'FFFFFF')
	    ],
	    'alignment' => [
	        'horizontal' => 'center',
	        'vertical' => 'center',
	        'wrapText' => true
	    ],
    ];

    protected $BodyCenter = [
        'font' => [
            'name' => 'Verdana',
            'size' => 10,
        ],
        'alignment' => [
            'horizontal' => 'center',
            'vertical' => 'center',
            'wrapText' => true
        ],
    ];

    public function __construct($view, $data){
		$this->view = $view;
	    $this->data = $data;
	}

	public function view(): View{
    	return view($this->view, ['data' => $this->data]);
    }

    public function title(): string{
        return "YoY - Month";
    }

    /**
    * @return array
    */
    public function registerEvents(): array{

    	return [
    		AfterSheet::class => function(AfterSheet $event){
                $cellRange = "A1";
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->headStyle);

                for ($dm=3; $dm < ((sizeof($this->data['mtx'])*13)+2); $dm++) { 
                    $cellRange = "A".$dm.":M".$dm;
                    $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->BodyCenter);
                }
            }
    	];
    }

    public function columnFormats(): array{
        
        return [
            "B" => "#,##0",
            "C" => "#,##0",
            "D" => "#,##0",
            "E" => "#,##0",
            "F" => "#,##0",
            "G" => "#,##0",
            "H" => "#,##0",
            "I" => "#,##0",
            "J" => "#,##0",
            "K" => "#,##0",
            "L" => "#,##0",
            "M" => "#,##0"
        ];
    }
}