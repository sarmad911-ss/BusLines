@extends("pages.template")
@section("content")
    <div class="page-title">
        @if(!empty($trip->id))
            Fahrt bearbeiten
        @else
            Fahrt hinzufügen
        @endif
    </div>

    <div class="tabs">
        <div class="tabs__item" :class="{active: tab==='StoreWaypointsTab'}" @click="setStoreWaypointsTab">Route und Preis</div>
        <div class="tabs__item" :class="{active: tab==='StoreInfoTab'}" @click="setStoreInfoTab">Fahrten-Daten</div>
    </div>
    <component :is="tab" @onCalc="onCalcMap" :trip="trip" @setinfotab="setStoreInfoTab"></component>
@endsection
@section("js")
    @if(!empty($trip->id))
    <script>trip_id={{$trip->id}};</script>
    @endif
    <script src="{{asset("pages/trip/store.js")}}"></script>
@endsection
@section("css")
    <meta name="google_key" content="{{ env("GOOGLE_API_KEY","") }}">
    <link rel="stylesheet" href="{{asset("css/tripStore.css")}}">
@endsection
