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

		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>
		
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
				<a class="navbar-brand" href="{{ route('home')}}">
					<img src="/wMinilogo.png" width="225" height="60" <?php echo(date("U")); ?>>
					<!--<span class="navbar-text"> |ADS - DLA </span>-->
				</a>
			</nav>

			<a class="navbar-brand" ></a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
			</button>

			<div class="collapse navbar-collapse" id="navbarSupportedContent">
				<ul class="navbar-nav mr-right" style="margin-left: 92.5%;">
					<a class="nav-link" href="{{ route('autenticate') }}" id="navbarDropdown" role="button" aria-haspopup="true" aria-expanded="false"> Login </a>                            
				</ul>
			</div>
		</nav>
		<div id="app"></div>
		
		@yield('content')

		<script type="text/javascript">
			function ShowLoading(e) {
				var div = document.createElement('div');
				var img = document.createElement('img');
				img.src = '/loading.gif';
				div.innerHTML = "Processing Request...<br/>";
				div.style.cssText = 'position: absolute; left: 0px; top:0px;  margin:0px;        width: 100%;        height: 100%;        display:block;        z-index: 99999;        opacity: 0.9;        -moz-opacity: 0;        filter: alpha(opacity = 45);        background: white;        background-image: url("/Loading.gif");        background-repeat: no-repeat;        background-position:50% 50%;        text-align: center;        overflow: hidden;   font-size:30px;     font-weight: bold;        color: black;        padding-top: 20%';
				div.appendChild(img);
				document.body.appendChild(div);
				return true;
				// These 2 lines cancel form submission, so only use if needed.
				//window.event.cancelBubble = true;
				//e.stopPropagation();
			}
		</script>

	</body>

</html>
