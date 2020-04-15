<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Render;


class renderPlanning extends Render{
    
    public function brand($brand){

        array_pop($brand);

        echo "<select id='brand' class='selectpicker' data-selected-text-format='count' multiple='true' name='brand[]' multiple data-actions-box='true' data-size='13' data-width='100%'>";
            for ($i = 0; $i < sizeof($brand); $i++) { 
                if ($brand[$i]["name"] != "DN" || $brand[$i]["name"] != "HO") {
                    $value[$i] = base64_encode(json_encode(array($brand[$i]['id'],$brand[$i]['name'])));
                    echo "<option selected='true' value='".$value[$i]."'>".$brand[$i]["name"]."</option>";   
                }
            }
            
        echo "</select>";
    }
}
