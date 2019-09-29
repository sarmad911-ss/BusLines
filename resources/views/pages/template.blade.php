<html lang="{{App::getLocale()}}" class="custom-scroll">
<head>
	<meta name="viewport" content="initial-scale=1.0, user-scalable=no">
	<meta charset="utf-8">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<link rel="stylesheet" href="{{asset("css/app.css")}}">
	<link rel="stylesheet" href="{{asset("css/loader.css")}}">
    <title>Bus-Lines.de</title>
    @include("components.favicon")
	@yield("css")
</head>
<body>
	<main class="container">
		@if(session()->has("user"))
			<section class="sidebar-container">
				<div class="sidebar">
					<a href="{{route("listTripView")}}">
						<img src="{{\App\Models\Settings::get("logo",asset("/images/logo.png"))}}" alt="" class="logo">
					</a>
					<nav>
						<a href="{{route("listTripView")}}" @if(Route::current()->getPrefix()=="/trips") class="active" @endif onclick="showLoader()">Fahrten</a>
						<a href="{{route("roadmapPage")}}" @if(Route::current()->getName()=="roadmapPage") class="active" @endif onclick="showLoader()">Fahrtenkalkulation</a>
						<a href="{{route("listCalculationsView")}}" @if(Route::current()->getName()=="listCalculationsView") class="active" @endif onclick="showLoader()">Рассчеты</a>
						<a href="{{route("depotView")}}" @if(Route::current()->getPrefix()=="/bus-depot") class="active" @endif onclick="showLoader()">Bus-Fuhrpark</a>
						<a href="{{route("listUsersView")}}" @if(Route::current()->getPrefix()=="/users") class="active" @endif onclick="showLoader()">Benutzer</a>
						<a href="{{route("financesListView")}}" @if(Route::current()->getName()=="financesListView") class="active" @endif onclick="showLoader()">Finanzen</a>
						<a href="{{route("settingsListView")}}" @if(Route::current()->getPrefix()=="/settings") class="active" @endif onclick="showLoader()">Einstellungen</a>
					</nav>
					<div class="user-block">
						<div class="user-block__avatar">
							<img src="{{session()->get("user")->photo_url}}" alt="">
						</div>
						<div class="user-block__name">
							{{session()->get("user")->name}}
						</div>
						<div class="user-block__email">
							{{session()->get("user")->email}}
						</div>
						<a class="user-block__logout" href="/logout">
							<i class="icon default logout-icon"></i>Abmelden
						</a>
					</div>
				</div>
			</section>

		@endif
		<section id="app" class="page-content">
			@yield("content")
			<div id="loader" class="show">
				<div class="loader_animation"></div>
			</div>
		</section>
	</main>

</body>
<script src="{{asset('js/app.js')}}"></script>
@yield("js")
</html>
