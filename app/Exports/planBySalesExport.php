<?php

namespace App\Exports;

use App\planBySales;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class planBySalesExport implements FromArray, WithHeadings, WithTitle, WithEvents, WithColumnFormatting, WithStrictNullComparison, WithCustomStartCell, ShouldAutoSize {
    
    protected $collect;
    protected $report;
    protected $BKGS;

    protected $headStyle = [
        'font' => [
            'bold' => true,
            'name' => 'Verdana',
            'size' => 7,
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
                'argb' => '0070c0',
            ],
        ],
    ];

    public function __construct($collect, $report, $BKGS = null){
        $this->collect = $collect;
        $this->report = $report;
        $this->BKGS = $BKGS;
    }

    public function array(): array {

        return $this->collect;
    }

    public function headings(): array{
	
    	return [
    		'Region',
    		'Year',
    		'Month',
    		'Brand',
    		'Sales Rep',
            'Revenue',
    	];
    }

    public function title(): string{
        return "Plan by Sales";
    }

	    /**
    * @return array
    */
    public function registerEvents(): array{
        
        return [
            AfterSheet::class => function(AfterSheet $event){
                $cellRange = "A4:F4";
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->headStyle);
                
                $cell = "A1";
                $event->sheet->setCellValue($cell, $this->report);
                $event->sheet->getDelegate()->getStyle('A1')->getFont()->setSize(20)->setBold(true);

                $event->sheet->getColumnDimension('A')->setAutoSize(false);
            },
        ];
    }

    public function columnFormats(): array{
        
        return [
            'F' => '#,##0.00'
        ];
    }

    public function startCell(): string{
        return 'A4';
    }
}
