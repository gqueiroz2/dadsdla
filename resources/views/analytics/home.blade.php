@extends('layouts.mirror')

@section('title', '@')

@section('head')


@endsection

@section('content')
    

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



@endsection



    

    