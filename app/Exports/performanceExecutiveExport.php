<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class performanceExecutiveExport implements FromArray, WithMultipleSheets {

    protected $sheets;
    protected $labels;

    public function __construct(array $sheets, $labels){
		$this->sheets = $sheets;
        $this->labels = $labels;
	}

    public function array(): array {

        return $this->sheets;
    }

    public function sheets(): array{
    	
    	$sheet = [
            new performanceExecutiveCase1Export($this->labels[0], $this->sheets),
            new performanceExecutiveCase2Export($this->labels[1], $this->sheets),
            new performanceExecutiveCase3Export($this->labels[2], $this->sheets),
            new performanceExecutiveCase4Export($this->labels[3], $this->sheets),
        ];

        return $sheet;
    }
}
