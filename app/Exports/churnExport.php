<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class churnExport implements FromView, WithEvents, ShouldAutoSize, WithTitle, WithColumnFormatting, WithStrictNullComparison {
    
    protected $view;
	protected $data;
	protected $dataTotal;	
	protected $dataChurn;
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

    public function __construct($view, $data, $dataTotal, $dataChurn, $names){
		$this->view = $view;
	    $this->data = $data;
	    $this->dataTotal = $dataTotal;
	    $this->dataChurn = $dataChurn;
	    $this->names = $names;
	}

	public function view(): View{

    	return view($this->view, ['data' => $this->data, 'dataTotal' => $this->dataTotal, 'dataChurn' => $this->dataChurn, 'names' => $this->names]);
    }

    /**
    * @return array
    */
    public function registerEvents(): array{
        
        return [
            AfterSheet::class => function(AfterSheet $event){
                $cellRange = "A1";
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->headStyle);

                if ($this->names['type'] == "client") {
                    $letter = "L";
                }else{
                    $letter = "K";
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

    	setlocale(LC_ALL, 'pt_BR');
        $nome = preg_replace( '/[`^~\'"]/', null, iconv( 'UTF-8', 'ASCII//TRANSLIT', $this->dataChurn ) );

   		if(strlen($nome) > 30){
   			$i = strpos($nome, " ");

			$nome = substr($nome, 0, $i);
   		}

   		return $nome;
    }

    public function columnFormats(): array{

        if ($this->names['val'] == "client") {
            return [
                'D' => '#,##0',
                'E' => '#,##0',
                'F' => '#,##0',
                'G' => '#0%',
                'H' => '#,##0',
                'I' => '#,##0',
                'J' => '#,##0',
                'K' => '#,##0'
            ];
        }else{
            return [
                'C' => '#,##0',
                'D' => '#,##0',
                'E' => '#,##0',
                'F' => '#0%',
                'H' => '#,##0',
                'I' => '#,##0',
                'I' => '#,##0',
                'J' => '#,##0'
            ];
        }
    }
}
