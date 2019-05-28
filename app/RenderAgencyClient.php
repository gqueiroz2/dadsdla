<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RenderAgencyClient extends Model
{
    public function type(){

    	echo "<select class='form-control' id='type' name='type' data-width='100%'>";
    		echo "<option value=''> Select a Type of Excel </option>";
    		echo "<option value='agency'> Agency </option>";
    		echo "<option value='client'> Client </option>";
		echo "</select>";

    }
}
