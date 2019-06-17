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
        <div class="row justify-content-center">
            <div class="col">
                <img src="\logo.png">
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



    

    