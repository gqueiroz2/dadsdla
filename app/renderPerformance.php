<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class renderPerformance extends Render
{
    public function coreMatrix($mtx){
    	for ($t=0; $t < sizeof($mtx["values"]); $t++) { 
	    	echo "<table>";
	    		echo "<tr>";
	    		echo "</tr>";
	    	echo "</table>";
    	}
    }
}
