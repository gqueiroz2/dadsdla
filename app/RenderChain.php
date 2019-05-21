<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Render;

class RenderChain extends Render{
    
    public function year(){
    	
    	echo "<select class='selectpicker' data-selected-text-format='count' multiple='true' name='year[]' multiple data-actions-box='true' data-size='3 ' data-width='100%'>";
			echo "<option value='2019' selected='true'> 2019 </option>";
			echo "<option value='2018'> 2018 </option>";
			echo "<option value='2017'> 2017 </option>";
			echo "<option value='2016'> 2016 </option>";
		echo "</select>";
    	
    }
}
