<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class summaryExport implements FromArray, WithMultipleSheets {
    
	protected $sheets;
    protected $auxSheets;
    protected $labels;

	public function __construct(array $sheets, $labels, $auxSheets){
		$this->sheets = $sheets;
        $this->labels = $labels;
        $this->auxSheets = $auxSheets;
	}

    public function array(): array {
        return $this->sheets;
    }

    public function sheets(): array{
    	
        $sheets = array();

        for ($a=0; $a < sizeof($this->auxSheets); $a++) { 
            array_push($sheets, new summaryTabExport($this->labels, $this->sheets[$this->auxSheets[$a]], $this->auxSheets[$a], $this->sheets));
        }

    	return $sheets;    	
    }
}
