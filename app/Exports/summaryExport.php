<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class summaryExport implements FromArray, WithMultipleSheets {
    
	protected $sheets;
    protected $region;
    protected $year;

	public function __construct(array $sheets, $region, $year){
		$this->sheets = $sheets;
        $this->region = $region;
        $this->year = $year;
	}

    public function array(): array {

        return $this->sheets;
    }

    public function sheets(): array{
    	
    	if ($this->region == "Brazil") {
    		
    		$sheets = [
	    		new cmapsExport($this->sheets['cmaps'], $this->year),
	    		new digitalExport($this->sheets['digital'], $this->region, $this->year),
	    		new planByBrandExport($this->sheets['plan'], $this->region, $this->year)
	    	];

    	}else{
    		
    		$sheets = [
	    		new ytdExport($this->sheets['ytd'], $this->region, $this->year),
	    		new digitalExport($this->sheets['digital'], $this->region, $this->year),
	    		new planByBrandExport($this->sheets['plan'], $this->region, $this->year)
	    	];
	    	
    	}

    	return $sheets;
    	
    }
}
