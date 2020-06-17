<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Render;
use App\subRankings;
use App\dashboards;

class renderDashboards extends Render{
    
    public function assembler($con,$handler,$type,$baseFilter,$secondaryFilter,$flow,$currencyView,$valueView){
        $sr = new subRankings();

        $cYear = intval(date("Y"));
        $pYear = $cYear - 1;
        $ppYear = $pYear - 1;
        $years = array($cYear,$pYear,$ppYear);
        if($type != "agencyGroup"){
            $showType = ucfirst($type);
        }else{
            $showType = "Agency Group";
        }

        $last3YearsRoot = $handler['last3YearsRoot'];
        $last3YearsChild = $handler['last3YearsChild'];
        $last3YearsByMonth = $handler['last3YearsByMonth'];
        $last3YearsByBrand = $handler['last3YearsByBrand'];
        $last3YearsByProduct = $handler['last3YearsByProduct'];

        //Linha do titulo
        echo "<div class='row justify-content-center mt-3' style='margin-right: 0.3%; margin-left: 0.3%;'>";
            echo "<div class='col lightBlue' align='center'>";
                echo "<span style='font-size:22px;'> $showType : ".$baseFilter->$type.": ".$currencyView."/".$valueView."</span>";
            echo "</div>";
        echo "</div>";


        //linha do Ranking

        //var_dump($showType);
        //var_dump($baseFilter);
        //var_dump($last3YearsRoot);
        //var_dump($years);

        echo "<div class='row justify-content-center mt-3' style='margin-right: 0.3%; margin-left: 0.3%;'>";
            echo "<div class='col' align='center'>";
                echo "<span style='width:100%;'>".$this->renderLast3Root($type,$showType,$baseFilter,$last3YearsRoot,$years)."</span>";
            echo "</div>";
        echo "</div>";


        if ($type == 'agency' || $type == "agencyGroup") {
            echo "<div class='row justify-content-center mt-2' style='margin-right: 0.3%; margin-left: 0.3%; min-height:250px;'>";
                echo "<div class='col' align='center'>";
                    echo "<div style='width:100%;' id='overviewChildChart' class='graphInner'> GRAFICO VALORES DOS ULTIMOS 3 ANOS DO TIPO CHILD </div>";
                echo "</div>";
            echo "</div>";

            echo "<div class='row justify-content-center mt-2' style='margin-right: 0.3%; margin-left: 0.3%;'>";
                echo "<div class='col' align='center'>";
                    echo "<span style='width:100%;'> ".$this->renderLast3Child($sr,$last3YearsChild,$years,$type)." </span>";
                echo "</div>";
            echo "</div>";  
        }
        

        echo "<div class='row justify-content-center mt-2' style='margin-right: 0.3%; margin-left: 0.3%; min-height:300px;'>";
            echo "<div class='col-3' align='center'>";
                echo "<span style='width:100%;'> ".$this->renderLast3ByMonth($last3YearsByMonth,$years,$type)." </span>";
            echo "</div>";
            echo "<div class='col-9' align='center' style='min-width:300px;'>";
                echo "<div style='width:100%;' id='overviewMonthChart' class='graphInner'> GRAFICO VALORES DOS ULTIMOS 3 ANOS POR MES </div>";
            echo "</div>";
        echo "</div>";

        echo "<div class='row justify-content-center mt-2' style='margin-right: 0.3%; margin-left: 0.3%; min-height:250px;'>";
            echo "<div class='col' style='margin-top:1.5%;'>";
                echo "<div class='container-fluid' style='margin:0px !important; padding:0px !important;'>";
                    
                    echo "<span style='width:100%;'> ".$this->renderLast3ByBrand($con,$last3YearsByBrand,$years,$type)." </span>";

                echo "</div>";
            echo "</div>";

            for ($y=0; $y < sizeof($years); $y++) { 
                
                echo "<div class='col' style='margin-top:1.5%;'>";
                    echo "<div class='container-fluid' style='margin:0px !important; padding:0px !important;'>";
                        
                        echo "<div class='row'>";
                            echo "<div class='col' align='center'>";
                                echo "<span><b>".$years[$y]."</b></span>";
                            echo "</div>";  
                        echo "</div>"; 

                        echo "<div class='row'>";
                            echo "<div class='col'>";
                                echo "<div id=\"overviewBrandChart".$flow[$y]."\" class='graphInner'> GRAFICO VALORES DOS ULTIMOS 3 ANOS POR MARCA </div>";
                            echo "</div>";  
                        echo "</div>";

                    echo "</div>";
                echo "</div>";
            } 

        echo "</div>";



        /*echo "<div class='row justify-content-center mt-2' style='margin-right: 0.1%; margin-left: 0.1%; min-height:250px;'>";
            echo "<div class='col' align='center'>";
                echo "<span style='width:100%;'> ".$this->renderLast3ByBrand($con,$last3YearsByBrand,$years,$type)." </span>";
            echo "</div>";
        echo "</div>";*/
      
        //echo "</div>";

        //echo "<div class='row justify-content-center mt-2' style='margin-right: 0.3%; margin-left: 0.3%;'>";
            
        echo "</div>";


        


    }

    public function renderLast3ByProduct($con,$l3BP,$years,$type){
        $tmp = $l3BP;

        $products = $l3BP['products'];
        $values = $l3BP['values'];
        if ($type != "client") {
            $mtx = $this->assembleProductsMatrix2($values,$products,$years);
        }else{
            $mtx = $this->assembleProductsMatrix($values,$products,$years);
        }

        $this->renderlast3ProductTable($mtx,$years,$type);

    }

    public function assembleProductsMatrix($values,$products,$years){
       
        for ($p=0; $p < sizeof($products); $p++) { 
            
            $mtx[$p][0] = $products[$p]['product'];
            for ($q=0; $q < sizeof($years); $q++) {                
                $mtx[$p][$q+1] = $values[$q][$p]; 
            }
        }

        $sort = array();
        foreach($mtx as $k=>$v) {
            $sort['cYear'][$k] = $v[1];
            $sort['pYear'][$k] = $v[2];
            $sort['ppYear'][$k] = $v[3];
        }
        # sort by event_type desc and then title asc
        array_multisort($sort['cYear'], SORT_DESC, 
                        $sort['pYear'], SORT_DESC,
                        $sort['ppYear'], SORT_DESC,
                        $mtx);

        $last = sizeof($mtx);

        

        $ttCYear = 0.0;
        $ttPYear = 0.0;
        $ttPPYear = 0.0;

        for ($i=0; $i < sizeof($mtx); $i++) {             

            for ($j=0; $j < sizeof($mtx[$i]); $j++) { 
                
                if($j == 1){
                    $ttCYear += $mtx[$i][$j];
                }elseif($j == 2){
                    $ttPYear += $mtx[$i][$j];
                }elseif($j == 3){
                    $ttPPYear += $mtx[$i][$j];
                }

            }
        }
        $mtx[$last][0] = "Total";
        $mtx[$last][1] = $ttCYear;
        $mtx[$last][2] = $ttPYear;
        $mtx[$last][3] = $ttPPYear;
        return $mtx;

    }

    public function assembleProductsMatrix2($values,$products,$years){
       
        for ($p=0; $p < sizeof($products); $p++) { 
            
            $mtx[$p][0] = $products[$p]['product'];
            $mtx[$p][1] = $products[$p]['client'];
            for ($q=0; $q < sizeof($years); $q++) {                
                $mtx[$p][$q+2] = $values[$q][$p]; 
            }
        }

        $sort = array();
        foreach($mtx as $k=>$v) {
            $sort['cYear'][$k] = $v[2];
            $sort['pYear'][$k] = $v[3];
            $sort['ppYear'][$k] = $v[4];
        }
        # sort by event_type desc and then title asc
        array_multisort($sort['cYear'], SORT_DESC, 
                        $sort['pYear'], SORT_DESC,
                        $sort['ppYear'], SORT_DESC,
                        $mtx);

        $last = sizeof($mtx);

        

        $ttCYear = 0.0;
        $ttPYear = 0.0;
        $ttPPYear = 0.0;

        for ($i=0; $i < sizeof($mtx); $i++) {             

            for ($j=0; $j < sizeof($mtx[$i]); $j++) { 
                
                if($j == 2){
                    $ttCYear += $mtx[$i][$j];
                }elseif($j == 3){
                    $ttPYear += $mtx[$i][$j];
                }elseif($j == 4){
                    $ttPPYear += $mtx[$i][$j];
                }

            }
        }
        $mtx[$last][0] = "Total";
        $mtx[$last][1] = "&nbsp";
        $mtx[$last][2] = $ttCYear;
        $mtx[$last][3] = $ttPYear;
        $mtx[$last][4] = $ttPPYear;
        
        return $mtx;

    }

    public function renderlast3ProductTable($mtx,$years,$type){
        echo "<table style='width:100%; border: 1px solid black; font-size:14px;'>";
        echo "<tr>";
            echo "<td class='lightBlue' style='width:25%;'> Product </td>";
            if ($type != "client") {   
                echo "<td class='lightBlue' style='width:25%;'> Client </td>";
            }
            for ($y=0; $y < sizeof($years); $y++) { 
                echo "<td class='lightBlue'>".$years[$y]."</td>";    
            }
            
        echo "</tr>";

        for ($p=0; $p < sizeof($mtx); $p++) { 
            if($mtx[$p][0] == "Total"){
                $something = "darkBlue";
            }else{
                if($p%2 == 0){
                    $something = "medBlue";
                }else{
                    $something = "rcBlue";
                }
            }
            echo "<tr>";                
            for ($q=0; $q < sizeof($mtx[$p]); $q++) {                 
                

                if(is_numeric($mtx[$p][$q])){
                    if($mtx[$p][$q] > 0){
                        echo "<td class='$something'>". number_format( $mtx[$p][$q] )."</td>";               
                    }else{
                        echo "<td class='$something'> - </td>";                 
                    }
                }else{
                    echo "<td class='$something'>". $mtx[$p][$q] ."</td>";                
                }
            }
            echo "</tr>";
        }
        echo "<table>";
    }

    public function renderLast3ByBrand($con,$l3BB,$years,$type){
        $this->renderlast3BrandTable($con,$l3BB,$years);

    }

    public function renderlast3BrandTable($con,$l3BB,$years){
        $dash = new dashboards();
        $brands = $dash->getBrands($con);
        for ($y=0; $y < sizeof($years); $y++){
            $l3BBTotal[$y] = 0.0;
            for ($b=0; $b < sizeof($brands); $b++){ 
                $l3BBTotal[$y] += $l3BB[$y][$b];
            }
        }

        echo "<table style='width:100%; border: 1px solid black; font-size:14px; text-align:center;'>";
            echo "<tr>";
                echo "<td class='lightBlue'>";
                    echo "<span> Brand </span>";
                echo "</td>"; 
                for ($y=0; $y < sizeof($years); $y++){ 
                    echo "<td class='lightBlue'>";
                        echo "<span> ".$years[$y]." </span>";
                    echo "</td>";
                }    
            echo "</tr>"; 
            for ($b=0; $b < sizeof($brands); $b++){ 
                if($b%2 ==0){
                    $something = "medBlue";
                }else{
                    $something = "rcBlue";
                }
                echo "<tr>";
                    echo "<td class='$something'>";
                        echo "<span> ".$brands[$b][1]." </span>";
                    echo "</td>";
                    for ($y=0; $y < sizeof($years); $y++){ 
                        echo "<td class='$something'>";
                            if($l3BB[$y][$b] > 0){
                                echo "<span> ".number_format($l3BB[$y][$b])." </span>";
                            }else{
                                echo "<span> - </span>";
                            }
                        echo "</td>";
                    }
                echo "</tr>"; 
            }   
            echo "<tr>";
                echo "<td class='darkBlue'>";
                    echo "<span> Total </span>";
                echo "</td>";
                for ($y=0; $y < sizeof($years); $y++){ 
                    echo "<td class='darkBlue'>";
                        if($l3BBTotal[$y] > 0){
                            echo "<span> ".number_format($l3BBTotal[$y])." </span>";
                        }else{
                            echo "<span> - </span>";
                        }
                    echo "</td>";
                }
            echo "</tr>"; 
        echo "</table>";
    }

    public function renderLast3ByMonth($l3BM,$years,$type){
        $this->renderlast3MonthTable($l3BM,$years);

    }

    public function renderlast3MonthTable($l3BM,$years){
        $dash = new dashboards();
        $months = $dash->getMonths();
        $monthsFN = $dash->getMonthsFullName();

        for ($y=0; $y < sizeof($years); $y++){
            $l3BMTotal[$y] = 0.0;
            for ($m=0; $m < sizeof($months); $m++){ 
                $l3BMTotal[$y] += $l3BM[$y][$m];
            }
        }

        echo "<table style='width:100%;border: 1px solid black; font-size:14px; text-align:center;'>";
            echo "<tr'>";
                echo "<td class='lightBlue'>";
                    echo "<span> Month </span>";
                echo "</td>"; 
                for ($y=0; $y < sizeof($years); $y++){ 
                    echo "<td class='lightBlue'>";
                        echo "<span> ".$years[$y]." </span>";
                    echo "</td>";
                }    
            echo "</tr>"; 
            for ($m=0; $m < sizeof($months); $m++){ 
                if($m%2 ==0){
                    $something = "medBlue";
                }else{
                    $something = "rcBlue";
                }
                echo "<tr>";
                    echo "<td class='$something'>";
                        echo "<span> ".$monthsFN[$m]." </span>";
                    echo "</td>";
                    for ($y=0; $y < sizeof($years); $y++){ 
                        echo "<td class='$something'>";
                            if($l3BM[$y][$m] > 0){
                                echo "<span> ".number_format($l3BM[$y][$m])." </span>";
                            }else{
                                echo "<span> - </span>";
                            }
                        echo "</td>";
                    }
                echo "</tr>"; 
            }   
            echo "<tr>";
                echo "<td class='darkBlue'>";
                    echo "<span> Total </span>";
                echo "</td>";
                for ($y=0; $y < sizeof($years); $y++){ 
                    echo "<td class='darkBlue'>";
                        if($l3BMTotal[$y] > 0){
                            echo "<span> ".number_format($l3BMTotal[$y])." </span>";
                        }else{
                            echo "<span> - </span>";
                        }
                    echo "</td>";
                }
            echo "</tr>"; 
        echo "</table>";

    }

    public function renderLast3Root($type,$showType,$baseFilter,$l3R,$years){
        
  
                    for ($l=0; $l < sizeof($l3R); $l++) { 
                        echo "<table style='width: 100%; zoom:100%; font-size: 14px;border: 1px solid black;'>";
                        for ($n=0; $n < sizeof($l3R[$l][0]); $n++) {
                            echo "<tr style='text-align:center;'>";
                            for ($m=0; $m < sizeof($l3R[$l]); $m++) { 
                                if($n == 0){
                                    $clr = "lightBlue";
                                    echo "<td class='$clr'>".$l3R[$l][$m][$n]."</td>";                    
                                }else{
                                    $clr = "medBlue";
                                    if($m >= 0 && $m <=2 ){
                                        echo "<td class='$clr' >".$l3R[$l][$m][$n]."°</td>";                    
                                    }else{
                                        if(is_numeric($l3R[$l][$m][$n])){
                                            echo "<td class='$clr'>".number_format($l3R[$l][$m][$n])."</td>";                    
                                        }else{
                                            echo "<td class='$clr'>".$l3R[$l][$m][$n]."</td>";                    
                                        }
                                    }

                                }
                            }   
                            echo "</tr>";                 
                        }
                        echo "</table>";                    
                    }

        
    
        /*
        echo "<div class='container-fluid' style='margin:0px !important; padding:0px !important;'>";
            echo "<div class='row'>";
                echo "<div class='col-3 lightBlue'>";
                    echo "<span> $showType </span>";
                echo "</div>";  
                for ($y=0; $y < sizeof($years); $y++) { 
                    echo "<div class='col-3 lightBlue'>";
                        echo "<span> ".$years[$y]." </span>";
                    echo "</div>";
                }  
            echo "</div>";    
            echo "<div class='row'>";
                echo "<div class='col-3 medBlue'>";
                    echo "<span> ".$baseFilter->$type." </span>";
                echo "</div>";  
                for ($y=0; $y < sizeof($years); $y++) { 
                    echo "<div class='col-3 medBlue'>";
                        echo "<span> ".number_format($l3R[$y])." </span>";
                    echo "</div>";
                }  
            echo "</div>";    
        echo "</div>";
        */
    }

    public function renderLast3Child($sr,$l3C,$years,$type){
        $temp = $sr->assembler($l3C,$years,$type);
        
        $mtx = $temp[0];
        $total = $temp[1];

        if ($type == "agencyGroup") {
            $newType = "agency";
        }elseif ($type == "agency") {
            $newType = "client";
        }else{
            $newType = "client";
        }

        $this->render($mtx, $total, $newType, sizeof($mtx[0]),$years);

        
    }

    public function render($mtx, $total, $type, $size ,$years){
        $sr = new subRankings();
        echo "<div class='container-fluid' style='margin:0px !important; padding:0px !important;'>";
            echo "<div class='row mt-2 mb-2 justify-content-center'>";
                echo "<div class='col'>";
                    echo "<table style='width: 100%; zoom:100%; font-size: 14px;border: 1px solid black;'>";
                        $sr->renderAssembler($mtx, $total, $type, $size , $years);
                    echo "</table>";
               echo "</div>";
           echo "</div>";
       echo "</div>";
    }
/*
    public function renderAssembler($mtx, $total, $type, $size ,$years){
        for ($m=0; $m < $size; $m++) {
            echo "<div class='row'>";
          
            if ($m == 0) {
              $color = "lightBlue";
            }elseif ($m%2 != 0) {
              $color = "rcBlue";
            }else{
              $color = "medBlue";
            }

            for ($i=0; $i < sizeof($mtx); $i++) {
                if ($m == 0) {
                    if($i >= 0 && $i <= 2){
                        $sz = 1;
                    }elseif($i == 3){
                        $sz = 1;
                    }elseif($i == 7 || $i == 8){
                        $sz = 1;
                    }else{
                        $sz = 2;
                    }

                        echo "<div class='col-$sz $color center'> ".$mtx[$i][$m]." </div>";
                }else {
                    if (!is_numeric($mtx[$i][$m])) {
                        if ($mtx[$i][$m] != "-") {
                            if ($type == "agency" && $mtx[$i][0] == "Agency") {
                                echo "<div class='col-1' id='".$type.$m."' class='$color center'>"
                                        .$mtx[$i][$m].
                                     "</div>";
                            }elseif ($type == "agencyGroup" && $mtx[$i][0] == "Agency Group") {
                                echo "<div class='col-1' id='".$type.$m."' class='$color center'>"
                                        .$mtx[$i][$m].
                                     "</div>";
                            }else{
                                if ($mtx[$i][$m] == "Others") {
                                    echo "<div class='col-1 $color center'> - </div>";      
                                }else{
                                    echo "<div class='col-1 $color center'> ".$mtx[$i][$m]." </div>";
                                }
                            }
                        }else{
                            if( $mtx[$i][0] == "Pos. ".$years[0] ||
                                $mtx[$i][0] == "Pos. ".$years[1] ||
                                $mtx[$i][0] == "Pos. ".$years[2] ||
                                $mtx[$i][0] == "VAR %" ||
                                $mtx[$i][0] == "VAR ABS."){
                                echo "<div class='col-1 $color center'> ".$mtx[$i][$m]." </div>";
                            }else{
                                echo "<div class='col-2 $color center'> ".$mtx[$i][$m]." </div>";
                            }
                        }
                    }else{
                        if (substr($mtx[$i][0], 0, 3) == "Pos") {
                            if ($mtx[$i][$m] != '-') {
                                echo "<div class='col-1 $color center'> ".$mtx[$i][$m]."º </div>";
                            }else{
                                echo "<div class='col-1 $color center'> ".$mtx[$i][$m]." </div>";
                            }
                        }else{
                            if($mtx[$i][0] == "VAR %" ||$mtx[$i][0] == "VAR ABS."){
                                echo "<div class='col-1 $color center'> ".number_format($mtx[$i][$m])." </div>";        
                            }else{
                                echo "<div class='col-2 $color center'> ".number_format($mtx[$i][$m])." </div>";        
                            }
                            
                        }
                        
                    }
                }
                
            }

            echo "</div>";
            
            if ($type != "client") {
                echo "<div class='row $color'>";
                    echo "<div class='col' id='sub".$type.$m."' style='display: none' colspan='".sizeof($mtx)."'></div>"; 
                echo "</div>";
            }
        }

        echo "<div class='row'>";

        for ($t=0; $t < sizeof($total); $t++) { 
            
            if (is_numeric($total[$t])) {
                echo "<div class='col darkBlue center'> ".number_format($total[$t])." </div>"; 
            }else{
              if ($total[$t] != "-") {
                echo "<div class='col darkBlue center'> ".$total[$t]." </div>"; 
              }else{
                echo "<div class='col darkBlue center'> &nbsp; </div>"; 
              }
            }

        }

        echo "</div>";

    }
*/

    public function baseFilter(){
    	echo "<select id='baseFilter' name='baseFilter' data-width='100%;' class='selectpicker' data-live-search='true'>";
            echo "<option value=''> Select Region </option>";
    	echo "</select>";
    }

    public function secondaryFilter(){
    	echo "<select id='secondaryFilter' name='secondaryFilter[]' style='width:100%;' class='selectpicker' data-live-search='true' multiple='true' multiple data-actions-box='true' data-selected-text-format='count'>";
            echo "<option value='' selected='true'> Select Region </option>";

    	echo "</select>";
    }


}
