<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Render;

class renderDashboards extends Render{
    
    public function baseFilter(){
    	echo "<select id='baseFilter' name='baseFilter' style='width:100%;' class='form-control'>";
            echo "<option value=''> Select Type </option>";

    	echo "</select>";
    }

    public function secondaryFilter(){
    	echo "<select id='secondaryFilter' name='secondaryFilter' style='width:100%;' class='form-control'>";
            echo "<option value=''> Select Type </option>";

    	echo "</select>";
    }


}
