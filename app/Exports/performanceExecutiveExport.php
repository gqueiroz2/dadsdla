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
            new executiveCase1Export($this->labels[0], $this->sheets)
        ];

        return $sheet;
    }
}
