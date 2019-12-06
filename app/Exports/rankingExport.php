<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class rankingExport implements FromArray, WithMultipleSheets {
    
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
    	
    	$sheets = array();

    	array_push($sheets, new allRankingExport($this->labels[0], $this->sheets));

    	if (isset($this->sheets['subMtx'])) {
    		$names = array("region" => $this->sheets['region'], "currency" => $this->sheets['currency'], 'value' => $this->sheets['value'], "head" => $this->sheets['names'], 'type' => $this->sheets['type'], 'years' => $this->sheets['years'], "subType" => $this->sheets['subType']);

	    	for ($i=0; $i < sizeof($this->sheets['subMtx']); $i++) { 
	    		array_push($sheets, new rankingTabExport($this->labels[1], $this->sheets['subMtx'][$i], $this->sheets['subTotal'][$i], $this->sheets['type2'][$i], $names));
	    	}	
    	}

    	return $sheets;
    }
}
