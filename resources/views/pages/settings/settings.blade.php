@extends("pages.template")
@section("css")
    <meta name="google_key" content="{{ env("GOOGLE_API_KEY","") }}">
    <link rel="stylesheet" href="{{asset("/css/settings.css")}}">
@endsection

@section("content")
	<div class="page-title">Einstellungen</div>
	<div class="tabs">
		<span @click="showCompanySettings" :class="{active:tab==='company'}" class="tabs__item">Firmendaten</span>
		<span @click="showCalcSettings" :class="{active:tab==='calc'}" class="tabs__item">Zahlen für Berechnungen</span>
	</div>
	<component :is="component"></component>
	{{--<company-settings-list v-if="tab=='company'"></company-settings-list>--}}
	{{--<calc-settings-list v-if="tab=='calc'"></calc-settings-list>--}}
@endsection
@section("js")
	<script src="{{asset("pages/settings.js")}}"></script>
@endsection
