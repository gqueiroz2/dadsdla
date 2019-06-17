
@extends('layouts.tcc')

@section('content')
<div class="container" style="margin-top: 3.5cm;">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header bg-dark">
                    <center>
                        <span><h1> &nbsp; </h1></span>
                    </center>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form action="" method="post">
                        @csrf

                        <div class="container">
                            <div class="row justify-content-center">
                                <div class="col">
                                    <span><b> Insira uma url: </b></span>
                                </div>
                            </div>
                            <div class="row justify-content-center">
                                <div class="col">
                                    <input type="text" class="form-control" style="width: 100%;">
                                </div>
                                <div class="col-3">
                                    <input type="submit" class="btn btn-dark" style="width: 100%;" value="Gerar Nota">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
