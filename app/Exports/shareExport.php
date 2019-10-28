<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class shareExport implements FromArray, WithMultipleSheets {
    
    protected $sheets;
    protected $report;
    protected $region;

    public function __construct(array $sheets, $report, $region){
		$this->sheets = $sheets;
        $this->report = $report;
        $this->region = $region;
	}

    public function array(): array {

        return $this->sheets;
    }

    public function sheets(): array{
    	
    	if (isset($this->sheets['cmaps'])) {
    		
    		$sheets = [
	    		new cmapsExport($this->sheets['cmaps'], $this->report[0]),
	    		new digitalExport($this->sheets['digital'], $this->report[1]),
	    	];

    	}else{
    		
    		$sheets = [
	    		new ytdExport($this->sheets['ytd'], $this->report[0]),
	    		new digitalExport($this->sheets['digital'], $this->report[1]),
	    	];
	    	
    	}

    	return $sheets;
    	
    }
}
