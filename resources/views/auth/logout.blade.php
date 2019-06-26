@extends('layouts.logout')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header" style="text-align: center;">You have logged out</div>

                <div class="card-body">
                    <form method="Get" action="{{ route('loginGet') }}">
                        <center>
                            <input type="submit" style="width: 50%;" class="btn btn-primary" value="Continue">
                        </center>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
