<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class coreExport implements FromArray, WithMultipleSheets {
    
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
    	
		$sheets = [
    		new ytdExport($this->sheets['ytd'], $this->report[0]),
    		new digitalExport($this->sheets['digital'], $this->report[1]),
    		new planBySalesExport($this->sheets['sales'], $this->report[2])
    	];

    	return $sheets;
    	
    }
}
