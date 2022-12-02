@extends('layouts.mirror')

@section('title', 'Relationship Base')

@section('head')
    <script src="/js/resultsResume.js"></script>
    <?php include(resource_path('views/auth.php')); ?>

    <style type="text/css">
        
        th,td{
            border-style: solid;
            border-color: black;
            border-width: 1px;

        }

    </style>

@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <form method="POST" action="{{ route('relationshipPost') }}" runat="server"  onsubmit="ShowLoading()">
                    @csrf
                    <div class="row">
                        <div class="col-sm">
                            <label class="labelLeft"><span class="bold"> Region: </span></label>
                            @if($errors->has('region'))
                                <label style="color: red;">* Required</label>
                            @endif

                            @if($userLevel == 'L0' || $userLevel == 'SU')
                                {{$render->region($region)}}                            
                            @elseif($userLevel == '1B')
                                {{$render->regionFilteredReps($region, $regionID)}}
                            @else
                                {{$render->regionFiltered($region, $regionID,$special)}}
                            @endif
                        </div>
                        
                        <div class="col-sm">
                            <label class="labelLeft"><span class="bold"> Type: </span></label>
                            @if($errors->has('type'))
                                <label style="color: red;">* Required</label>
                            @endif
                                <select id='type' name='type' style='width:100%;' class='form-control'>
                                    <option value='agency'> Agency </option>
                                    <option value='client'> Client </option>                                    
                                </select>
                        </div>
                        
                        <div class="col-sm">
                            <label> &nbsp; </label>
                            <input type="submit" value="Generate" class="btn btn-primary" style="width: 100%;">     
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row justify-content-end mt-2">
            <div class="col" style="color: #0070c0;font-size: 22px">
                <span style="float: right;"> Relationship </span>
            </div>
            <div class="col-1" style="color: #0070c0;font-size: 22px">
            </div>
        </div>

        <div class="row justify-content-center mt-2">
            <div class="col-10">
                {{ $render->construct($type,$structure) }}
            </div>            
        </div>
    </div>

    

@endsection



    

    