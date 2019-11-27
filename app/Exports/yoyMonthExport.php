<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class yoyMonthExport implements FromArray, WithMultipleSheets {
    
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
    	
    	$sheets = [
            new yoyMonthTabExport($this->labels[0], $this->sheets),
            new yoySemesterTabExport($this->labels[1], $this->sheets)
        ];

        return $sheets;
    }
}
