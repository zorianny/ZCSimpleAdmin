<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
		<meta name="viewport" content="width=device-width, initial-scale=1"/>

		<title>@section('title') ZCSimpleAdmin - Zimbra Community Simple Administrator @show</title>

		<!-- Fonts -->
		<link rel="stylesheet" href="{!! url('css/font-awesome.min.css') !!}"/>
		<link rel="stylesheet" href="{!! url('css/font-googleapis-lato.css') !!}"/>
		<link rel="stylesheet" href="{!! url('css/custom.css') !!}"/>

		<!-- Styles -->
		<link rel="stylesheet" href="{!! url('bootstrap-3.3.6/css/bootstrap.css') !!}"/>

		<style>
			body {
				font-family: 'Lato';
			}

			.fa-btn {
				margin-right: 6px;
			}
		</style>
	</head>
	<body id="app-layout">
		<nav class="navbar navbar-default navbar-static-top">
			<div class="container">
				<div class="navbar-header">

					<!-- Collapsed Hamburger -->
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>

					<!-- Branding Image -->
					<a class="navbar-brand" href="{!! url('/home') !!}">
						ZCSimpleAdmin
					</a>
				</div>

				<div class="collapse navbar-collapse" id="app-navbar-collapse">
					<!-- Left Side Of Navbar >
					<ul class="nav navbar-nav">
						@if (!Auth::guest())
							<li><a href="{!! url('/home') !!}">Cambio de Clave</a></li>
							<li><a href="{!! url('/home') !!}">Creaci&oacute;n de Cuenta</a></li>
						@endif
					</ul>
					-->

					<!-- Right Side Of Navbar -->
					<ul class="nav navbar-nav navbar-right">
						<!-- Authentication Links -->
						@if (Auth::guest())
							<li><a href="{!! url('/login') !!}">Inicio de Sesi&oacute;n</a></li>
							<!--li><a href="{!! url('/register') !!}">Registrarse</a></li-->
						@else
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
									{{ Auth::user()->name }} <span class="caret"></span>
								</a>

								<ul class="dropdown-menu" role="menu">
									<li><a href="{!! url('/logout') !!}"><i class="fa fa-btn fa-sign-out"></i>Salir</a></li>
								</ul>
							</li>
						@endif
					</ul>
				</div>
			</div>
		</nav>

		@yield('content')

		<!-- JavaScripts -->
		<script src="{!! url('css/jquery-2.2.3.min.js') !!}"></script> 
		<script src="{!! url('bootstrap-3.3.6/js/bootstrap.js') !!}"></script>
	</body>
</html>