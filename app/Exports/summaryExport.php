<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class summaryExport implements FromArray, WithMultipleSheets {
    
	protected $sheets;
    protected $report;
    protected $region;
    protected $BKGS;

	public function __construct(array $sheets, $report, $region, $BKGS){
		$this->sheets = $sheets;
        $this->report = $report;
        $this->region = $region;
        $this->BKGS = $BKGS;
	}

    public function array(): array {

        return $this->sheets;
    }

    public function sheets(): array{
    	
    	if ($this->region == "Brazil") {
    		
    		$sheets = [
	    		new cmapsExport($this->sheets['cmaps'], $this->report[0], $this->BKGS[0]),
                new ytdExport($this->sheets['pYtd'], $this->report[3], $this->BKGS[1]),
	    		new digitalExport($this->sheets['digital'], $this->report[1]),
	    		new planByBrandExport($this->sheets['plan'], $this->report[2])
	    	];

    	}else{
    		
    		$sheets = [
	    		new ytdExport($this->sheets['ytd'], $this->report[0], $this->BKGS[0]),
                new ytdExport($this->sheets['pYtd'], $this->report[3], $this->BKGS[1]),
	    		new digitalExport($this->sheets['digital'], $this->report[1]),
	    		new planByBrandExport($this->sheets['plan'], $this->report[2])
	    	];
	    	
    	}

    	return $sheets;
    	
    }
}
