@extends('layouts.planningMirror')

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
    
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col">
                <center>
                    <span style="font-weight: bold;font-size: 18px;"> Contact Us 
                        <a href="mailto:d_ads@discovery.com">Ad Sales Portal</a>
                    </span>
                </center>
            </div>
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



    

    
