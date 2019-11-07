<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class brandExport implements FromView, WithEvents, ShouldAutoSize, WithTitle {
    
	protected $view;
	protected $data;
	protected $dataTotal;
	protected $dataType;
	protected $dataBrand;
	protected $names;

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
                'argb' => '0070c0',
            ],
        ],
    ];

    protected $lineBodyPair = [
        'font' => [
            'bold' => true,
            'name' => 'Verdana',
            'size' => 10,
            'color' => array('rgb' => '000000')
        ],
        'alignment' => [
            'horizontal' => 'center',
            'vertical' => 'center',
            'wrapText' => true
        ],
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => [
                'argb' => 'dce6f1',
            ],
        ],
    ];

    protected $lineBodyOdd = [
        'font' => [
            'bold' => true,
            'name' => 'Verdana',
            'size' => 10,
            'color' => array('rgb' => '000000')
        ],
        'alignment' => [
            'horizontal' => 'center',
            'vertical' => 'center',
            'wrapText' => true
        ],
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => [
                'argb' => 'c3d8ef',
            ],
        ],
    ];

    protected $lastLineBody = [
        'font' => [
            'bold' => true,
            'name' => 'Verdana',
            'size' => 10,
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
                'argb' => '0f243e',
            ],
        ],
    ];

    public function __construct($view, $data, $dataTotal, $dataType, $dataBrand, $names){
		$this->view = $view;
	    $this->data = $data;
	    $this->dataTotal = $dataTotal;
	    $this->dataType = $dataType;
	    $this->dataBrand = $dataBrand;
	    $this->names = $names;
	}

	public function view(): View{
		if ($this->dataType == "agency") {
			$sizeCols = 8;
			$pos = 5;
		}else{
			$sizeCols = 7;
			$pos = 4;
		}

    	return view($this->view, ['data' => $this->data, 'dataTotal' => $this->dataTotal, 'dataType' => $this->dataType, 'dataBrand' => $this->dataBrand, 'names' => $this->names, 'sizeCols' => $sizeCols, 'pos' => $pos]);
    }

    /**
    * @return array
    */
    public function registerEvents(): array{
        
        return [
            AfterSheet::class => function(AfterSheet $event){
                $cellRange = "A1";
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->headStyle);
                
                if ($this->dataType == "agency") {
                	$letter = "H";
                }else{
                	$letter = "G";
                }

                $cellRange = "A2:".$letter."2";
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->headStyle);
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(10);

                for ($d=0; $d < sizeof($this->data[0]); $d++) { 
                	$cellRange = "A".($d+3).":".$letter.($d+3);
                	if (($d+3) % 2 == 0) {
                		$event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->lineBodyOdd);
                	}else{
                		$event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->lineBodyPair);
                	}
                }

                $cellRange = "A".(sizeof($this->data[0])+2).":".$letter.(sizeof($this->data[0])+2);
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->lastLineBody);
            },
        ];
    }

    public function title(): string{
        return "ranking brand - ".$this->dataBrand;
    }
}
