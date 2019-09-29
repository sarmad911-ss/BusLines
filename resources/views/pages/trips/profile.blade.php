@extends("pages.template")
@section("content")
    <div class="page-title" v-if="trip">
        <div>
            <div class="gray-text">@{{ trip.start_date_f }} – @{{ trip.end_date_f }}</div>
            <div>@{{trip.name}}</div>
        </div>
        <div class="gray-light-text" v-if="trip.last_update">
            letzte Änderungen<br>
            <span v-if="trip.last_update.user">@{{ trip.last_update.user.name }}</span> @{{ trip.last_update.time }}
        </div>
    </div>
    <div class="tabs">
        <div class="tabs__container">
            <span class="tabs__item" @click="setInfoTab" :class="{active: tab=='TripInfoTab'}">Information</span>
            <span class="tabs__item" @click="setSegmentsTab" :class="{active: tab=='SegmentsTab'}">KM</span>
            <span class="tabs__item" @click="setFinancesTab" :class="{active: tab=='FinancesTab'}">Finanzen</span>
            <span class="tabs__item" @click="setDocumentsTab" :class="{active: tab=='DocumentsTab'}">Dokumente</span>
        </div>
        <div class="links-block">
            <span class="gray-light-text" @click="deleteTrip"><i class="icon delete-icon default"></i> Fahrt löschen</span>
            <a :href="trip.store_url" class="link-text"><i class="icon edit-icon default"></i> Fahrt erstellen/bearbeiten</a>
        </div>
    </div>
    <div class="tabs-content">
        <component v-bind:is="tab" :trip="trip"></component>
    </div>

@endsection
@section("js")
    <script> tripId = {{$trip->id}};</script>
    <script src="{{asset("pages/trip/profile.js")}}"></script>
@endsection
@section("css")
    <link rel="stylesheet" href="{{asset("css/tripProfile.css")}}">
@endsection