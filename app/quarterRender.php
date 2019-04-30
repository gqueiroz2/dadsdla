<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Render;

class quarterRender extends Render
{
    public function quarters(){
    	echo "<select value='quarter' multiple='true'>";
    		echo "<option value='all'>All</option>";
    		echo "<option value='1'>Q1</option>";
    		echo "<option value='2'>Q2</option>";
    		echo "<option value='3'>Q3</option>";
    		echo "<option value='4'>Q4</option>";
    	echo "</select>";
    }

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
