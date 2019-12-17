<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class summaryTabExport implements FromView, WithEvents, ShouldAutoSize, WithTitle, WithColumnFormatting {
    
    protected $view;
	protected $data;
	protected $tab;
	protected $headInfo;
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

    public function __construct($view, $data, $tab, $headInfo, $type){
		$this->view = $view;
	    $this->data = $data;
	    $this->tab = $tab;
	    $this->headInfo = $headInfo;
        $this->type = $type;
	}

	public function view(): View{

    	return view($this->view, ['data' => $this->data, 'tab' => $this->tab, 'headInfo' => $this->headInfo]);
    }

    /**
    * @return array
    */
    public function registerEvents(): array{
        
        return [
            AfterSheet::class => function(AfterSheet $event){
                $cellRange = "A1";
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->headStyle);

                $cellRange = "A2:I2";
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->headStyle);
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(8);

                $letter = "I";

                for ($d=0; $d < sizeof($this->data); $d++) { 
                	$cellRange = "A".($d+3).":".$letter.($d+3);
                    $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->bodyCenter);
                }

                if ($this->type != "Excel") {
                    $event->sheet->getDelegate()->getPageSetup()
                        ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE)
                        ->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
                }
            },
        ];
    }

    public function title(): string{
        return "summary - ".$this->tab;
    }

    public function columnFormats(): array{
        
        return [
            'B' => '#,##0',
            'C' => '#,##0',
            'D' => '#,##0',
            'E' => '#,##0',
            'F' => '#,##0',
            'G' => '0%',
            'H' => '0%',
            'I' => '0%'
        ];
    }
}
