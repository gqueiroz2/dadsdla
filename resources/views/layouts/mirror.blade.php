<!DOCTYPE html>
<html>
    <head>
        <?php 
            $userName = Request::session()->get('userName'); 
            $userLevel = Request::session()->get('userLevel');
        ?>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title> D|ADS DLA - @yield('title') </title>

        <script src="https://code.jquery.com/jquery-3.3.1.min.js" 
                integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
                crossorigin="anonymous">
        </script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" 
                integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous">
        </script>
        <link href="/css/app.css" rel="stylesheet">
        <link href="/css/root.css" rel="stylesheet">
        <link href="/twbs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet"/>        
        <script src="/components/jquery/jquery.min.js"></script>        
        <script src="/twbs/bootstrap/dist/js/bootstrap.min.js"></script>
        <script src="/js/base.js"></script>
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <!-- David Stutz bootstrap-multiselect -->
        <script src="/bootstrap-select/dist/js/bootstrap-select.js"></script>
        <link rel="stylesheet" href="/bootstrap-select/dist/css/bootstrap-select.css">
        <!-- Include the plugin's CSS and JS:-->
        <style type="text/css">
            .bootstrap-select{
                border-style: solid;
                border-color: #d9d9d9;
                border-width: 1px;
                border-radius: 3px;
            }

            select{
                background: #fafafa !important;
            }
        </style>
        @yield('head')
        
    </head>
    <body>       
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <!-- Image and text -->
            <nav class="navbar navbar-light bg-light">
                <a class="navbar-brand" href="{{ url('/')}}">
                    <img src="/logo.png" width="225" height="60">
                    <!--<span class="navbar-text"> |ADS - DLA </span>-->
                </a>
            </nav>

            <a class="navbar-brand" ></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <?php $userName = Request::session()->get('userName'); ?>


            @if($userName != null)
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item dropdown">
                            <a class="nav-link" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Results </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('resultsResumeGet') }}"> Resume </a>
                                <a class="dropdown-item" href="{{ route('resultsMonthlyGet') }}"> Monthly </a>
                                <a class="dropdown-item" href="{{ route('resultsQuarterGet') }}"> Quarter </a>
                                <a class="dropdown-item" href="{{ route('resultsShareGet') }}"> Share (Channel/Executive) </a>
                                <a class="dropdown-item" href="{{ route('resultsYoYGet') }}"> YoY </a>
                                <a class="dropdown-item" href="{{ route('resultsMonthlyYoYGet') }}"> Monthly YoY </a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Performance </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="#"> Core </a>
                                <a class="dropdown-item" href="#"> Executive </a>                                
                                <a class="dropdown-item" href="#"> Quarter </a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Dashboards </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="#"> Brand </a>
                                <a class="dropdown-item" href="#"> Market </a>                                
                                <a class="dropdown-item" href="#"> Churn </a>                                
                                <a class="dropdown-item" href="#"> Overview </a>
                            </div>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="#"> Ranking <span class="sr-only">(current)</span></a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="#"> Header / CMAPS <span class="sr-only">(current)</span></a>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> P&R </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('pacingReportGet') }}"> Pacing Report </a>
                                <!--<a class="dropdown-item" href="#"> Market </a>-->
                            </div>
                        </li>

                    </ul>    

                    <ul class="navbar-nav mr-right" style="margin-right: 2.5%;">
                        <li class="nav-item dropdown dropleft">
                            <a class="nav-link" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" data-flip="true" aria-haspopup="true" aria-expanded="false"> {{$userName}} </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <input type="submit" class="dropdown-item" value="Logout">
                                </form>
                                @if($userLevel == "SU")
                                    <a class="dropdown-item" href="{{ route('dataManagementHomeGet') }}"> Data Management </a>
                                @endif
                            </div>
                        </li>
                    </ul>    
                </div>
            @else

            @endif
        </nav>
        <div id="app"></div>
        
        @if(! is_null($userName))
            @yield('content')
        @else
            @yield('contentLogout')
        @endif
    </body>

</html>
