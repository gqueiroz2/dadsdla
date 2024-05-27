@extends('layouts.mirror')

@section('head')
    <?php include(resource_path('views/auth.php')); ?>
@endsection

@section('contentLogout')

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">{{ __('Login') }}</div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('loginPost') }}" runat="server"  onsubmit="ShowLoading()">
                                @csrf

                                <div class="form-group row">
                                    <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                                    <div class="col-md-6">
                                        <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>

                                        @if ($errors->has('email'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                                    <div class="col-md-6">
                                        <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                        @if ($errors->has('password'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('password') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row mb-0">
                                    <label for="password" class="col-md-4 col-form-label text-md-right">
                                        &nbsp;
                                    </label>
                                    <div class="col-md-6">
                                        <button type="submit" class="btn btn-primary" style="width: 100%;">
                                            {{ __('Login') }}
                                        </button>
                                        <a class="btn btn-link" href="{{ route('forgotPasswordGet') }}" style="float:right;">
                                            {{ __('Forgot Your Password?') }}
                                        </a>
                                    </div>
                                </div>
                                
                                <br>
                                
                                <div class="row justify-content-center">
                                    <div class="col">
                                        @if(session('error'))
                                            <div class="alert alert-danger">
                                                {{ session('error') }}
                                            </div>
                                        @endif

                                        @if(session('response'))
                                            <div class="alert alert-info">
                                                {{ session('response') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
@endsection

@section('content')

    <div class="container-fluid fill px-4 d-table">
        <div id="map" class="aligh-middle" >
            <center>
                <img src="\wPortalLogo.png" style="max-width: 35%; margin-top: 10%; height: auto;" <?php echo(date("U")); ?>>
            </center>
        </div> <!-- This one wants to be 100% height -->
    </div>
@endsection