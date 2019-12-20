<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;

class rankingChurnExport implements FromArray, WithMultipleSheets, WithTitle {
    
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

    	array_push($sheets, new allChurnExport($this->labels[0], $this->sheets, $this->typeExport));

    	if (isset($this->sheets['subMtx'])) {
    		$names = array("region" => $this->sheets['region'], "currency" => $this->sheets['currency'], 'value' => $this->sheets['value'], "head" => $this->sheets['headNames'], 'type' => $this->sheets['type'], 'years' => $this->sheets['years'], 'val' => $this->sheets['val']);

	    	for ($i=0; $i < sizeof($this->sheets['subMtx']); $i++) { 
	    		array_push($sheets, new churnExport($this->labels[1], $this->sheets['subMtx'][$i], $this->sheets['subTotal'][$i], $this->sheets['churn'][$i], $names, $this->typeExport));
	    	}	
    	}

    	return $sheets;
    }

    public function title(): string{
        return $this->title;
    }
}
