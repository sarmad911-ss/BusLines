@extends("pages.template")
@section("content")
    <div class="page-title">
        Bus-Fuhrpark
        <a href="{{route("storeDepotView")}}" class="button">Bus hinzufügen</a>
    </div>
    <div class="custom-table" v-if="busses">
        <div class="custom-table__head">
            <div>Nummer</div>
            <div>Fahrzeugtyp</div>
            <div>Model</div>
            <div>Sitzplätze</div>
            <div>Eigentümer</div>
        </div>
        <div class="custom-table__body">
            <a class="custom-table__row" v-for="bus in busses" :href="bus.profile_url">
                <div><div class="bus-number">@{{bus.plate_number}}</div></div>
                <div><div class="black-text">@{{bus.type}}</div></div>
                <div>@{{bus.model}}</div>
                <div>@{{bus.seats_quantity}} Orte</div>
                <div><div class="link-text">@{{bus.owner}}</div></div>
            </a>
        </div>
    </div>
    <pagination v-bind:meta="meta" @ondata="onData" v-if="meta"></pagination>
    @if(session()->has("notification"))
        <notification :text="'{{session()->get("notification")}}'" v-model="showNotification"></notification>
    @endif
@endsection
@section("js")
    @if(session()->has("notification"))
        <script>
            openNotification = true;
        </script>
    @endif
    <script src="{{asset("pages/bus/list.js")}}"></script>
@endsection
@section("css")
    <link rel="stylesheet" href="{{asset("css/buslist.css")}}">
@endsection
<?php session()->forget("notification"); ?>