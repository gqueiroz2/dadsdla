<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;

class insightsExport implements FromArray, WithMultipleSheets, WithTitle {

    protected $sheets;
    protected $labels;
    protected $title;

    public function __construct(array $sheets, $labels, $title){
        $this->sheets = $sheets;
        $this->labels = $labels;
        $this->title = $title;
    }

    public function array(): array {

        return $this->sheets;
    }

    public function sheets(): array{
        
        $sheet = [
            new viewerInsightsTabExport($this->labels, $this->sheets)
        ];

        return $sheet;
    }

    public function title(): string{
        return $this->title;
    }
}
