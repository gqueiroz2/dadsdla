<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Render;

class renderDashboards extends Render{
    
    public function baseFilter(){
    	echo "<select id='baseFilter' name='baseFilter' style='width:100%;' class='selectpicker' data-live-search='true'>";
            echo "<option value=''> Select Region </option>";

    	echo "</select>";
    }

    public function secondaryFilter(){
    	echo "<select id='secondaryFilter' name='secondaryFilter[]' style='width:100%;' class='selectpicker' data-live-search='true' multiple='true' multiple data-actions-box='true' data-selected-text-format='count'>";
            echo "<option value='' selected='true'> Select Region </option>";

    	echo "</select>";
    }


}
