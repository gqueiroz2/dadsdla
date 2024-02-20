<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Render;

class RenderChain extends Render{
    
    public function yearMultiple(){
    	$cMonth = date('m');
        
        $cYear = intval( date('Y') );
        $nYear = $cYear + 1  ;
        $pYear = $cYear -1;
        $ppYear = $pYear -1;
        $pppYear = $ppYear -1;

        if($cMonth == 12){
            $year = array($cYear,$nYear,$pYear,$ppYear,$pppYear);
        }else{
            $year = array($cYear,$pYear,$ppYear,$pppYear);
        }

    	echo "<select class='selectpicker' data-selected-text-format='count' multiple='true' name='year[]' multiple data-actions-box='true' data-size='3 ' data-width='100%'>";
			for ($y=0; $y < sizeof($year); $y++) { 
                if($y == 0){
                    echo "<option value='".$year[$y]."' selected='true'>".$year[$y]."</option>";    
                }else{  
                    echo "<option value='".$year[$y]."'>".$year[$y]."</option>";    
                }
            }
		echo "</select>";
    	
    }

    public function year(){
        $cMonth = date('m');

        $cYear = intval( date('Y') );
        $nYear = $cYear + 1  ;

        $pYear = $cYear -1;
        $ppYear = $pYear -1;
        $pppYear = $ppYear -1;

        if($cMonth == 12){
            $year = array($cYear,$nYear,$pYear,$ppYear,$pppYear);
        }else{
            $year = array($cYear,$pYear,$ppYear,$pppYear);
        }

    	echo "<select class='form-control' name='year' data-width='100%'>";
			for ($y=0; $y < sizeof($year); $y++) { 
                if($y == 0){
                    echo "<option value='".$year[$y]."' selected='true'>".$year[$y]."</option>";    
                }else{  
                    echo "<option value='".$year[$y]."'>".$year[$y]."</option>";    
                }
            }
		echo "</select>";
    	
    }

    public function report(){

    	echo "<select class='form-control' name='table' id='tableToCheck' data-width='100%'>";
            echo "<option value='data_hub'> DATA HUB </option>";            
            echo "<option value='aleph'> ALEPH </option>";            
            //echo "<option value='sf_pr'> SF P&R </option>";
            //echo "<option value='sf_pr_brand'> SF P&R BRAND </option>";            
            echo "<option value='wbd'> WBD </option>";
            echo "<option value='wbd_bv'> WBD AVB </option>";
            //echo "<option value='ytdFN'> YTD FN </option>";
            //echo "<option value='fw_digital'> FW Digital </option>";
            //echo "<option value='insights'> INSIGHTS </option>";           
		echo "</select>";

    }

    public function table($name){

    	echo "<select class='form-control' name='$name' data-width='100%'>";
    		echo "<option value=''> Select </option>";
    		echo "<option value='aleph'> ALEPH </option>";            
            echo "<option value='data_hub'> DATA HUB </option>";
            //echo "<option value='sf_pr'> SF P&R </option>";
            //echo "<option value='sf_pr_brand'> SF P&R BRAND </option>";
            echo "<option value='wbd'> WBD </option>";
            echo "<option value='wbd_bv'> WBD AVB </option>";
            //echo "<option value='ytdFN'> YTD FN </option>";
			//echo "<option value='ytd'> YTD </option>";
            //echo "<option value='fw_digital'> FW Digital </option>";
            //echo "<option value='insights'> INSIGHTS </option>";
			//echo "<option value='mini_header'> Mini-Header </option>";
			//echo "<option value='digital'> Digital </option>";
		echo "</select>";

    }

    public function tableCmaps($name){
        echo "<select class='form-control' name='$name' data-width='100%'>";
            echo "<option value='cmaps'> CMAPS </option>";
            echo "<option value='pipeline'> PIPELINE </option>";
        echo "</select>";
    }

    public function dailyResults($name){
        echo "<select class='form-control' name='$name' data-width='100%'>";
            echo "<option value='daily_results'> DAILY RESULTS </option>";
            echo "<option value='pipeline'> PIPELINE </option>";
        echo "</select>";
    }

    public function reportCmaps(){
        echo "<select class='form-control' name='table' id='tableToCheck' data-width='100%'>";
            echo "<option value='cmaps'> CMAPS </option>";  
            echo "<option value='pipeline'> PIPELINE </option>";          
        echo "</select>";
    }

   public function tableinsights($name){
        echo "<select class='form-control' name='$name' data-width='100%'>";
            //echo "<option value='insights'> INSIGHTS </option>";
            echo "<option value='forecast'> T-REX </option>";
        echo "</select>";
    }

    public function reportInsights(){
        echo "<select class='form-control' name='table' id='tableToCheck' data-width='100%'>";
            //echo "<option value='insights'> INSIGHTS </option>";
            echo "<option value='forecast'> T-REX </option>";            
        echo "</select>";
    }

    


}
