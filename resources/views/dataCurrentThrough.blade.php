@extends('layouts.mirror')
@section('title', 'Data Current Through')
@section('head')
    <?php include(resource_path('views/auth.php')); ?>
@endsection
@section('content')    

    <?php

    var_dump($regionID);
    var_dump($userLevel);

    ?>

    <div class="container-fluid fill px-4">
        <div class="row justify-content-center" style="margin-top: 5%;">
            <div class="col-10">
                <div class="card">
                    <div class="card-header">
                        <center>
                            <span style="font-size: 18px; font-weight: bold;"> Reports Data Current Throught: </span>
                        </center>
                    </div>
                    <div class="card-body">
                        <div class="container-fluid">
                            <div class="row justify-content-center">
                                <div class="col">
                                    <div class="card">
                                        <div class="card-header">
                                            <center>
                                                <span style="font-size: 18px; font-weight: bold;"> IBMS: </span>
                                            </center>
                                        </div>
                                        <div class="card-body"></div>            
                                    </div>
                                </div>
                                @if($regionID == 1 || $userLevel == "SU")
                                    <div class="col">
                                        <div class="card">
                                            <div class="card-header">
                                                <center>
                                                    <span style="font-size: 18px; font-weight: bold;"> CMAPS: </span>
                                                </center>
                                            </div>
                                            <div class="card-body"></div>            
                                        </div>
                                    </div>
                                @endif
                                <div class="col">
                                    <div class="card">
                                        <div class="card-header">
                                            <center>
                                                <span style="font-size: 18px; font-weight: bold;"> Digital: </span>
                                            </center>
                                        </div>
                                        <div class="card-body"></div>            
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>            
                </div>
            </div>
        </div>
    </div>
@endsection



    

    