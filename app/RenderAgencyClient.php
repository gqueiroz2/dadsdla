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

    public function somekind(){

    	echo "<select class='form-control' name='table_end' data-width='100%'>";
    		echo "<option value=''> Select a Table </option>";
    		echo "<option value='base'> Base </option>";
    		echo "<option value='group'> Group </option>";
    		echo "<option value='unit'> Unit </option>";
		echo "</select>";

    }
}
