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


		 echo "<div class='table-responsive' style='zoom:70%;'>";
            echo "<table style=' border:solid; width:100%; text-align:center; border-width:1px; font-size:25px;'>";
                echo "<tr><th class='lightBlue'> Pacing Report - $cYear - ($currency/$value) </th></tr>";
            echo "</table>";
        echo "</div>";


        for ($b=0; $b <sizeof($brands) ; $b++) { 
                echo "<div class='row mt-2'>";
                echo "<div class='col-2'>";
                    echo "<table style='width:100%; text-align:center; zoom:70%; font-size:18px;'>";
                        echo "<tr>";
                            echo "<td style='height:40px;'>&nbsp</td>";
                        echo "</tr>";
                        echo "<tr>";
                            echo "<td rowspan='9' class=\"".strtolower($brands[$b]['name'])."\" style='width:35%; border-style:solid; border-color:black; border-width: 1px 1px 1px 1px; height:40px; font-size:30px;'>".$brands[$b]['name']."</td>";
                            echo "<td class='odd' style='width:65%; border-style:solid; border-color:black; border-width: 1px 1px 0px 0px; height:40px;'> $pYear Ad Sales </td>";
                        echo "</tr>";
                        echo "<tr>";
                            echo "<td class='even' style='width:65%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px; height:40px;'> $pYear SAP </td>";
                        echo "</tr>";
                        echo "<tr>";
                            echo "<td class='odd' style='width:65%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px; height:40px;'> Target </td>";
                        echo "</tr>";
                        echo "<tr>";
                            echo "<td class='even' style='width:65%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px; height:40px;'> Fcst Asales - Current </td>";
                        echo "</tr>";
                        echo "<tr>";
                            echo "<td class='odd' style='width:65%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px; height:40px;'> Fcst Corporate </td>";
                        echo "</tr>";
                        echo "<tr>";
                            echo "<td class='even' style='width:65%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px; height:40px;'> $cYear Ad Sales </td>";
                        echo "</tr>";
                        echo "<tr>";
                            echo "<td class='odd' style='width:65%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px; height:40px;'> $cYear SAP </td>";
                        echo "</tr>";
                        echo "<tr>";
                            echo "<td class='even' style='width:65%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px; height:40px;'> % ".$cYear."F/Fcst-$pYear </td>";
                        echo "</tr>";
                        echo "<tr>";
                            echo "<td class='odd' style='width:65%; border-style:solid; border-color:black; border-width: 0px 1px 1px 0px; height:40px;'> % ".$cYear."F/Target </td>";
                        echo "</tr>";
                    echo "</table>";
                echo "</div>";
                echo "<div class='col linked table-responsive '>";
            		echo "<table style='width:100%; text-align:center; min-width:3000px; zoom:70%; border-style:solid; border-color:black; border-width: 0px 0px 0px 1px; font-size:18px;'>";
                        
                        echo "<tr>";
                        for ($m=0; $m <sizeof($this->month); $m++) { 
                            if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                                echo "<td class='quarter' style='width:5.625%; border-style:solid; border-color:black; border-width: 1px 1px 1px 1px; height:40px;'>".$this->month[$m]."</td>";
                            }else{
                                echo "<td class='smBlue' style='width:5.625%; border-style:solid; border-color:black; border-width: 1px 0px 1px 0px; height:40px;'>".$this->month[$m]."</td>";
                            }
                        }
                            echo "<td class='darkBlue' style='width:10%; border-style:solid; border-color:black; border-width: 1px 1px 1px 0px; height:40px;'>Total</td>";
                        echo "</tr>";


                        /*
                            BOOKING ANO PASSADO
                        */
                        echo "<tr>";
                            for ($m=0; $m <sizeof($this->month); $m++) { 
                                if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                                    echo "<td class='medBlue' style='width:5.625%; border-style:solid; border-color:black; border-width: 1px 1px 0px 1px; height:40px;'>".number_format($bookingPYear[$b][$m],0,',','.')."</td>";
                                }else{
                                    echo "<td class='odd' style='width:5.625%; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px; height:40px;'>".number_format($bookingPYear[$b][$m],0,',','.')."</td>";
                                }
                            }
                            echo "<td class='smBlue' style='width:10%; border-style:solid; border-color:black; border-width: 1px 1px 0px 0px; height:40px;'>".number_format($bookingPYear[$b][$m],0,',','.')."</td>";
                        echo "</tr>";



                        /*
                             
                            SAP ANO PASSADO

                        */
                        echo "<tr>";
                            for ($m=0; $m <sizeof($this->month); $m++) { 
                                if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                                    echo "<td class='medBlue' style='width:5.625%; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px; height:40px;'>".number_format($SAPPYear[$b][$m],0,',','.')."</td>";
                                }else{
                                    echo "<td class='even' style='width:5.625%; border-style:solid; border-color:black; border-width: 0px 0px 0px 0px; height:40px;'>".number_format($SAPPYear[$b][$m],0,',','.')."</td>";
                                }
                            }
                            echo "<td class='smBlue' style='width:10%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px; height:40px;'>".number_format($SAPPYear[$b][$m],0,',','.')."</td>";
                        echo "</tr>";

                        /*
                            TARGET
                        */

                        echo "<tr>";
                            for ($m=0; $m <sizeof($this->month); $m++) { 
                                if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                                    echo "<td class='medBlue' style='width:5.625%; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px; height:40px;'>".number_format($target[$b][$m],0,',','.')."</td>";
                                }else{
                                    echo "<td class='odd' style='width:5.625%; border-style:solid; border-color:black; border-width: 0px 0px 0px 0px; height:40px;'>".number_format($target[$b][$m],0,',','.')."</td>";
                                }
                            }
                            echo "<td class='smBlue' style='width:10%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px; height:40px;'>".number_format($target[$b][$m],0,',','.')."</td>";
                        echo "</tr>";

                        /*
                            FCST
                        */

                        echo "<tr>";
                            for ($m=0; $m <sizeof($this->month); $m++) { 
                                if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                                    echo "<td class='medBlue' style='width:5.625%; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px; height:40px;'>".number_format($fcst[$b][$m],0,',','.')."</td>";
                                }else{
                                    echo "<td class='even' style='width:5.625%; border-style:solid; border-color:black; border-width: 0px 0px 0px 0px; height:40px;'>".number_format($fcst[$b][$m],0,',','.')."</td>";
                                }
                            }
                            echo "<td class='smBlue' style='width:10%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px; height:40px;'>".number_format($fcst[$b][$m],0,',','.')."</td>";
                        echo "</tr>";

                        /*

                            Forecast Corporate

                        */

                        echo "<tr>";
                            for ($m=0; $m <sizeof($this->month); $m++) { 
                                if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                                    echo "<td class='medBlue' style='width:5.625%; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px; height:40px;'>".number_format($corporate[$b][$m],0,',','.')."</td>";
                                }else{
                                    echo "<td class='odd' style='width:5.625%; border-style:solid; border-color:black; border-width: 0px 0px 0px 0px; height:40px;'>".number_format($corporate[$b][$m],0,',','.')."</td>";
                                }
                            }
                            echo "<td class='smBlue' style='width:10%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px; height:40px;'>".number_format($corporate[$b][$m],0,',','.')."</td>";
                        echo "</tr>";

                        /*

                            BOOKING ANO ATUAL

                        */

                        echo "<tr>";
                            for ($m=0; $m <sizeof($this->month); $m++) { 
                                if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                                    echo "<td class='medBlue' style='width:5.625%; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px; height:40px;'>".number_format($bookingCYear[$b][$m],0,',','.')."</td>";
                                }else{
                                    echo "<td class='even' style='width:5.625%; border-style:solid; border-color:black; border-width: 0px 0px 0px 0px; height:40px;'>".number_format($bookingCYear[$b][$m],0,',','.')."</td>";
                                }
                            }
                            echo "<td class='smBlue' style='width:10%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px; height:40px;'>".number_format($bookingCYear[$b][$m],0,',','.')."</td>";
                        echo "</tr>";

                         /*

                            BOOKING ANO ATUAL

                        */

                        echo "<tr>";
                            for ($m=0; $m <sizeof($this->month); $m++) { 
                                if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                                    echo "<td class='medBlue' style='width:5.625%; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px; height:40px;'>".number_format($SAPCYear[$b][$m],0,',','.')."</td>";
                                }else{
                                    echo "<td class='odd' style='width:5.625%; border-style:solid; border-color:black; border-width: 0px 0px 0px 0px; height:40px;'>".number_format($SAPCYear[$b][$m],0,',','.')."</td>";
                                }
                            }
                            echo "<td class='smBlue' style='width:10%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px; height:40px;'>".number_format($SAPCYear[$b][$m],0,',','.')."</td>";
                        echo "</tr>";

                        /*

                            PORCENTAGEM ENTRE FCST ATUAL E BOOKING ANO PASSADO
    
                        */

                        echo "<tr>";
                            for ($m=0; $m <sizeof($this->month); $m++) { 
                                if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                                    echo "<td class='medBlue' style='width:5.625%; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px; height:40px;'>".number_format($prc1[$b][$m],0,',','.')."%</td>";
                                }else{
                                    echo "<td class='even' style='width:5.625%; border-style:solid; border-color:black; border-width: 0px 0px 0px 0px; height:40px;'>".number_format($prc1[$b][$m],0,',','.')."%</td>";
                                }
                            }
                            echo "<td class='smBlue' style='width:10%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px; height:40px;'>".number_format($prc1[$b][$m],0,',','.')."%</td>";
                        echo "</tr>";


                        /*

                            PORCENTAGEM ENTRE FCST ATUAL E TARGET

                        */

                        echo "<tr>";
                            for ($m=0; $m <sizeof($this->month); $m++) { 
                                if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                                    echo "<td class='medBlue' style='width:5.625%; border-style:solid; border-color:black; border-width: 0px 1px 1px 1px; height:40px;'>".number_format($prc2[$b][$m],0,',','.')."%</td>";
                                }else{
                                    echo "<td class='odd' style='width:5.625%; border-style:solid; border-color:black; border-width: 0px 0px 1px 0px; height:40px;'>".number_format($prc2[$b][$m],0,',','.')."%</td>";
                                }
                            }
                            echo "<td class='smBlue' style='width:10%; border-style:solid; border-color:black; border-width: 0px 1px 1px 0px; height:40px;'>".number_format($prc2[$b][$m],0,',','.')."%</td>";
                        echo "</tr>";

                	echo "</table>";
                echo "</div>";
            echo "</div>";
       	}

        echo "<div class='row mt-2'>";
            echo "<div class='col-2'>";
                echo "<table style='width:100%; text-align:center; zoom:70%; font-size:18px;'>";
                    echo "<tr>";
                        echo "<td style='height:40px;'>&nbsp</td>";
                    echo "</tr>";
                    echo "<tr>";
                        echo "<td rowspan='9' class=\"darkBlue\" style='width:35%; border-style:solid; border-color:black; border-width: 1px 1px 1px 1px; height:40px; font-size:30px;'>Total</td>";
                        echo "<td class='odd' style='width:65%; border-style:solid; border-color:black; border-width: 1px 1px 0px 0px; height:40px;'> $pYear Ad Sales </td>";
                    echo "</tr>";
                    echo "<tr>";
                        echo "<td class='even' style='width:65%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px; height:40px;'> $pYear SAP </td>";
                    echo "</tr>";
                    echo "<tr>";
                        echo "<td class='odd' style='width:65%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px; height:40px;'> Target </td>";
                    echo "</tr>";
                    echo "<tr>";
                        echo "<td class='even' style='width:65%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px; height:40px;'> Fcst Asales - Current </td>";
                    echo "</tr>";
                    echo "<tr>";
                        echo "<td class='odd' style='width:65%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px; height:40px;'> Fcst Corporate </td>";
                    echo "</tr>";
                    echo "<tr>";
                        echo "<td class='even' style='width:65%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px; height:40px;'> $cYear Ad Sales </td>";
                    echo "</tr>";
                    echo "<tr>";
                        echo "<td class='odd' style='width:65%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px; height:40px;'> $cYear SAP </td>";
                    echo "</tr>";
                    echo "<tr>";
                        echo "<td class='even' style='width:65%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px; height:40px;'> % ".$cYear."F/Fcst-$pYear </td>";
                    echo "</tr>";
                    echo "<tr>";
                        echo "<td class='odd' style='width:65%; border-style:solid; border-color:black; border-width: 0px 1px 1px 0px; height:40px;'> % ".$cYear."F/Target </td>";
                    echo "</tr>";
                echo "</table>";
            echo "</div>";
            echo "<div class='col linked table-responsive '>";
                echo "<table style='width:100%; text-align:center; min-width:3000px; zoom:70%; border-style:solid; border-color:black; border-width: 0px 0px 0px 1px; font-size:18px;'>";
                    
                    echo "<tr>";
                    for ($m=0; $m <sizeof($this->month); $m++) { 
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td class='quarter' style='width:5.625%; border-style:solid; border-color:black; border-width: 1px 1px 1px 1px; height:40px;'>".$this->month[$m]."</td>";
                        }else{
                            echo "<td class='smBlue' style='width:5.625%; border-style:solid; border-color:black; border-width: 1px 0px 1px 0px; height:40px;'>".$this->month[$m]."</td>";
                        }
                    }
                        echo "<td class='darkBlue' style='width:10%; border-style:solid; border-color:black; border-width: 1px 1px 1px 0px; height:40px;'>Total</td>";
                    echo "</tr>";

                    /*
                        BOOKING ANO PASSADO
                    */
                    echo "<tr>";
                        for ($m=0; $m <sizeof($this->month); $m++) { 
                            if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                                echo "<td class='medBlue' style='width:5.625%; border-style:solid; border-color:black; border-width: 1px 1px 0px 1px; height:40px;'>".number_format($totalBookingPYear[$m],0,',','.')."</td>";
                            }else{
                                echo "<td class='odd' style='width:5.625%; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px; height:40px;'>".number_format($totalBookingPYear[$m],0,',','.')."</td>";
                            }
                        }
                        echo "<td class='smBlue' style='width:10%; border-style:solid; border-color:black; border-width: 1px 1px 0px 0px; height:40px;'>".number_format($totalBookingPYear[$m],0,',','.')."</td>";
                    echo "</tr>";



                    /*
                         
                        SAP ANO PASSADO

                    */
                    echo "<tr>";
                        for ($m=0; $m <sizeof($this->month); $m++) { 
                            if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                                echo "<td class='medBlue' style='width:5.625%; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px; height:40px;'>".number_format($totalActualPYear[$m],0,',','.')."</td>";
                            }else{
                                echo "<td class='even' style='width:5.625%; border-style:solid; border-color:black; border-width: 0px 0px 0px 0px; height:40px;'>".number_format($totalActualPYear[$m],0,',','.')."</td>";
                            }
                        }
                        echo "<td class='smBlue' style='width:10%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px; height:40px;'>".number_format($totalActualPYear[$m],0,',','.')."</td>";
                    echo "</tr>";

                    /*
                        TARGET
                    */

                    echo "<tr>";
                        for ($m=0; $m <sizeof($this->month); $m++) { 
                            if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                                echo "<td class='medBlue' style='width:5.625%; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px; height:40px;'>".number_format($totalTarget[$m],0,',','.')."</td>";
                            }else{
                                echo "<td class='odd' style='width:5.625%; border-style:solid; border-color:black; border-width: 0px 0px 0px 0px; height:40px;'>".number_format($totalTarget[$m],0,',','.')."</td>";
                            }
                        }
                        echo "<td class='smBlue' style='width:10%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px; height:40px;'>".number_format($totalTarget[$m],0,',','.')."</td>";
                    echo "</tr>";

                    /*
                        FCST
                    */

                    echo "<tr>";
                        for ($m=0; $m <sizeof($this->month); $m++) { 
                            if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                                echo "<td class='medBlue' style='width:5.625%; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px; height:40px;'>".number_format($totalFcstValue[$m],0,',','.')."</td>";
                            }else{
                                echo "<td class='even' style='width:5.625%; border-style:solid; border-color:black; border-width: 0px 0px 0px 0px; height:40px;'>".number_format($totalFcstValue[$m],0,',','.')."</td>";
                            }
                        }
                        echo "<td class='smBlue' style='width:10%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px; height:40px;'>".number_format($totalFcstValue[$m],0,',','.')."</td>";
                    echo "</tr>";

                    /*

                        Forecast Corporate

                    */

                    echo "<tr>";
                        for ($m=0; $m <sizeof($this->month); $m++) { 
                            if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                                echo "<td class='medBlue' style='width:5.625%; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px; height:40px;'>".number_format($totalCorporate[$m],0,',','.')."</td>";
                            }else{
                                echo "<td class='odd' style='width:5.625%; border-style:solid; border-color:black; border-width: 0px 0px 0px 0px; height:40px;'>".number_format($totalCorporate[$m],0,',','.')."</td>";
                            }
                        }
                        echo "<td class='smBlue' style='width:10%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px; height:40px;'>".number_format($totalCorporate[$m],0,',','.')."</td>";
                    echo "</tr>";

                    /*

                        BOOKING ANO ATUAL

                    */

                    echo "<tr>";
                        for ($m=0; $m <sizeof($this->month); $m++) { 
                            if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                                echo "<td class='medBlue' style='width:5.625%; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px; height:40px;'>".number_format($totalBookingCYear[$m],0,',','.')."</td>";
                            }else{
                                echo "<td class='even' style='width:5.625%; border-style:solid; border-color:black; border-width: 0px 0px 0px 0px; height:40px;'>".number_format($totalBookingCYear[$m],0,',','.')."</td>";
                            }
                        }
                        echo "<td class='smBlue' style='width:10%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px; height:40px;'>".number_format($totalBookingCYear[$m],0,',','.')."</td>";
                    echo "</tr>";

                     /*

                        BOOKING ANO ATUAL

                    */

                    echo "<tr>";
                        for ($m=0; $m <sizeof($this->month); $m++) { 
                            if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                                echo "<td class='medBlue' style='width:5.625%; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px; height:40px;'>".number_format($totalActualCYear[$m],0,',','.')."</td>";
                            }else{
                                echo "<td class='odd' style='width:5.625%; border-style:solid; border-color:black; border-width: 0px 0px 0px 0px; height:40px;'>".number_format($totalActualCYear[$m],0,',','.')."</td>";
                            }
                        }
                        echo "<td class='smBlue' style='width:10%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px; height:40px;'>".number_format($totalActualCYear[$m],0,',','.')."</td>";
                    echo "</tr>";

                    /*

                        PORCENTAGEM ENTRE FCST ATUAL E BOOKING ANO PASSADO

                    */

                    echo "<tr>";
                        for ($m=0; $m <sizeof($this->month); $m++) { 
                            if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                                echo "<td class='medBlue' style='width:5.625%; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px; height:40px;'>".number_format($totalPrc1[$m],0,',','.')."%</td>";
                            }else{
                                echo "<td class='even' style='width:5.625%; border-style:solid; border-color:black; border-width: 0px 0px 0px 0px; height:40px;'>".number_format($totalPrc1[$m],0,',','.')."%</td>";
                            }
                        }
                        echo "<td class='smBlue' style='width:10%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px; height:40px;'>".number_format($totalPrc1[$m],0,',','.')."%</td>";
                    echo "</tr>";


                    /*

                        PORCENTAGEM ENTRE FCST ATUAL E TARGET

                    */

                    echo "<tr>";
                        for ($m=0; $m <sizeof($this->month); $m++) { 
                            if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                                echo "<td class='medBlue' style='width:5.625%; border-style:solid; border-color:black; border-width: 0px 1px 1px 1px; height:40px;'>".number_format($totalPrc2[$m],0,',','.')."%</td>";
                            }else{
                                echo "<td class='odd' style='width:5.625%; border-style:solid; border-color:black; border-width: 0px 0px 1px 0px; height:40px;'>".number_format($totalPrc2[$m],0,',','.')."%</td>";
                            }
                        }
                        echo "<td class='smBlue' style='width:10%; border-style:solid; border-color:black; border-width: 0px 1px 1px 0px; height:40px;'>".number_format($totalPrc2[$m],0,',','.')."%</td>";
                    echo "</tr>";

                echo "</table>";
            echo "</div>";
        echo "</div>";
	}

}
