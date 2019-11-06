<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class rankingBrandExport implements FromArray, WithMultipleSheets {

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
    	array_push($sheets, new allBrandsExport($this->labels[0], $this->sheets));

    	$names = array("region" => $this->sheets['region'], 'currency' => $this->sheets['currency'], 'value' => $this->sheets['value']);

		for ($b=0; $b < sizeof($this->sheets['brand']); $b++) { 
			array_push($sheets, new brandExport($this->labels[1],$this->sheets['brandsMtx'][$b], $this->sheets['brandsTotal'][$b], $this->sheets['type'], $this->sheets['brand'][$b][1], $names));
		}

    	return $sheets;
    	
    }

}
