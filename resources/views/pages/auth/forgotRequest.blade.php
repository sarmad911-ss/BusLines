@extends("pages.auth.template")
@section("content")
	<change-password-form :forgot-key="'{{ $key }}'"></change-password-form>
@endsection
@section("js")
<script src="{{asset("pages/auth/changePassword.js")}}"></script>
@endsection