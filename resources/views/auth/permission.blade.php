@extends('layouts.logout')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header" style="text-align: center; font-weight: bold; font-size: 20px;">Permission denied</div>
                <div class="card-body">
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-6">
                                <div>
                                    You don't have the permission to access D|ADS, please contact your Regional Manager or send an e-mail to
                                    <a href="mailto:d_ads@discovery.com" style="display: inline-block;" >D|ADS</a>.
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
