<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Render;

class monthlyRender extends Render
{ 
    public function firstPos(){
    	echo "<select name='firstPos'>";
    		echo "<option value=''> Select </option>";
    		echo "<option value='planbybrand'> Plan by Brand </option>";
    	echo "</select>";
    }

    public function secondPos(){
    	echo "<select name='secondPos'>";
    		echo "<option value=''> Select </option>";
    		echo "<option value='IBMS'> IBMS </option>";
    		echo "<option value='CMAPS'> CMAPS </option>";
    		echo "<option value='Header'> Header </option>";//somente se for brasil a região selecionada
    	echo "</select>";
    }
}
