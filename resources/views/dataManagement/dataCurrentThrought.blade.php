@extends('layouts.mirror')

@section('title', '@')

@section('head')

@endsection

@section('content')
    <div class="container-fluid">
        <div class="row mt-5 justify-content-center">
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-header">
                        <center><h4><b> Data Current Throught</b> </h4></center>
                    </div>
                    <div clasline="card-body">
                        <div class="container-fluid">

                            @if(isset($rtr))
                                <?php
                                    var_dump($rtr);
                                ?>
                            @endif

                            <form action="{{ route('dataCurrentThroughtP') }}" runat="server"  onsubmit="ShowLoading()" method="POST">
                            @csrf

                                <div class="row justify-content-center">          
                                    <div class="col">       
                                        <div class="form-group">
                                            <label><b> CMAPS: </b></label> 
                                            @if($errors->has('cmapsInfo'))
                                                <label style="color: red;">* Required</label>
                                            @endif
                                            <input type="date" class="form-control" name="cmapsInfo" value="{{$newList['cmaps']}}">
                                        </div>
                                    </div>
                                </div>

                                <div class="row justify-content-center">          
                                    <div class="col">       
                                        <div class="form-group">
                                            <label><b> Discovery CRM: </b></label> 
                                            @if($errors->has('crmInfo'))
                                                <label style="color: red;">* Required</label>
                                            @endif
                                            <input type="date" class="form-control" name="crmInfo" value="{{$newList['sf']}}">
                                        </div>
                                    </div>
                                </div>
{{--
                                <div class="row justify-content-center">          
                                    <div class="col">       
                                        <div class="form-group">
                                            <label><b> FreeWheel: </b></label> 
                                            @if($errors->has('freewheelInfo'))
                                                <label style="color: red;">* Required</label>
                                            @endif
                                            <input type="date" class="form-control" name="freeWheelInfo" value="{{$newList['fw']}}">
                                        </div>
                                    </div>
                                </div>
--}}
                                <div class="row justify-content-center">          
                                    <div class="col">       
                                        <div class="form-group">
                                            <label><b> BTS: </b></label> 
                                            @if($errors->has('btsInfo'))
                                                <label style="color: red;">* Required</label>
                                            @endif
                                            <input type="date" class="form-control" name="btsInfo" value="{{$newList['bts']}}">
                                        </div>
                                    </div>
                                </div> 

                                 <div class="row justify-content-center">          
                                    <div class="col">       
                                        <div class="form-group">
                                            <label><b> ALEPH / WBD: </b></label> 
                                            @if($errors->has('alephInfo'))
                                                <label style="color: red;">* Required</label>
                                            @endif
                                            <input type="date" class="form-control" name="alephInfo" value="{{$newList['aleph']}}">
                                        </div>
                                    </div>
                                </div> 

                               {{-- <div class="row justify-content-center">          
                                    <div class="col">       
                                        <div class="form-group">
                                            <label><b> Insights: </b></label> 
                                            @if($errors->has('insightsInfo'))
                                                <label style="color: red;">* Required</label>
                                            @endif
                                            <input type="date" class="form-control" name="insightsInfo" value="{{$newList['insights']}}">
                                        </div>
                                    </div>
                                </div>  
                                --}}
                                @if(session('currentThrought'))
                                    <div class="alert alert-info">
                                        {{ session('currentThrought') }}
                                    </div>
                                @endif

                                <div class="row justify-content-center">          
                                    <div class="col">       
                                        <div class="form-group">
                                            <input type="submit" class="btn btn-primary" style="width: 100%;">
                                        </div>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



@endsection



    

    