<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToArray;

class testImport implements ToCollection{

    public function collection(Collection $rows){
        foreach ($rows as $row) {
        	
        }
    }
}
