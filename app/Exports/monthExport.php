<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class monthExport implements FromArray, WithMultipleSheets {
    
	protected $sheets;
    protected $label;

    public function __construct(array $sheets, $label){
		$this->sheets = $sheets;
        $this->label = $label;
	}

    public function array(): array {
        return $this->sheets;
    }

    public function sheets(): array{
    	
    	$sheets = [
            new monthTabExport($this->label, $this->sheets)
        ];

    	return $sheets;
    	
    }
}
