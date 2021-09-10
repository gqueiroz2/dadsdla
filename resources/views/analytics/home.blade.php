@extends('layouts.mirror')

@section('title', '@')

@section('head')


@endsection

@section('content')
    
@csrf 
    <div class="container-fluid mt-5">
        <div class="row justify-content-center mt-5">
            <div class="col-sm-10">
                <div class="card">
                    <div class="card-header">
                        <center><h4> <b> Analytics </b> </h4></center>
                    </div>
                    <div class="card-body">
                        @if($info)
                            {{ $aR->panel($info) }}
                        @else
                            <center>
                                <h4>
                                    
                                    NO DATA

                                </h4>
                            </center>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php  
    if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')   
         $url = "https://";   
    else  
         $url = "http://";   
    // Append the host(domain name, ip) to the URL.   
    $url.= $_SERVER['HTTP_HOST'];   
    
    // Append the requested resource location to the URL   
    $url.= $_SERVER['REQUEST_URI'];    
      
    echo $url;  
  ?>   

@endsection



    

    