<html lang="{{App::getLocale()}}">
<head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{asset('css/auth.css')}}">
    <title>{{$title ?? "Einloggen"}}</title>
    @yield("css")
</head>
<body>
<main id="app" >
    <section class="container">
        <img src="{{\App\Models\Settings::get("logo",asset("/images/logo.png"))}}" alt="" class="logo">
        <div class="form-block">
            @yield("content")
        </div>
    </section>
</main>
<script src="{{asset('js/app.js')}}"></script>
@yield("js")

</body>
</html>