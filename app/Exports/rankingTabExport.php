<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class rankingTabExport implements FromView, WithEvents, ShouldAutoSize, WithTitle, WithColumnFormatting {
    
    protected $view;
	protected $data;
	protected $dataTotal;	
	protected $dataNew;
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

    protected $head2Style = [
        'font' => [
            'italic' => true,
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

    public function __construct($view, $data, $dataTotal, $dataRanking, $names){
		$this->view = $view;
	    $this->data = $data;
	    $this->dataTotal = $dataTotal;
	    $this->dataRanking = $dataRanking;
	    $this->names = $names;
	}

	public function view(): View{

    	return view($this->view, ['data' => $this->data, 'dataTotal' => $this->dataTotal, 'dataRanking' => $this->dataRanking, 'names' => $this->names]);
    }

    /**
    * @return array
    */
    public function registerEvents(): array{
        
        return [
            AfterSheet::class => function(AfterSheet $event){
                $cellRange = "A1";
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->headStyle);

                $cellRange = "A2";
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->head2Style);

                $cellRange = "A3";
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->head2Style);

                if ($this->names['type'] == "client") {
                    $letter = "J";
                }else{
                    $letter = "I";
                }

                $cellRange = "A5:".$letter."5";
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->headStyle);
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(10);

                for ($d=0; $d < sizeof($this->data[0]); $d++) { 
                	$cellRange = "A".($d+6).":".$letter.($d+6);
                	if (($d+6) % 2 == 0) {
                		$event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->lineBodyOdd);
                	}else{
                		$event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->lineBodyPair);
                	}

                    if ($this->names['type'] == "client") {
                        $cell = $event->sheet->getCell("J".($d+6))->getValue();

                        if (is_numeric($cell)) {
                            $event->sheet->getCell("J".($d+6))->setValue($cell/100);
                        }
                    }else{
                        $cell = $event->sheet->getCell("I".($d+6))->getValue();

                        if (is_numeric($cell)) {
                            $event->sheet->getCell("I".($d+6))->setValue($cell/100);
                        }
                    }
                }

                $cellRange = "A".(sizeof($this->data[0])+5).":".$letter.(sizeof($this->data[0])+5);
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->lastLineBody);
            },
        ];
    }

    public function title(): string{

    	$a = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûüýýþÿŔŕ?';
        $b = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuuyybyRr-';
        $nome = strtr($this->dataRanking, utf8_decode($a), $b);
        $nome = preg_replace("/[^0-9a-zA-Z\.\s+]+/",'',$nome);

   		if(strlen($nome) > 30){
   			$i = strpos($nome, " ");

			$nome = substr($nome, 0, $i);
   		}

   		return $nome;   
    }

    public function columnFormats(): array{

        if ($this->names['type'] == "client") {
            
            return [
	            'F' => '#,##0',
	            'G' => '#,##0',
	            'H' => '#,##0',
	            'I' => '#,##0',
	            'J' => '#0%'
        	];
    	}else{
    		return [
	            'E' => '#,##0',
	            'F' => '#,##0',
	            'G' => '#,##0',
	            'H' => '#,##0',
	            'I' => '#0%'
        	];
        }
    }
}
