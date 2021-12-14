<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class yoyBrandTabExport implements FromView, WithEvents, ShouldAutoSize, WithTitle {
    
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

    protected $dc = [
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => [
                'rgb' => '0070c0',
            ],
        ],
        'font' => [
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
    protected $hh = [
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => [
                'rgb' => 'ff6600',
            ],
        ],
        'font' => [
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
    protected $dk = [
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => [
                'rgb' => 'ffff00',
            ],
        ],
        'font' => [
            'name' => 'Verdana',
            'size' => 12
        ],
        'alignment' => [
            'horizontal' => 'center',
            'vertical' => 'center',
            'wrapText' => true
        ],
    ];
    protected $ap = [
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => [
                'rgb' => '009933',
            ],
        ],
        'font' => [
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
    protected $tlc = [
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => [
                'rgb' => 'ff0000',
            ],
        ],
        'font' => [
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
    protected $id = [
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => [
                'rgb' => '000000',
            ],
        ],
        'font' => [
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
    protected $dt = [
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => [
                'rgb' => '002060',
            ],
        ],
        'font' => [
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
    protected $fn = [
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => [
                'rgb' => 'ff0000',
            ],
        ],
        'font' => [
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
    protected $onl = [
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => [
                'rgb' => '6600ff',
            ],
        ],
        'font' => [
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
    protected $vix = [
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => [
                'rgb' => '004b84',
            ],
        ],
        'font' => [
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
    protected $oth = [
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => [
                'rgb' => '808080',
            ],
        ],
        'font' => [
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
    protected $hgtv = [
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => [
                'rgb' => '88cc00',
            ],
        ],
        'font' => [
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

    protected $axn = [
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => [
                'rgb' => 'ba1111',
            ],
        ],
        'font' => [
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

    protected $axd = [
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => [
                'rgb' => 'ba1111',
            ],
        ],
        'font' => [
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

    protected $gc = [
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => [
                'rgb' => '8f60a6',
            ],
        ],
        'font' => [
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


    protected $ho = [
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => [
                'rgb' => '4ba396',
            ],
        ],
        'font' => [
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
   

    protected $ac = [
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => [
                'rgb' => '968d69',
            ],
        ],
        'font' => [
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

    protected $es = [
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => [
                'rgb' => '27247d',
            ],
        ],
        'font' => [
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
   


    protected $tp = [
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => [
                'rgb' => 'bbcc5e',
            ],
        ],
        'font' => [
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

    protected $df = [
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => [
                'rgb' => '616161',
            ],
        ],
        'font' => [
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

    protected $son = [
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => [
                'rgb' => '000000',
            ],
        ],
        'font' => [
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

    protected $sd = [
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => [
                'rgb' => '000000',
            ],
        ],
        'font' => [
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

    protected $eus = [
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => [
                'rgb' => '000000',
            ],
        ],
        'font' => [
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


    protected $ias = [
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => [
                'rgb' => '21346b',
            ],
        ],
        'font' => [
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

    protected $atv = [
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => [
                'rgb' => '26ff00',
            ],
        ],
        'font' => [
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

    protected $dn = [
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => [
                'rgb' => '0f243e',
            ],
        ],
        'font' => [
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

    public function __construct($view, $data, $type){
        $this->view = $view;
        $this->data = $data;
        $this->type = $type;
    }

	public function view(): View{
    	return view($this->view, ['data' => $this->data]);
    }

    public function title(): string{
        return "YoY - Brand";
    }

     public function brandName($name){
        
        if ($name == "DC") {
            return $this->dc;
        }elseif ($name == "HH") {
            return $this->hh;
        }elseif ($name == "DK") {
            return $this->dk;
        }elseif ($name == "AP") {
            return $this->ap;
        }elseif ($name == "TLC") {
            return $this->tlc;
        }elseif ($name == "ID") {
            return $this->id;
        }elseif ($name == "DT") {
            return $this->dt;
        }elseif ($name == "FN") {
            return $this->fn;
        }elseif ($name == "ONL") {
            return $this->onl;
        }elseif ($name == "VIX") {
            return $this->vix;
        }elseif ($name == "OTH") {
            return $this->oth;
        }elseif ($name == "HGTV") {
            return $this->hgtv;
        }elseif ($name == "DN") {
            return $this->dn;
        }elseif ($name == "GC"){
            return $this->gc;
        }elseif ($name == "HO"){
            return $this->ho;
        }elseif ($name == "ATV"){
            return $this->atv;
        }elseif ($name == "AXN"){
            return $this->axn;
        }elseif ($name == "SON"){
            return $this->son;
        }elseif ($name == "AC"){
            return $this->ac;
        }elseif ($name == "SD"){
            return $this->sd;
        }elseif ($name == "AXD"){
            return $this->axd;
        }elseif ($name == "ES"){
            return $this->es;
        }elseif ($name == "IAS"){
            return $this->ias;
        }elseif ($name == "DF"){
            return $this->df;
        }elseif ($name == "EUS"){
            return $this->eus;
        }elseif ($name == "TP"){
            return $this->tp;
        }
    }

    /**
    * @return array
    */
    public function registerEvents(): array{

    	return [
    		AfterSheet::class => function(AfterSheet $event){

                $cellRange = "A1";
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->headStyle);
                
            	for ($i=3; $i < ((sizeof($this->data['mtx'])*7)+1); $i+=7) {
            		$cellRange = "A".$i.":A".($i+5);
            		$event->sheet->getDelegate()->mergeCells($cellRange);
                }

                $a = 10;

                 for ($b=0; $b < sizeof($this->data['mtx']); $b++) { 
                    if ($b == 0) {
                        $cellRange = "A3";
                    }else{
                        $cellRange = "A".$a;
                        $a = $a + 7;
                    }
                    
                    //$bName = array($this->data['mtx'][$b][0][0]);
                    //var_dump($bName);
                    $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->brandName($this->data['mtx'][$b][0][0]));
                }

                for ($dm=3; $dm < ((sizeof($this->data['mtx'])*7)+2); $dm++) { 
                    $cellRange = "A".$dm.":O".$dm;
                    $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->bodyCenter);
                }

                for ($dm=0; $dm < sizeof($this->data['mtx']); $dm++) { 
                    if ($dm == 0) {
                        $cellRange = "B".($dm+4).":O".($dm+4);
                        $cellRange2 = "B".($dm+5).":O".($dm+5);
                        $cellRange3 = "B".($dm+6).":O".($dm+6);
                        $cellRange4 = "B".($dm+7).":O".($dm+7);
                        $cellRange5 = "B".($dm+8).":O".($dm+8);

                        $b = 4;
                        $b2 = 5;
                        $b3 = 6;
                        $b4 = 7;
                        $b5 = 8;
                    }else{
                        $cellRange = "B".($b+7).":O".($b+7);
                        $cellRange2 = "B".($b2+7).":O".($b2+7);
                        $cellRange3 = "B".($b3+7).":O".($b3+7);
                        $cellRange4 = "B".($b4+7).":O".($b4+7);
                        $cellRange5 = "B".($b5+7).":O".($b5+7);

                        $b = $b+7;
                        $b2 = $b2+7;
                        $b3 = $b3+7;
                        $b4 = $b4+7;
                        $b5 = $b5+7;
                    }

                    $event->sheet->getStyle($cellRange)->getNumberFormat()->applyFromArray(array('formatCode' => "#,##0"));

                    $event->sheet->getStyle($cellRange2)->getNumberFormat()->applyFromArray(array('formatCode' => "#,##0"));

                    $event->sheet->getStyle($cellRange3)->getNumberFormat()->applyFromArray(array('formatCode' => "#,##0"));

                    $event->sheet->getStyle($cellRange4)->getNumberFormat()->applyFromArray(array('formatCode' => "#,##0"));

                    $event->sheet->getStyle($cellRange5)->getNumberFormat()->applyFromArray(array('formatCode' => "#,##0"));
                }

                if ($this->type != "Excel") {

                    $c = 0;
                    for ($i=3; $i < ((sizeof($this->data['mtx'])*7)+1); $i+=7) { 
                        $c++;

                        if ($c == 5) {
                            $cell = "A".($i-2);
                            $event->sheet->getDelegate()->setBreak($cell, \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::BREAK_ROW);

                            $cellMerge = "A".($i-1).":O".($i-1);
                            $event->sheet->getDelegate()->mergeCells($cellMerge);

                            $cell = "A".($i-1);
                            $event->sheet->getCell($cell)->setValue($this->data['region']." - Year Over Year : BKGS - ".$this->data['year']." (".strtoupper($this->data['currency'][0]['name']).")/".strtoupper($this->data['value'].")"));

                            $event->sheet->getDelegate()->getStyle($cellMerge)->applyFromArray($this->headStyle);
                            
                            $c = 0;
                        }
                    }

                    $cellRange = "A2:O2";
                    $event->sheet->getDelegate()->mergeCells($cellRange);
                    $event->sheet->getDelegate()->getPageSetup()
                        ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE)
                        ->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A3);
                }
            }
    	];
    }

}
