<?php

namespace App\Exports;

use App\digital;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class digitalExport implements FromCollection, WithHeadings, WithTitle, WithEvents, WithColumnFormatting, WithStrictNullComparison {
    
	protected $collect;
    protected $region;
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
    		'Year',
    		'Month',
    		'Client',
    		'Agency',
    		'Campaign',
    		'Insertion Order',
    		'Insertion Order ID',
    		'Region',
    		'Sales Rep',
    		'IO Start Date',
    		'IO End Date',
    		'Agency Commission Percentage',
    		'Rep Commission Percentage',
    		'Currency',
    		'Placement',
    		'Buy Type',
    		'Content Targeting Set Name',
    		'Ad Unit',
    		'Type of Revenue',
            'Revenue',
    	];
    }

    public function title(): string{
        return "Digital (".$this->year.") - ".$this->region;
    }

    /**
    * @return array
    */
    public function registerEvents(): array{
        
        return [
            AfterSheet::class => function(AfterSheet $event){
                $cellRange = "A1:T1";
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->headStyle);
            },
        ];
    }

    public function columnFormats(): array{
        
        return [
            'L' => '#0%',
            'M' => '#0%',
            'T' => '#,##0.00'
        ];
    }
}
