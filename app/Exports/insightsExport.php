<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;

class insightsExport implements FromArray, WithMultipleSheets, WithTitle {

    protected $sheets;
    protected $labels;
    protected $title;
    protected $typeExport;

    public function __construct(array $sheets, $labels,$typeExport, $title){
        $this->sheets = $sheets;
        $this->labels = $labels;
        $this->title = $title;
        $this->typeExport = $typeExport;
    }

    public function array(): array {

        return $this->sheets;
    }

    public function sheets(): array{
        
        $sheet = array();

        array_push($sheet, new viewerInsightsTabExport($this->labels[0], $this->sheets, $this->typeExport)); 
        array_push($sheet, new idNumberTabExport($this->labels[1], $this->sheets, $this->typeExport));    

        return $sheet;
    }

    public function title(): string{
        return $this->title;
    }
}
