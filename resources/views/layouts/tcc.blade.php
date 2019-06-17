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
        <title> GEMAS - Genetic Engineering Marketing Analysis Score @yield('title') </title>

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
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <!-- Image and text -->
            <nav class="navbar navbar-dark bg-dark">
                <a class="navbar-brand" href="{{ url('/')}}">
                    <img src="/gemas.png" width="15%">
                    <!--<span class="navbar-text"> |ADS - DLA </span>-->
                </a>
            </nav>

            <a class="navbar-brand" ></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>

            
        </nav>
        <div id="app"></div>
        
        @yield('content')
    </body>

</html>