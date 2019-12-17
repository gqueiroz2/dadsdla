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
    protected $type;

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
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => [
                'rgb' => '0070c0',
            ],
        ],
    ];

    protected $bodyCenter = [
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

    public function __construct($view, $data, $type){
        $this->view = $view;
        $this->data = $data;
        $this->type = $type;
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

                for ($dm=3; $dm < ((sizeof($this->data['mtx'])*4)+10); $dm++) { 
                    $cellRange = "A".$dm.":M".$dm;
                    $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->bodyCenter);
                }

                if ($this->type != "Excel") {
                    
                    $c = 0;

                    for ($dm=3; $dm < ((sizeof($this->data['mtx'])*4)+10); $dm++) { 
                        $c++;

                        if ($c == (sizeof($this->data['mtx'])+2)) {
                            $cell = "A".($dm-1);
                            $event->sheet->getDelegate()->setBreak($cell, \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::BREAK_ROW);

                            $cellMerge = "A".($dm).":M".($dm);
                            $event->sheet->getDelegate()->mergeCells($cellMerge);

                            $cell = "A".($dm);
                            $event->sheet->getCell($cell)->setValue($this->data['region']." - Year Over Year : BKGS - ".$this->data['year']." (".strtoupper($this->data['currency'][0]['name']).")/".strtoupper($this->data['value'].")"));

                            $event->sheet->getDelegate()->getStyle($cellMerge)->applyFromArray($this->headStyle);
                            
                            $c = 0;
                        }
                    }

                    $cellRange = "A2:M2";
                    $event->sheet->getDelegate()->mergeCells($cellRange);
                    $event->sheet->getDelegate()->getPageSetup()
                        ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE)
                        ->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A3);
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
