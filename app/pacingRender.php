<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class pacingRender extends Render{
    
    protected $month = array('Jan','Feb','Mar','Q1','Apr','May','Jun','Q2','Jul','Aug','Sep','Q3','Oct','Nov','Dec','Q4');

	public function pacingReport($brands){
		 echo "<div class='table-responsive' style='zoom:80%;'>
            <table style=' border:solid; width:100%; text-align:center; border-width:1px; font-size:25px;'>
                <tr><th class='lightBlue'> Pacing Report </th></tr>
            </table>
        </div>";

        echo "<br>";

        echo "<div class='row'>";
        	echo "<div class='col linked table-responsive '>";
            echo "<table style='  width:100%; text-align:center; min-width:2000px; >";
        		echo "<tr>";
        			echo "<td style='width:5%;'>&nbsp</td>";
        			echo "<td style='width:5%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px; height:40px;'>&nbsp</td>";
            	for ($m=0; $m <sizeof($this->month) ; $m++) { 
            		if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                        echo "<td class='quarter' style='width:3.9%; border-style:solid; border-color:black; border-width: 1px 1px 1px 1px; height:40px;'>".$this->month[$m]."</td>";
                    }else{
                        echo "<td class='smBlue' style='width:3.9%; border-style:solid; border-color:black; border-width: 1px 0px 1px 0px; height:40px;'>".$this->month[$m]."</td>";
                    }
            	}
        			echo "<td class='darkBlue' style='width:5%; border-style:solid; border-color:black; border-width: 1px 1px 1px 0px; height:40px;'>Total</td>";
        		echo "</tr>";
            echo "</table>";
        	echo "</div>";
        echo "</div>";
        echo "<div class='row'>";
        	echo "<div class='col linked table-responsive '>";
        	for ($c=0; $c <sizeof($brands) ; $c++) { 
        		echo "<table style='  width:100%; text-align:center; min-width:2000px; >";
    	    		echo "<tr>";
        				echo "<td rowspan='11' style='width:5%; border-style:solid; border-color:black; border-width: 1px 0px 1px 0px; height:40px;'>".$brands[$c]['name']."</td>";
	        		echo "</tr>";
            	echo "</table>";
           	}
            	
        	echo "</div>";
        echo "</div>";
	}

}
