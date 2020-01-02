<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;

class rankingExport implements FromArray, WithMultipleSheets, WithTitle {
    
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
    	
    	$sheets = array();

    	array_push($sheets, new allRankingExport($this->labels[0], $this->sheets, $this->typeExport));

    	if (isset($this->sheets['subMtx'])) {
    		$names = array("region" => $this->sheets['region'], "currency" => $this->sheets['currency'], 'value' => $this->sheets['value'], "head" => $this->sheets['names'], 'type' => $this->sheets['type'], 'years' => $this->sheets['years'], "subType" => $this->sheets['subType']);

	    	for ($i=0; $i < sizeof($this->sheets['subMtx']); $i++) { 
	    		array_push($sheets, new rankingTabExport($this->labels[1], $this->sheets['subMtx'][$i], $this->sheets['subTotal'][$i], $this->sheets['type2'][$i], $names, $this->typeExport));
	    	}	
    	}

    	return $sheets;
    }

    public function title(): string{
        return $this->title;
    }
}
