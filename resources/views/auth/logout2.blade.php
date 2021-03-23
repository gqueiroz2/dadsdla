@extends('layouts.logout')

@section('content')

<div class="container">
    <div class="row justify-content-center" style="margin-top: 7.5%;">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header" style="text-align: center; font-weight: bold; font-size: 20px;">You are logged off</div>
                    <div class="card-body">
                        <center>
		                    @if (session('msg'))
                                <div class="alert alert-success" role="alert">
                                    <span> {{ session('msg') }} </span>  
                                </div>                                
                            @endif
                            <p style="width: 50%;">
                                Discovery Communications, LLC uses your network username and password to login to D|ADS. Continue to login to D|ADS through your network.
                            </p>
	                        <form method="Get" action="{{ route('autenticate2') }}">
                                <input type="submit" style="width: 50%;" class="btn btn-primary" value="Continue">
    	                   </form>
                        </center>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
