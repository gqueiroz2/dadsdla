<?php

namespace App\Exports;

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;


class pipelineExport implements FromArray, WithMultipleSheets, WithTitle {
    
    protected $sheets;
    protected $label;
    protected $typeExport;
    protected $title;

    public function __construct(array $sheets, $label, $typeExport, $title){
        $this->sheets = $sheets;
        $this->label = $label;
        $this->typeExport = $typeExport;
        $this->title = $title;
    }

    public function array(): array {
        return $this->sheets;
    }

    public function sheets(): array{
        
        $sheets = [
            new pipelineTabExport($this->label, $this->sheets, $this->typeExport)
        ];

        return $sheets;
    }

    public function title(): string{
        return $this->title;
    }
}
