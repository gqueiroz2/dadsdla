@extends('layouts.mirror')

@section('title', '@')

@section('head')
    <script src="/js/viewer.js"></script>
    <?php include(resource_path('views/auth.php')); ?>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <form method="POST" action="{{ route('resultsResumePost') }}" runat="server"  onsubmit="ShowLoading()">
                    @csrf
                    <div class="row">                        

                        <div class="col">
                            <label class="labelLeft"><span class="bold"> Region: </span></label>
                            @if($errors->has('region'))
                                <label style="color: red;">* Required</label>
                            @endif

                            @if($userLevel == 'L0' || $userLevel == 'SU')
                                {{$render->region($region)}}                            
                            @else
                                {{$render->regionFiltered($region, $regionID, $special)}}
                            @endif
                        </div>
                        <div class="col">
                            <label class="labelLeft"><span class="bold"> Source: </span></label>
                            @if($errors->has('brand'))
                                <label style="color: red;">* Required</label>
                            @endif
                            {{$render->sourceDataBase()}}
                        </div>
                        
                        <div class="col">
                            <label class="labelLeft"><span class="bold"> PI: </span></label>
                            @if($errors->has('brand'))
                                <label style="color: red;">* Required</label>
                            @endif
                            {{$render->piNumber($brand)}}
                        </div>
                        
                        <div class="col">
                            <label class="labelLeft"><span class="bold"> Year: </span></label>
                            @if($errors->has('year'))
                                <label style="color: red;">* Required</label>
                            @endif
                            {{$render->year($regionID)}}                    
                        </div>  
                        <div class="col">
                            <label class='labelLeft'><span class="bold">Months:</span></label>
                            @if($errors->has('month'))
                                <label style="color: red;">* Required</label>
                            @endif
                            {{$render->months()}}
                        </div>
                        <div class="col">
                            <label class="labelLeft"><span class="bold"> Brand: </span></label>
                            @if($errors->has('brand'))
                                <label style="color: red;">* Required</label>
                            @endif
                            {{$render->brand($brand)}}
                        </div>
                    
                        <div class="col">
                            <label class='labelLeft'><span class="bold">Sales Rep:</span></label>
                            @if($errors->has('salesRep'))
                                <label style="color: red;">* Required</label>
                            @endif
                            {{$render->salesRep()}}
                        </div>                        
                    </div>

                    <div class="row">
                        <div class="col">
                            <label class="labelLeft"><span class="bold"> Currency: </span></label>
                            @if($errors->has('currency'))
                                <label style="color: red;">* Required</label>
                            @endif
                            {{$render->currency($currency)}}
                        </div>
                        <div class="col">
                            <label class="labelLeft"><span class="bold"> Value: </span></label>
                            @if($errors->has('value'))
                                <label style="color: red;">* Required</label>
                            @endif
                            {{$render->value2()}}
                        </div>
                        <div class="col">
                            <label> &nbsp; </label>
                            <input type="submit" value="Generate" class="btn btn-primary" style="width: 100%;">     
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row justify-content-end mt-2">
            <div class="col" style="color: #0070c0;font-size: 22px">
                <span style="float: right; margin-right: 2.5%;"> Data Current Through: DD-MM-YY (<?php echo date('d \/ m \/ y'); ?>) </span>
            </div>

            
        </div>

        <div id="vlau"></div>
    </div>


@endsection



    

    