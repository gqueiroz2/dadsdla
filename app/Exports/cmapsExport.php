<?php

namespace App\Exports;

use App\cmaps;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class cmapsExport implements FromCollection, WithHeadings, WithTitle, WithEvents, WithColumnFormatting, WithStrictNullComparison {
    
	protected $collect;
    protected $year;

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

	public function __construct($collect, $year){
		$this->collect = $collect;
        $this->year = $year;
	}

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection() {
        $collect = array($this->collect);

        return collect($collect);
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
            'Currency',
    		'Type of Revenue',
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
        return "Cmaps - ".$this->year;
    }

    /**
    * @return array
    */
    public function registerEvents(): array{
        
        return [
            AfterSheet::class => function(AfterSheet $event){
                $cellRange = "A1:X1";
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->headStyle);
            },
        ];
    }

    public function columnFormats(): array{
        
        return [
            'O' => '#,##0.00',
            'Q' => '#0%'
        ];
    }
}
