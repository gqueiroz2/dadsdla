@extends('layouts.mirror')
@section('title', 'Data Current Through')
@section('head')
    <?php include(resource_path('views/auth.php')); ?>
@endsection
@section('content')    

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
                                @for($c=0;$c<sizeof($current);$c++)

                                        <div class="col">
                                            <div class="card">
                                                <div class="card-header">
                                                    <center>
                                                        <span style="font-size: 18px; font-weight: bold;"> {{$current[$c]}}: </span>
                                                    </center>
                                                </div>
                                                <div class="card-body">
                                                    <center>
                                                        <?php 
                                                            $tmp = $base->breakTimeStamp($updateTime[$c]);
                                                        ?>
                                                        @if($tmp)
                                                            <span style="font-size: 15px; font-weight: bold;"> 
                                                                Date : {{ $tmp["date"] }}
                                                            </span><br>
                                                            <span style="font-size: 14px; font-weight: bold;"> 
                                                                Time : {{ $tmp["time"] }}
                                                            </span>
                                                        @else
                                                            <span style="font-size: 15px; font-weight: bold;"> 
                                                                There is no data !!!
                                                            </span>
                                                        @endif
                                                    </center>
                                                </div>            
                                            </div>
                                        </div>


                                @endfor
                                    {{--
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
                                    --}}
                            </div>
                        </div>
                    </div>            
                </div>
            </div>
        </div>
    </div>
@endsection



    

    