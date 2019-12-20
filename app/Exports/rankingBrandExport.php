<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;

class rankingBrandExport implements FromArray, WithMultipleSheets, WithTitle {

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
    	array_push($sheets, new allBrandsExport($this->labels[0], $this->sheets, $this->typeExport));

    	$names = array("region" => $this->sheets['region'], 'currency' => $this->sheets['currency'], 'value' => $this->sheets['value']);

		for ($b=0; $b < sizeof($this->sheets['brand']); $b++) { 
			array_push($sheets, new brandExport($this->labels[1],$this->sheets['brandsMtx'][$b], $this->sheets['brandsTotal'][$b], $this->sheets['type'], $this->sheets['brand'][$b][1], $names, $this->typeExport));
		}

    	return $sheets;
    }

    public function title(): string{
        return $this->title;
    }

}
