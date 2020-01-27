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

    #map {
        width: 100%;
        height: 90%;
        min-height: 90%;
        display: block;
    }

    html, body {
        height: 100%;
    }

    .fill { 
        min-height: 90%;
        height: 90%;
    }

    .vcenter {
        margin-top: 45%;
        margin-bottom: 45%;
    }

</style>

@endsection

@section('content')
    
    <div class="container-fluid fill px-4 d-table" style='margin-top:-5%;'>
        <div class="row">
            <div id="map" class="aligh-middle" >
                <center>
                    <img src="\warningImg.png" style="width: 65%; margin-top: 10%; height: auto;">
                    
                </center>                
                <center>
                    <span style="font-weight: bold;font-size: 18px; margin-top:-5%;"> Contact Us 
                        <a href="mailto:d_ads@discovery.com">Ad Sales Portal</a>
                    </span>
                </center>
            </div> <!-- This one wants to be 100% height -->
        </div>
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



    

    
