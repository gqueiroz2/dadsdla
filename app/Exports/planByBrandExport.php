<?php

namespace App\Exports;

use App\planByBrand;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class planByBrandExport implements FromCollection, WithHeadings, WithTitle, WithEvents, WithColumnFormatting, WithStrictNullComparison {
    
	protected $collect;
    protected $region;
    protected $year;
    protected $plan;

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

	public function __construct($collect, $region, $year){
		$this->collect = $collect;
        $this->region = $region;
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
    		'Region',
    		'Year',
    		'Month',
    		'Brand',
    		'Source',
    		'Currency',
    		'Type of Revenue',
    		'Revenue',
    	];
    }

    public function title(): string{
        return "Plan By Brand (".$this->year.") - ".$this->region;
    }

    /**
    * @return array
    */
    public function registerEvents(): array{
        
        return [
            AfterSheet::class => function(AfterSheet $event){
                $cellRange = "A1:H1";
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->headStyle);
            },
        ];
    }

    public function columnFormats(): array{
        
        return [
            'H' => '#,##0.00'
        ];
    }
}
