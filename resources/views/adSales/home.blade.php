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

</style>

@endsection

@section('content')
    
    <div class="container-fluid fill px-4">
        <div id="map">
            <center>
                <img src="\logo.png" style="max-width: 100%; margin-top: 15%; height: auto;">
            </center>
        </div> <!-- This one wants to be 100% height -->
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



    

    