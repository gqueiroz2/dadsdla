@extends('layouts.mirror')

@section('title', '@')

@section('head')

<style>
    table {
        border-collapse: collapse;
        width: 100%;
    }

    th, td {
      text-align: left;
      padding: 8px;
    }

    tr:nth-child(even) {background-color: #f2f2f2;}

</style>

@endsection

@section('content')
    <div class="container-fluid">
        {{--
        @if (Route::has('login'))
            <div class="top-right links">
                @auth
                    <a href="{{ url('/home') }}">Home</a>
                @else
                    <a href="{{ route('login') }}">Login</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}">Register</a>
                    @endif
                @endauth
            </div>
        @else
        
        
            <div class="container-fluid" style="margin-top: 11vw;">
                <div class="row justify-content-center">
                    <div class="col">
                        <center>
                            <img src="/shortLogo.png" style="width: 25%; height: auto; opacity:1; border: 2px solid black;">
                            <spam style="font-size:10vw;border: 2px solid black;">|ADS DLA </spam>
                        </center>                       
                    </div>
                </div>
            </div>
        
        @endif
        --}}    
        <?php

            
           /*
            $ytd = $base;

            echo "<table style='width:90%;'>";
            echo "<tr>";
                echo "<th> Month </th>";
                echo "<th> Channel Brand </th>";
                echo "<th> Channel Feed </th>";
                echo "<th> Sales Rep </th>";
                echo "<th> Agency </th>";
                echo "<th> Client </th>";
                echo "<th> Currency </th>";
                echo "<th> Charge Type </th>";
                echo "<th> Client Product </th>";
                echo "<th> Campaign Reference </th>";
                echo "<th> Order Reference </th>";
                echo "<th> Impression Duration </th>";
                echo "<th> Gross Revenue </th>";
                echo "<th> Num of Spot </th>";
                echo "<th> Net Revenue </th>";
            echo "</tr>";
            for ($y=0; $y < sizeof($ytd); $y++) {                     
                for ($x=0; $x < sizeof($ytd[$region]); $x++) { 
                    echo "<tr>";
                        echo "<td>".$ytd[$region][$x]["month"]."</td>";
                        echo "<td>".$ytd[$region][$x]["channel_brand"]."</td>";
                        echo "<td>".$ytd[$region][$x]["channel_feed"]."</td>";
                        echo "<td>".$ytd[$region][$x]["sales_rep"]."</td>";
                        echo "<td>".$ytd[$region][$x]["agency"]."</td>";
                        echo "<td>".$ytd[$region][$x]["client"]."</td>";
                        echo "<td>".$ytd[$region][$x]["currency"]."</td>";
                        echo "<td>".$ytd[$region][$x]["charge_type"]."</td>";
                        echo "<td>".$ytd[$region][$x]["client_product"]."</td>";
                        echo "<td>".$ytd[$region][$x]["campaign_reference"]."</td>";
                        echo "<td>".$ytd[$region][$x]["order_reference"]."</td>";
                        echo "<td>".$ytd[$region][$x]["impression_duration"]."</td>";
                        echo "<td>".$ytd[$region][$x]["gross_revenue"]."</td>";
                        echo "<td>".$ytd[$region][$x]["num_of_spot"]."</td>";
                        echo "<td>".$ytd[$region][$x]["net_revenue"]."</td>";
                    echo "</tr>";
                }                    
            }
            echo "</table>";
            */
        ?>
    </div>

<script type="text/javascript">
    
    jQuery(document).ready(function(){
        
        $('#someSelect').change(function (){
            var value = $(this).val();
            alert(value);
        })
        
    });

</script>

@endsection



    

    