<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<title>{{ config('app.name', 'Laravel') }}</title>

	<script type="text/javascript">
		const APP_URL  = {!! json_encode(url('/')) !!}
		const ROUTE    = '{!! Route::currentRouteName() !!}'
		const HOME_URL = APP_URL + '/home';
		const BLOG_URL = APP_URL + '/blog';
		const API_URL  = APP_URL + '/api/';
	</script>
	@auth
	<script type="text/javascript">
		const USER_TOKEN = '{!! Auth::user()->api_token !!}'
	</script>
	@endauth

	<!-- Scripts -->
	<script src="{{ asset('js/app.js') }}" defer></script>

	<!-- Fonts -->
	<link rel="dns-prefetch" href="//fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

	<!-- Styles -->
	<link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
	<header class="container-full flex-center position-ref">
		<nav class="navbar navbar-expand-lg navbar-light bg-light">
			<a class="navbar-brand" href="{{ url('/') }}">Teste Prático</a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>

			<div class="collapse navbar-collapse" id="navbarSupportedContent">
				<ul class="navbar-nav mr-auto">
					<li class="nav-item active">
						<a class="navbar-brand" href="{{ url('/blog') }}">Blog</a>
					</li>
					
					@if (Route::has('login'))
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Perfil</a>
							<div class="dropdown-menu" aria-labelledby="navbarDropdown">
								@auth
									<a class="dropdown-item" href="{{ url('/home') }}">{{ Auth::user()->name }}</a>
									<a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>

									<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
										@csrf
									</form>
								@else
									<a class="dropdown-item" href="{{ route('login') }}">Login</a>

									@if (Route::has('register'))
									<a class="dropdown-item" href="{{ route('register') }}">Register</a>
									@endif
								@endauth
							</div>
					@endif
				</ul>
			</div>
		</nav>

</header>

<main>
	@yield('content')
</main>
</body>
</html>
