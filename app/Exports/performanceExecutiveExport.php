<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;

class performanceExecutiveExport implements FromArray, WithMultipleSheets, WithTitle {

    protected $sheets;
    protected $labels;
    protected $typeExport;
    protected $title;

    public function __construct(array $sheets, $labels, $typeExport, $title){
        $this->sheets = $sheets;
        $this->labels = $labels;
        $this->typeExport = $typeExport;
        $this->title = $title;
    }

    public function array(): array {
        return $this->sheets;
    }

    public function sheets(): array{
    	
    	$sheet = [
            new performanceExecutiveCase1Export($this->labels[0], $this->sheets, $this->typeExport),
            new performanceExecutiveCase2Export($this->labels[1], $this->sheets, $this->typeExport),
            new performanceExecutiveCase3Export($this->labels[2], $this->sheets, $this->typeExport),
            new performanceExecutiveCase4Export($this->labels[3], $this->sheets, $this->typeExport)
        ];

        return $sheet;
    }

    public function title(): string{
        return $this->title;
    }
}
