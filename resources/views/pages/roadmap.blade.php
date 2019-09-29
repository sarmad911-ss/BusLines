@extends("pages.template")
@section("css")
	<meta name="google_key" content="{{ env("GOOGLE_API_KEY","") }}">
@endsection
@section("content")
    <div class="page-title" style="display: flex;">
        Fahrtenkalkulation
        <a href="{{route("listCalculationsView")}}" class="link-text" style="margin-left: 20px;"><i class="icon medium calc-icon no-hover" style="margin-right: 6px;"></i>История рассчетов</a>
    </div>
	<roadmap></roadmap>
@endsection
@section("js")
	<script src="{{asset("pages/roadmap.js")}}"></script>
@endsection
