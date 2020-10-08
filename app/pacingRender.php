<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class pacingRender extends Render{
    
    protected $month = array('Jan','Feb','Mar','Q1','Apr','May','Jun','Q2','Jul','Aug','Sep','Q3','Oct','Nov','Dec','Q4');

	public function pacingReport($brands,$forRender){

        $cYear = $forRender['cYear'];
        $pYear = $forRender['pYear'];
        $value = $forRender['value'];
        $currency = $forRender['currency'];

        // as variaveis estão sendo declaradas em ordem que aparecem na tabela
        $bookingPYear = $forRender['bookingPYear'];
        $SAPPYear = $forRender['SAPPYear'];
        $target = $forRender['target'];
        $fcst = $forRender['fcst'];
        $corporate = $forRender['corporate'];
        $bookingCYear = $forRender['bookingCYear'];
        $SAPCYear = $forRender['SAPCYear'];
        $prc1 = $forRender['prc1'];
        $prc2 = $forRender['prc2'];

        $totalFcstValue = $forRender['totalFcstValue'];
        $totalActualCYear = $forRender['totalActualCYear'];
        $totalActualPYear = $forRender['totalActualPYear'];
        $totalCorporate = $forRender['totalCorporate'];
        $totalTarget = $forRender['totalTarget'];
        $totalBookingCYear = $forRender['totalBookingCYear'];
        $totalBookingPYear = $forRender['totalBookingPYear'];
        $totalPrc1 = $forRender['totalPrc1'];
        $totalPrc2 = $forRender['totalPrc2'];


		echo "<div class='row'>";
            echo "<div class='col lightBlue'>
                    <center>
                        <span style='font-size:24px;'> 
                            (P&R) Pacing Report - $cYear - ($currency/". strtoupper($value).") 
                        </span>
                    </center>
                  </div>";
        echo "</div>";

        echo "<div class='row sticky-top'>";
            echo "<table style='width: 100%; zoom: 85%;font-size: 16px;'>
                <tr class='center'>
                    <td style='width: 7% !important; background-color: white;'> &nbsp; </td>
                </tr>
            </table>";

            echo "<table style='width: 100%; zoom: 85%;font-size: 16px;'>";
                echo "<tr class='center'>";
                    echo "<td class='darkBlue' style='width:10%;'>DN</td>";
                    for ($m=0; $m <sizeof($this->month); $m++) { 
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td class='quarter' style='width:5%;'>".$this->month[$m]."</td>";
                        }else{
                            echo "<td class='smBlue' style='width:5%;'>".$this->month[$m]."</td>";
                        }
                    }
                    echo "<td class='darkBlue' style='width:5%;'>Total</td>";
                echo "</tr>";
                /* BOOKING ANO PASSADO */
                echo "<tr class='center'>";
                    echo "<td class='odd' style='width:10%;'> $pYear Ad Sales </td>";
                    for ($m=0; $m <sizeof($this->month); $m++) { 
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td class='medBlue' style='width:5%;'>".number_format($totalBookingPYear[$m],0,',','.')."</td>";
                        }else{
                            echo "<td class='odd' style='width:5%;'>".number_format($totalBookingPYear[$m],0,',','.')."</td>";
                        }
                    }
                    echo "<td class='smBlue' style='width:5%;'>".number_format($totalBookingPYear[$m],0,',','.')."</td>";
                echo "</tr>";
                /* SAP ANO PASSADO */
                echo "<tr class='center'>";
                    echo "<td class='even' style='width:10%;'> $pYear SAP </td>";
                    for ($m=0; $m <sizeof($this->month); $m++) { 
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td class='medBlue' style='width:5%;'>".number_format($totalActualPYear[$m],0,',','.')."</td>";
                        }else{
                            echo "<td class='even' style='width:5%;'>".number_format($totalActualPYear[$m],0,',','.')."</td>";
                        }
                    }
                    echo "<td class='smBlue' style='width:5%;'>".number_format($totalActualPYear[$m],0,',','.')."</td>";
                echo "</tr>";
                /* TARGET */
                echo "<tr class='center'>";
                    echo "<td class='odd' style='width:10%;'> Target </td>";
                    for ($m=0; $m <sizeof($this->month); $m++) { 
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td class='medBlue' style='width:5%;'>".number_format($totalTarget[$m],0,',','.')."</td>";
                        }else{
                            echo "<td class='odd' style='width:5%;'>".number_format($totalTarget[$m],0,',','.')."</td>";
                        }
                    }
                    echo "<td class='smBlue' style='width:5%;'>".number_format($totalTarget[$m],0,',','.')."</td>";
                echo "</tr>";
                /* FCST */
                echo "<tr class='center'>";
                    echo "<td class='even' style='width:10%;'> Fcst Ad Sales - Current </td>";
                    for ($m=0; $m <sizeof($this->month); $m++) { 
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td class='medBlue' style='width:5%;'>".number_format($totalFcstValue[$m],0,',','.')."</td>";
                        }else{
                            echo "<td class='even' style='width:5%;'>".number_format($totalFcstValue[$m],0,',','.')."</td>";
                        }
                    }
                    echo "<td class='smBlue' style='width:5%;'>".number_format($totalFcstValue[$m],0,',','.')."</td>";
                echo "</tr>";
                /* Forecast Corporate */
                echo "<tr class='center'>";
                    echo "<td class='odd' style='width:10%;'> Fcst Corporate </td>";
                    for ($m=0; $m <sizeof($this->month); $m++) { 
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td class='medBlue' style='width:5%;'>".number_format($totalCorporate[$m],0,',','.')."</td>";
                        }else{
                            echo "<td class='odd' style='width:5%;'>".number_format($totalCorporate[$m],0,',','.')."</td>";
                        }
                    }
                    echo "<td class='smBlue' style='width:5%;'>".number_format($totalCorporate[$m],0,',','.')."</td>";
                echo "</tr>";
                /* BOOKING ANO ATUAL */
                echo "<tr class='center'>";
                    echo "<td class='even' style='width:10%;'> $cYear Ad Sales </td>";
                    for ($m=0; $m <sizeof($this->month); $m++) { 
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td class='medBlue' style='width:5%;'>".number_format($totalBookingCYear[$m],0,',','.')."</td>";
                        }else{
                            echo "<td class='even' style='width:5%;'>".number_format($totalBookingCYear[$m],0,',','.')."</td>";
                        }
                    }
                    echo "<td class='smBlue' style='width:5%;'>".number_format($totalBookingCYear[$m],0,',','.')."</td>";
                echo "</tr>";
                /* BOOKING ANO ATUAL */
                echo "<tr class='center'>";
                    echo "<td class='odd' style='width:10%;'> $cYear SAP </td>";
                    for ($m=0; $m <sizeof($this->month); $m++) { 
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td class='medBlue' style='width:5%;'>".number_format($totalActualCYear[$m],0,',','.')."</td>";
                        }else{
                            echo "<td class='odd' style='width:5%;'>".number_format($totalActualCYear[$m],0,',','.')."</td>";
                        }
                    }
                    echo "<td class='smBlue' style='width:5%;'>".number_format($totalActualCYear[$m],0,',','.')."</td>";
                echo "</tr>";
                /* PORCENTAGEM ENTRE FCST ATUAL E BOOKING ANO PASSADO */
                echo "<tr class='center'>";
                    echo "<td class='even' style='width:10%;'> % ".$cYear."F/Fcst-$pYear </td>";
                    for ($m=0; $m <sizeof($this->month); $m++) { 
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            if($totalPrc1[$m] != 0 ){
                                echo "<td class='medBlue' style='width:5%;'>".number_format($totalPrc1[$m],0,',','.')."%</td>";
                            }else{
                                echo "<td class='medBlue' style='width:5%;'> - </td>";    
                            }
                        }else{
                            if($totalPrc1[$m] != 0 ){
                                echo "<td class='even' style='width:5%;'>".number_format($totalPrc1[$m],0,',','.')."%</td>";
                            }else{
                                echo "<td class='even' style='width:5%;'> - </td>";    
                            }
                        }
                    }
                    if($totalPrc1[$m] != 0 ){
                        echo "<td class='smBlue' style='width:5%;'>".number_format($totalPrc1[$m],0,',','.')."%</td>";
                    }else{
                        echo "<td class='smBlue' style='width:5%;'> - </td>";
                    }
                echo "</tr>";
                /* PORCENTAGEM ENTRE FCST ATUAL E TARGET */
                echo "<tr class='center'>";
                    echo "<td class='odd' style='width:10%;'> % ".$cYear."F/Target </td>";
                    for ($m=0; $m <sizeof($this->month); $m++) { 
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            if($totalPrc2[$m]){
                                echo "<td class='medBlue' style='width:5%;'>".number_format($totalPrc2[$m],0,',','.')."%</td>";
                            }else{
                                echo "<td class='medBlue' style='width:5%;'> - </td>";
                            }
                        }else{
                            if($totalPrc2[$m]){
                                echo "<td class='odd' style='width:5%;'>".number_format($totalPrc2[$m],0,',','.')."%</td>";
                            }else{
                                echo "<td class='odd' style='width:5%;'> - </td>";
                            }
                        }
                    }
                    if($totalPrc2[$m]){
                        echo "<td class='smBlue' style='width:5%;'>".number_format($totalPrc2[$m],0,',','.')."%</td>";
                    }else{
                        echo "<td class='smBlue' style='width:5%;'> - </td>";
                    }
                echo "</tr>";
            echo "</table>";  

            echo "<table style='width: 100%; zoom: 85%;font-size: 16px;'>
                <tr class='center'>
                    <td style='width: 7% !important; background-color: white;'> &nbsp; </td>
                </tr>
            </table>";

        echo "</div>";

        for ($b=0; $b <sizeof($brands) ; $b++) { 
            echo "<div class='row mt-2'>";                
        		echo "<table style='width: 100%; zoom: 85%;font-size: 16px;'>";
                    echo "<tr class='center'>";
                        echo "<td class='lightBlue' style='width:10%;'>".$brands[$b]['name']."</td>";
                        for ($m=0; $m <sizeof($this->month); $m++) { 
                            if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                                echo "<td class='quarter' style='width:5%;'>".$this->month[$m]."</td>";
                            }else{
                                echo "<td class='smBlue' style='width:5%;'>".$this->month[$m]."</td>";
                            }
                        }
                        echo "<td class='darkBlue' style='width:5%;'>Total</td>";
                    echo "</tr>";
                    /* BOOKING ANO PASSADO */
                    echo "<tr class='center'>";
                        echo "<td class='odd' style='width:10%;'> $pYear Ad Sales </td>";
                        for ($m=0; $m <sizeof($this->month); $m++) { 
                            if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                                echo "<td class='medBlue' style='width:5%;'>".number_format($bookingPYear[$b][$m],0,',','.')."</td>";
                            }else{
                                echo "<td class='odd' style='width:5%;'>".number_format($bookingPYear[$b][$m],0,',','.')."</td>";
                            }
                        }
                        echo "<td class='smBlue' style='width:5%;'>".number_format($bookingPYear[$b][$m],0,',','.')."</td>";
                    echo "</tr>";
                    /* SAP ANO PASSADO */
                    echo "<tr class='center'>";
                        echo "<td class='even' style='width:10%;'> $pYear SAP </td>";
                        for ($m=0; $m <sizeof($this->month); $m++) { 
                            if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                                echo "<td class='medBlue' style='width:5%;'>".number_format($SAPPYear[$b][$m],0,',','.')."</td>";
                            }else{
                                echo "<td class='even' style='width:5%;'>".number_format($SAPPYear[$b][$m],0,',','.')."</td>";
                            }
                        }
                        echo "<td class='smBlue' style='width:5%;'>".number_format($SAPPYear[$b][$m],0,',','.')."</td>";
                    echo "</tr>";
                    /* TARGET */
                    echo "<tr class='center'>";
                        echo "<td class='odd' style='width:10%;'> Target </td>";
                        for ($m=0; $m <sizeof($this->month); $m++) { 
                            if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                                echo "<td class='medBlue' style='width:5%;'>".number_format($target[$b][$m],0,',','.')."</td>";
                            }else{
                                echo "<td class='odd' style='width:5%;'>".number_format($target[$b][$m],0,',','.')."</td>";
                            }
                        }
                        echo "<td class='smBlue' style='width:5%;'>".number_format($target[$b][$m],0,',','.')."</td>";
                    echo "</tr>";
                    /* FCST */
                    echo "<tr class='center'>";
                        echo "<td class='even' style='width:10%;'> Fcst Ad Sales - Current </td>";
                        for ($m=0; $m <sizeof($this->month); $m++) { 
                            if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                                echo "<td class='medBlue' style='width:5%;'>".number_format($fcst[$b][$m],0,',','.')."</td>";
                            }else{
                                echo "<td class='even' style='width:5%;'>".number_format($fcst[$b][$m],0,',','.')."</td>";
                            }
                        }
                        echo "<td class='smBlue' style='width:5%;'>".number_format($fcst[$b][$m],0,',','.')."</td>";
                    echo "</tr>";
                    /* Forecast Corporate */
                    echo "<tr class='center'>";
                        echo "<td class='odd' style='width:10%;'> Fcst Corporate </td>";
                        for ($m=0; $m <sizeof($this->month); $m++) { 
                            if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                                echo "<td class='medBlue' style='width:5%;'>".number_format($corporate[$b][$m],0,',','.')."</td>";
                            }else{
                                echo "<td class='odd' style='width:5%;'>".number_format($corporate[$b][$m],0,',','.')."</td>";
                            }
                        }
                        echo "<td class='smBlue' style='width:5%;'>".number_format($corporate[$b][$m],0,',','.')."</td>";
                    echo "</tr>";
                    /* BOOKING ANO ATUAL */
                    echo "<tr class='center'>";
                        echo "<td class='even' style='width:10%;'> $cYear Ad Sales </td>";
                        for ($m=0; $m <sizeof($this->month); $m++) { 
                            if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                                echo "<td class='medBlue' style='width:5%;'>".number_format($bookingCYear[$b][$m],0,',','.')."</td>";
                            }else{
                                echo "<td class='even' style='width:5%;'>".number_format($bookingCYear[$b][$m],0,',','.')."</td>";
                            }
                        }
                        echo "<td class='smBlue' style='width:5%;'>".number_format($bookingCYear[$b][$m],0,',','.')."</td>";
                    echo "</tr>";
                    /* SAP ANO ATUAL */
                    echo "<tr class='center'>";
                        echo "<td class='odd' style='width:10%;'> $cYear SAP </td>";
                        for ($m=0; $m <sizeof($this->month); $m++) { 
                            if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                                echo "<td class='medBlue' style='width:5%;'>".number_format($SAPCYear[$b][$m],0,',','.')."</td>";
                            }else{
                                echo "<td class='odd' style='width:5%;'>".number_format($SAPCYear[$b][$m],0,',','.')."</td>";
                            }
                        }
                        echo "<td class='smBlue' style='width:5%;'>".number_format($SAPCYear[$b][$m],0,',','.')."</td>";
                    echo "</tr>";
                    /* PORCENTAGEM ENTRE FCST ATUAL E BOOKING ANO PASSADO */
                    echo "<tr class='center'>";
                        echo "<td class='even' style='width:10%;'> % ".$cYear."F/Fcst-$pYear </td>";
                        for ($m=0; $m <sizeof($this->month); $m++) { 
                            if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                                if( $prc1[$b][$m] != 0 ){
                                    echo "<td class='medBlue' style='width:5%;'>".number_format($prc1[$b][$m],0,',','.')."%</td>";
                                }else{
                                    echo "<td class='medBlue' style='width:5%;'> - </td>";
                                }
                            }else{
                                if( $prc1[$b][$m] != 0 ){
                                    echo "<td class='even' style='width:5%;'>".number_format($prc1[$b][$m],0,',','.')."%</td>";
                                }else{
                                    echo "<td class='even' style='width:5%;'> - </td>";
                                }
                            }
                        }
                        if($prc1[$b][$m] != 0){
                            echo "<td class='smBlue' style='width:5%;'>".number_format($prc1[$b][$m],0,',','.')."%</td>";
                        }else{
                            echo "<td class='smBlue' style='width:5%;'> - </td>";
                        }
                    echo "</tr>";
                    /* PORCENTAGEM ENTRE FCST ATUAL E TARGET */
                    echo "<tr class='center'>";
                        echo "<td class='odd' style='width:10%;'> % ".$cYear."F/Target </td>";
                        for ($m=0; $m <sizeof($this->month); $m++) { 
                            if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                                if($prc2[$b][$m] != 0){
                                    echo "<td class='medBlue' style='width:5%;'>".number_format($prc2[$b][$m],0,',','.')."%</td>";
                                }else{
                                    echo "<td class='medBlue' style='width:5%;'> - </td>"; 
                                }
                            }else{
                                if($prc2[$b][$m] != 0){
                                    echo "<td class='odd' style='width:5%;'>".number_format($prc2[$b][$m],0,',','.')."%</td>";
                                }else{
                                    echo "<td class='odd' style='width:5%;'> - </td>";
                                }
                            }
                        }
                        if($prc2[$b][$m] != 0){
                            echo "<td class='smBlue' style='width:5%;'>".number_format($prc2[$b][$m],0,',','.')."%</td>";
                        }else{
                            echo "<td class='smBlue' style='width:5%;'> - </td>";
                        }
                    echo "</tr>";
            	echo "</table>";
            echo "</div>";
       	}

        
	}

}
