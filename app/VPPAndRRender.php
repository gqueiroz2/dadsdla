<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\PAndRRender;


class VPPAndRRender extends PAndRRender{
    
	public function VP1($forRender){
        $current = intval( date('m') ) - 1;
        $currentM = intval( date('m') );
        $yearToDate = $this->dealsWithMonthYTD($current);
        $currentMonth = $this->dealsWithMonth($currentM);

        $client = $forRender['client'];

        $bookingscYTDByClient = $forRender["bookingscYTDByClient"];
        $bookingspYTDByClient = $forRender["bookingspYTDByClient"];
        $varAbsYTDByClient = $forRender["varAbsYTDByClient"];

        $fcstcMonthByClient = $forRender["fcstcMonthByClient"];
        $bookingscMonthByClient = $forRender["bookingscMonthByClient"];
        $totalcYearMonthByClient = $forRender["totalcYearMonthByClient"];
        $bookingspMonthByClient = $forRender["bookingspMonthByClient"];
        $varAbsMonthByClient = $forRender["varAbsMonthByClient"];

        $fcstFullYearByClient = $forRender['fcstFullYearByClient'] ;
        $bookingscYearByClient = $forRender['bookingscYearByClient'];
        $bookingspYearByClient = $forRender['bookingspYearByClient'];
        $closedFullYearByClient = $forRender['closedFullYearByClient'];
        $bookedPercentageFullYearByClient = $forRender['bookedPercentageFullYearByClient'];
        $totalFullYearByClient = $forRender['totalFullYearByClient'];
        $varAbsFullYearByClient = $forRender["varAbsFullYearByClient"];
        $varPerFullYearByClient = $forRender["varPerFullYearByClient"];

        $bookingscYTD = $forRender["bookingscYTD"];
        $bookingspYTD = $forRender["bookingspYTD"];

        $varAbsYTD = $forRender["varAbsYTD"];
        $varPerYTD = $forRender["varPerYTD"];

        $fcstcMonth = $forRender["fcstcMonth"];
        $bookingscMonth = $forRender["bookingscMonth"];
        $totalcYearMonth = $forRender["totalcYearMonth"];
        $bookingspMonth = $forRender["bookingspMonth"];

        $varAbscMonth = $forRender["varAbscMonth"];
        $varPercMonth = $forRender["varPercMonth"];

        $closedFullYear = $forRender["closedFullYear"];
        $fcstFullYear = $forRender["fcstFullYear"];
        $bookingscYear = $forRender["bookingscYear"];
        $bookingspYear = $forRender["bookingspYear"];
        $bookedPercentageFullYear = $forRender["bookedPercentageFullYear"];
        $totalFullYear = $forRender["totalFullYear"];

        $varAbsFullYear = $forRender["varAbsFullYear"];
        $varPerFullYear = $forRender["varPerFullYear"];
        $fcstFullYearPercentage = $forRender["fcstFullYearPercentage"];

        $bookingsOverclosed = $forRender["bookingsOverclosed"];
        $closedFullYearPercentage = $forRender["closedFullYearPercentage"];
        $bookingscYearPercentage = $forRender["bookingscYearPercentage"];
        $fcstFullYearPercentage = $forRender["fcstFullYearPercentage"];
        $percentage = $forRender["percentage"];

        $region = $forRender["region"];
        $currency = $forRender["currency"];
        $value = $forRender["value"];
        $cYear = $forRender["cYear"];
        $pYear = $cYear-1;

        $totalFullYearByClientAE = $forRender["totalFullYearByClientAE"];
        $fcstFullYearByClientAE = $forRender["fcstFullYearByClientAE"];
        $fcstFullYearAE = $forRender["fcstFullYearAE"];

        $currencyName = $forRender["currencyName"];
        $valueView = $forRender["valueView"];
        $clientBrands = $forRender["clientBrands"];

        $target = $forRender["target"];
        $varABS = $forRender["varABS"];
        $varPRC = $forRender["varPRC"];
        /*echo "<td class='newBlue' colspan='2' style='border-style:solid; border-color:black; border-width: 1px 0px 1px 0px;'>
                        
                    </td>";*/

        echo "<input type='hidden' name='percentage' value='".base64_encode(json_encode($percentage))."'>";
        echo "<input type='hidden' name='client' value='".base64_encode(json_encode($client))."'>";
        echo "<input type='hidden' name='region' value='".base64_encode(json_encode($region))."'>";
        echo "<input type='hidden' name='currency' value='".base64_encode(json_encode($currency))."'>";
        echo "<input type='hidden' name='value' value='".base64_encode(json_encode($value))."'>";
        echo "<input type='hidden' name='cYear' value='".base64_encode(json_encode($cYear))."'>";
        echo "<input type='hidden' name='clientBrands' value='".base64_encode(json_encode($clientBrands))."'>";
        echo "<input type='hidden' id='target' name='target' value='".number_format($target,0,',','.')."'>";

        echo "<div class='row'>";
        echo "<div class='col-2'>";
            echo "<table id='table3' style=' width:100%;  text-align:center;'>";
                echo "<tr>";
                    echo "<td style='width:100%; height:40px;'><input type='text' id='myInput' onkeyup=\"myFunc()\" placeholder=\"Search for clients...\"></td>";
                echo "</tr>";
                echo "<tr>";
                    echo "<td class='lightBlue' rowspan='2' id='currencyName' style='border-style:solid; border-color:black; border-width: 1px 1px 0px 1px; height:40px;' >
                        <input type='text' readonly='true' id='varPRC' 
                                       value='(%) Target: ".number_format($varPRC,0,",",".")."%' 
                                       style=' border:none; font-weight:bold; 
                                       background-color:transparent; text-align:center; color:white; width:100%;'>

                    </td>";
                echo "</tr>";
                echo "<tr>";
                echo "</tr>";
                echo "<tr>";
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 1px 1px 1px 1px; height:20px;' >Total</td>";
                echo "</tr>";
                /*echo "<tr>";
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 1px 1px; height:20px;'>%</td>";
                echo "</tr>";*/
                echo "<tr>";
                    echo "<td>&nbsp</td>";
                echo "</tr>";
            echo "</table>";
        echo "</div>";
        echo "<div class='col table-responsive linked'>";
            echo "<table id='table4' style=' min-width:2600px; width:100%; text-align:center; margin-right:10px;'>";
                echo "<tr>";
                    echo "<td class='darkBlue' style='border-style:solid; border-color:black; border-width: 1px 0px 0px 1px; height:40px;' colspan='2'>Bookings YTD</td>";
                    echo "<td class='darkBlue' style='border-style:solid; border-color:black; border-width: 1px 1px 0px 0px;'> $yearToDate </td>";
                    echo "<td>&nbsp</td>";
                    echo "<td class='darkBlue' style='border-style:solid; border-color:black; border-width: 1px 0px 0px 1px;' colspan='4'> Bookings $currentMonth </td>";
                    /*
                        CURRENT MONTH
                    */
                    echo "<td class='darkBlue' style='border-style:solid; border-color:black; border-width: 1px 1px 0px 0px;' > $currentMonth </td>";
                    echo "<td>&nbsp</td>";
                    echo "<td class='darkBlue' style='border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;' colspan='9'>Full Year</td>";
                echo "</tr>";
                echo "<tr>";
                    echo "<td class='lightBlue' id='cYear' rowspan='2' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px; height:20px;'>$cYear</td>";
                    echo "<td class='lightBlue2' rowspan='2' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>$pYear</td>";
                    echo "<td class='lightBlue2' rowspan='2' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;'>Var. $pYear</td>";
                    echo "<td>&nbsp</td>";
                    echo "<td class='lightBlue' colspan='3' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>$cYear</td>";
                    echo "<td class='lightBlue2' rowspan='2' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>$pYear</td>";
                    echo "<td class='lightBlue2' rowspan='2'style='border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;'>Var. $pYear</td>";
                    echo "<td>&nbsp</td>";
                    echo "<td class='lightBlue' colspan='6' style=' border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>$cYear</td>";
                    echo "<td class='lightBlue2' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;'>$pYear</td>";
                    echo "<td class='lightBlue' rowspan='2' style=' border-style:solid; border-color:black; border-width: 1px 1px 1px 0px; width:5%;'>Var Abs</td>";
                    echo "<td class='lightBlue' rowspan='2' style=' border-style:solid; border-color:black; border-width: 1px 1px 1px 0px; width:5%;'>Var %</td>";
                    //echo "<td class='lightBlue' rowspan='2' style=' border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;'>Var $cYear/Target</td>";
                echo "</tr>";
                echo "<tr>";
                    echo "<td style='height:20px;'>&nbsp</td>";
                    echo "<td class='lightBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 1px;'>Bookings</td>";
                    echo "<td class='lightBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'>Fcast</td>";
                    echo "<td class='lightBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'>Total</td>";
                    echo "<td>&nbsp</td>";
                    echo "<td class='lightBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 1px;'>Closed</td>";
                    echo "<td class='lightBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'>Booked</td>";
                    echo "<td class='lightBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'>% Booked</td>";
                    echo "<td class='lightBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'>Fcst AE</td>";
                    echo "<td class='lightBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'>Manual Estimation</td>";
                    echo "<td class='lightBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 1px 0px;'>Total</td>";
                    echo "<td class='lightBlue2' style='border-style:solid; border-color:black; border-width: 0px 1px 1px 0px;'>Total</td>";
                echo "</tr>";
                echo "<tr>";
                    /* Bookings YTD Current Year */
                    echo "<td class='medBlue' id='col-1' style='border-style:solid; border-color:black; border-width: 1px 0px 1px 1px; height:20px; width:5%;'>
                            ".number_format($bookingscYTD, 0, ",", ".")."
                        </td>";

                    /* Bookings YTD Past Year */
                    echo "<td class='medBlue' id='col-2' style='border-style:solid; border-color:black; border-width: 1px 0px 1px 0px; width:5%; '>
                            ".number_format($bookingspYTD, 0, ",", ".")."
                          </td>";

                    /* Bookings YTD Var YoY */
                    echo "<td class='medBlue' id='col-3' style='border-style:solid; border-color:black; border-width: 1px 1px 1px 0px; width:5%; '>
                            ".number_format($varAbsYTD, 0, ",", ".")."
                          </td>";

                    echo "<td id='col-4' style='width:1%;'>&nbsp</td>";

                    /* Bookings Current Month on Current Year */
                    echo "<td class='medBlue' id='col-5' style='border-style:solid; border-color:black; border-width: 1px 0px 1px 1px; width:5%;'>
                            ".number_format($bookingscMonth, 0, ",", ".")."                            
                          </td>";

                    /* FCST Current Month on Current Year */
                    echo "<td class='medBlue' id='col-6' style='border-style:solid; border-color:black; border-width: 1px 0px 1px 0px; width:5%; '>
                            ".number_format($fcstcMonth, 0, ",", ".")."                            
                          </td>";

                    /* Bookings Current Month on Current Year + FCST Current Month on Current Year */
                    echo "<td class='medBlue' id='col-7' style='border-style:solid; border-color:black; border-width: 1px 0px 1px 0px; width:5%; '>
                            ".number_format($totalcYearMonth, 0, ",", ".")."                            
                          </td>";

                    /* Bookings Current Month on Past Year */
                    echo "<td class='medBlue' id='col-8' style='border-style:solid; border-color:black; border-width: 1px 0px 1px 0px; width:5%; '>
                            ".number_format($bookingspMonth, 0, ",", ".")."                            
                          </td>";

                    /* VAR Bookings Current Month on Current Year -/ FCST Current Month on Current Year */
                    echo "<td class='medBlue' id='col-9' style='border-style:solid; border-color:black; border-width: 1px 1px 1px 0px;  width:5%;'>
                            ".number_format($varAbscMonth, 0, ",", ".")."                            
                          </td>";

                    echo "<td id='col-10' style='width:1%;'>&nbsp</td>";

                    /*Closed*/
                    echo "<td class='medBlue' id='col-11' style='border-style:solid; border-color:black; border-width: 1px 0px 1px 1px; width:5%; '>                            
                            ".number_format($closedFullYear, 0, ",", ".")."                            
                          </td>";
                    
                    echo "<td class='medBlue' id='col-12' style='border-style:solid; border-color:black; border-width: 1px 0px 1px 0px; width:5%;'>
                            ".number_format($bookingscYear, 0, ",", ".")."                            
                            </td>";

                    /* % Booked*/                    
                    echo "<td class='medBlue' id='col-13' style='border-style:solid; border-color:black; border-width: 1px 0px 1px 0px; width:5%;'>
                            ".number_format($bookingsOverclosed, 0, ",", ".")."%
                            </td>";

                    /*FCST AE*/                    
                    echo "<td class='medBlue' id='col-14' style='border-style:solid; border-color:black; border-width: 1px 0px 1px 0px; width:5%;'>
                            ".number_format($fcstFullYearAE, 0, ",", ".")."
                        </td>";
                    
                    /*Manual Estimation*/
                    echo "<td class='medBlue' id='col-15' style='border-style:solid; border-color:black; border-width: 1px 0px 1px 0px; width:5%;'>
                                <input type='text' readonly='true' id='RF-Total-Fy' 
                                       value='".number_format($fcstFullYear, 0, ",", ".")."' 
                                       style=' border:none; font-weight:bold; 
                                       background-color:transparent; text-align:center'>
                          </td>";
                    /*Total CYear*/
                    echo "<td class='medBlue' id='col-16' style='border-style:solid; border-color:black; border-width: 1px 0px 1px 0px; width:5%;'>
                            <input type='text' readonly='true' id='TotalCy-Fy' 
                                       value='".number_format($totalFullYear, 0, ",", ".")."' 
                                       style=' border:none; font-weight:bold; 
                                       background-color:transparent; text-align:center'>
                                                            
                          </td>";

                    /*Total PYear*/
                    echo "<td class='medBlue' id='col-17' style='border-style:solid; border-color:black; border-width: 1px 0px 1px 0px; width:5%;'>
                                 <input type='text' readonly='true' id='totalPYear' 
                                       value='".number_format($bookingspYear, 0, ",", ".")."' 
                                       style=' border:none; font-weight:bold; 
                                       background-color:transparent; text-align:center; width:100%;'>
                          </td>";

                    /*Var Abs CYear and PYear*/
                    echo "<td class='medBlue' id='col-18' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 0px; width:5%;'> 
                                    <input type='text' readonly='true' id='varABS2' 
                                       value='".number_format($varAbsFullYear, 0, ",", ".")."' 
                                       style=' border:none; font-weight:bold; 
                                       background-color:transparent; text-align:center; width:100%;'>
                    </td>";
                    
                    echo "<td class='medBlue' id='col-19' style='border-style:solid; border-color:black; border-width: 0px 1px 1px 0px; width:5%;'> 
                                    <input type='text' readonly='true' id='varPRC2' 
                                       value='".number_format($varPerFullYear, 0, ",", ".")."%' 
                                       style=' border:none; font-weight:bold; 
                                       background-color:transparent; text-align:center; width:100%;'>
                    </td>";


                    /*Var Abs CYear and PYear*/
                    /*echo "<td class='medBlue' id='col-18' style='border-style:solid; border-color:black; border-width: 1px 0px 0px 0px; width:5%;'>
                                \$: ".number_format($varAbsFullYear, 0, ",", ".")."
                          </td>";*/

                    /*Var Abs CYear and Target*/
                    /*echo "<td class='medBlue' id='col-19' style='border-style:solid; border-color:black; border-width: 1px 1px 0px 0px; width:5%;'>
                                <input type='text' readonly='true' id='varABS' 
                                       value='\$: ".number_format($varABS,0,",",".")."' 
                                       style=' border:none; font-weight:bold; 
                                       background-color:transparent; text-align:center'>
                          </td>";*/

                echo "</tr>";

                /*echo "<tr>";
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 1px; height:20px;'>&nbsp</td>";
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'>&nbsp</td>";
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 1px 0px;'>
                                ".number_format($varPerYTD, 0, ",", ".")."%
                          </td>";
                    echo "<td>&nbsp</td>";
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 1px;'>0%</td>";
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'>&nbsp</td>";
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'>&nbsp</td>";
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'>&nbsp</td>";
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 1px 0px;'>
                            ".number_format($varPercMonth, 0, ",", ".")."%                            
                    </td>";

                    

                    echo "<td>&nbsp</td>";
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 1px;'>
                                ".number_format($closedFullYearPercentage, 0, ",", ".")."%
                          </td>";
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'>
                                ".number_format($bookingscYearPercentage, 0, ",", ".")."%
                          </td>";
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'>&nbsp</td>";
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'>
                                ".number_format($fcstFullYearPercentage, 0, ",", ".")."%
                          </td>";
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'>
                                ".number_format($fcstFullYearPercentage, 0, ",", ".")."%
                          </td>";
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'>
                                100%
                          </td>";

                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'>&nbsp</td>";

                    Var Abs CYear and PYear
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'> \$: ".number_format($varAbsFullYear, 0, ",", ".")."
                    </td>";
                    
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'> %: ".number_format($varPerFullYear, 0, ",", ".")."%
                    </td>";

                    Var PRC CYear and Target
                    
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 1px 0px;'> 
                            <input type='text' readonly='true' id='varPRC' 
                                       value='%: ".number_format($varPRC,0,",",".")."%' 
                                       style=' border:none; font-weight:bold; 
                                       background-color:transparent; text-align:center'>
                    </td>";
                echo "</tr>";*/
            echo "</table>";
        echo "</div>";
        echo "</div>";


        echo "<div class='row '>";

        echo "<div class='col-2'>";
            echo "<table class='temporario' id='table1' style='width:100%; min-height:100%; text-align:center;'>";
                for ($c=0; $c <sizeof($client) ; $c++) {
                    if($c%2 == 0){
                        $class = "rcBlue";
                    }else{
                        $class = "odd";
                    }
                    echo "<tr>";
                        echo "<td class='$class' style='border-style:solid; border-color:black; border-width: 1px 1px 1px 1px; height:30px; width:100%;' id='parent-$c' >".$client[$c]['client']."</td>";
                    echo "</tr>";
                }
            echo "</table>";
        echo "</div>";

        echo "<div class='col table-responsive linked'>";
            echo "<table class='temporario' id='table2' style='min-width:2600px; min-height:100%; text-align:center; width:100%;'>";
                
                for ($c=0; $c < sizeof($client); $c++) {
                    if($c%2 == 0){
                        $class = "rcBlue";
                    }else{
                        $class = "odd";
                    }

                    echo "<tr>";

                        /* Bookings YTD Current Year */
                        echo "<td class='$class col-c-1' id='child-$c' style='border-style:solid; border-color:black; border-width: 1px 0px 1px 1px; height:30px; '>
                                ".number_format($bookingscYTDByClient[$c], 0, ",", ".")."
                              </td>";

                        /* Bookings YTD Past Year */
                        echo "<td class='$class col-c-2' style='border-style:solid; border-color:black; border-width: 1px 0px 1px 0px; '>
                                ".number_format($bookingspYTDByClient[$c], 0, ",", ".")."
                              </td>";

                        /* Bookings YTD Var YoY */
                        echo "<td class='$class col-c-3' style='border-style:solid; border-color:black; border-width: 1px 1px 1px 0px; '>
                                ".number_format($varAbsYTDByClient[$c], 0, ",", ".")."
                              </td>";

                        echo "<td class='col-c-4' >&nbsp</td>";

                        /* Bookings Current Month on Current Year */
                        echo "<td class='$class col-c-5' style='border-style:solid; border-color:black; border-width: 1px 0px 1px 1px; '>
                                ".number_format($bookingscMonthByClient[$c], 0, ",", ".")."
                              </td>";

                        /* FCST Current Month on Current Year */
                        echo "<td class='$class col-c-6' style='border-style:solid; border-color:black; border-width: 1px 0px 1px 0px; '>
                                    <input type='text' id='clientRF-Cm-$c' 
                                           value='".number_format($fcstcMonthByClient[$c], 0, ",", ".")."' 
                                           style='width:100%; border:none; 
                                           font-weight:bold; 
                                           background-color:transparent; text-align:center'
                                           readonly='true'>
                              </td>";

                        /*TOTAL September BKG + FCST*/                        
                        echo "<td class='$class col-c-7' style='border-style:solid; border-color:black; border-width: 1px 0px 1px 0px; '>
                                ".number_format($totalcYearMonthByClient[$c], 0, ",", ".")."
                            </td>";

                        /* Bookings Current Month on Past Year */
                        echo "<td class='$class col-c-8' style='border-style:solid; border-color:black; border-width: 1px 0px 1px 0px; '>
                                ".number_format($bookingspMonthByClient[$c], 0, ",", ".")."
                              </td>";

                        /* VAR Bookings Current Month on Current Year -/ FCST Current Month on Current Year */
                        echo "<td class='$class col-c-9' style='border-style:solid; border-color:black; border-width: 1px 1px 1px 0px; '>
                                    ".number_format($varAbsMonthByClient[$c], 0, ",", ".")."
                              </td>";

                        echo "<td class='col-c-10'>&nbsp</td>";

                        /*Closed*/
                        echo "<td class='$class col-c-11' style='border-style:solid; border-color:black; border-width: 1px 0px 1px 1px; '>
                                <input type='text' readonly='true' id='closed-Fy-$c' 
                                           value='".number_format($closedFullYearByClient[$c], 0, ",", ".")."' 
                                           style='width:100%; border:none; font-weight:bold;
                                           background-color:transparent; text-align:center;'>
                              </td>";

                        /* Booked*/
                        echo "<td class='$class col-c-12' style='border-style:solid; border-color:black; border-width: 1px 0px 1px 0px; '>
                                <input type='text' readonly='true' id='booking-Fy-$c' 
                                           value='".number_format($bookingscYearByClient[$c], 0, ",", ".")."' 
                                           style='width:100%; border:none; font-weight:bold;
                                           background-color:transparent; text-align:center;'>
                              </td>";

                        /* % Booked Percentage*/                    
                        echo "<td class='$class col-c-13' style='border-style:solid; border-color:black; border-width: 1px 0px 1px 0px; '>
                                ".number_format($bookedPercentageFullYearByClient[$c], 0, ",", ".")."%
                              </td>";

                        /*Proposals*/ 
                        echo "<td class='$class col-c-14' style='border-style:solid; border-color:black; border-width: 1px 0px 1px 0px; '>
                                    <input type='text' readonly='true' id='passClientRF-Fy-$c' 
                                           value='".number_format($fcstFullYearByClientAE[$c], 0, ",", ".")."' 
                                           style='width:100%; border:none; font-weight:bold;
                                           background-color:transparent; text-align:center;'>
                              </td>";

                        /*Fcst*/
                        echo "<td class='$class col-c-15' style='border-style:solid; border-color:black; border-width: 1px 0px 1px 0px; '>
                                    <input type='text' id='clientRF-Fy-$c' name='clientRF-Fy-$c' 
                                           value='".number_format($fcstFullYearByClient[$c], 0, ",", ".")."' 
                                           style='width:100%; border:none; font-weight:bold;
                                           background-color:transparent; text-align:center;'>
                             </td>";

                        /*Total CYear*/
                        echo "<td class='$class col-c-16' style='border-style:solid; border-color:black; border-width: 1px 0px 1px 0px; '>
                                <input type='text' readonly='true' id='totalClient-Fy-$c' 
                                           value='".number_format($totalFullYearByClient[$c], 0, ",", ".")."' 
                                           style='width:100%; border:none; font-weight:bold;
                                           background-color:transparent; text-align:center;'>
                              </td>";

                        /*Total PYear*/
                        echo "<td class='$class col-c-17' style='border-style:solid; border-color:black; border-width: 1px 0px 1px 0px; '>
                                ".number_format($bookingspYearByClient[$c], 0, ",", ".")."
                              </td>";

                        /*Var Abs YoY*/
                        echo "<td class='$class col-c-18' style='border-style:solid; border-color:black; border-width: 1px 0px 1px 0px; width:5%;'>
                                ".number_format($varAbsFullYearByClient[$c], 0, ",", ".")."
                              </td>";

                        /*Var Per YoY*/
                        echo "<td class='$class col-c-19' style='border-style:solid; border-color:black; border-width: 1px 1px 1px 0px;  width:5%;'>";
                            if($varPerFullYearByClient[$c] > 0){
                                echo number_format($varPerFullYearByClient[$c], 0, ",", ".")."%";
                            }else{
                                echo "-";
                            }
                        echo "</td>";
                    echo "</tr>";
                }
            echo "</table>";
        echo "</div>";
        echo "</div>";
    }    



}
