<?php

namespace App;

use Illuminate\Support\Facades\Request;
use App\Management;
use App\sql;

class header extends Management
{
    public function get($con){       
    
        $sql = new sql();

        $table = 'header h';

        $columns = "h.ID AS 'id',
                    r.name AS 'campaignRegion',
                    r.name AS 'Salesregion',
                    b.name AS 'brand',
                    sr.name AS 'salesRep',
                    "
                    ;
    }  
}
