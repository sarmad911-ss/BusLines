@extends("pages.auth.template")
@section("content")
	<component v-bind:is="component" @change-form="changeForm"></component>
@endsection
@section("js")
<script src="{{asset("pages/auth/login.js")}}"></script>
@endsection