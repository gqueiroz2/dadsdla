<?php

namespace App\Exports;

use App\cmaps;
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

class cmapsExport implements FromArray, WithHeadings, WithTitle, WithEvents, WithColumnFormatting, WithStrictNullComparison, WithCustomStartCell, ShouldAutoSize {
    
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
    		'Decode',
            'Year',
    		'Month',
    		'Map Number',
            'Sales Rep',
    		'Package',
    		'Client',
    		'Product',
    		'Segment',
    		'Agency',
    		'Brand',
    		'Pi Number',
    		'Revenue',
    		'Market',
            'Discount',
    		'Client CNPJ',
    		'Agency CNPJ',
    		'Media Type',
    		'Log',
    		'Ad Sales Support',
    		'OBS',
    		'Sector',
    		'Category',
    	];
    }

    public function title(): string{
        if (is_null($this->BKGS)) {
            return "TV";
        }else{
            return $this->BKGS;
        }
    }

    /**
    * @return array
    */
    public function registerEvents(): array{
        
        return [
            AfterSheet::class => function(AfterSheet $event){
                $cellRange = "A4:W4";
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
            'M' => '#,##0.00',
            'O' => '#0%'
        ];
    }

    public function startCell(): string{
        return 'A4';
    }
}