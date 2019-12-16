<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;

class summaryExport implements FromArray, WithMultipleSheets, WithTitle {
    
	protected $sheets;
    protected $auxSheets;
    protected $labels;
    protected $typeExport;
    protected $title;

	public function __construct(array $sheets, $labels, $auxSheets, $typeExport, $title){
		$this->sheets = $sheets;
        $this->labels = $labels;
        $this->auxSheets = $auxSheets;
        $this->typeExport = $typeExport;
        $this->title = $title;
	}

    public function array(): array {
        return $this->sheets;
    }

    public function sheets(): array{
    	
        $sheets = array();

        for ($a=0; $a < sizeof($this->auxSheets); $a++) { 
            array_push($sheets, new summaryTabExport($this->labels, $this->sheets[$this->auxSheets[$a]], $this->auxSheets[$a], $this->sheets, $this->typeExport));
        }

    	return $sheets;
    }

    public function title(): string{
        return $this->title;
    }
}
