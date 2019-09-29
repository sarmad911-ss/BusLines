@extends("pages.template")
@section('content')
    <div class="page-title">
        <div>Fahrten</div>
        <div class="filter-block">
            <div style="display: flex; align-items: center">
                <span class="icon vector-arrow-icon medium" @click="minDay"></span>
                <custom-date-time class="date-block" :placeholder="'von'" :no-time="true" v-model="searchData.start_date"></custom-date-time>
                <span class="icon vector-arrow-icon medium reverse" style="margin-left: 0px" @click="addDay"></span>
            </div>
            <custom-select :placeholder="'Fahrten Status'" :items="statuses" class="status-block" v-model="searchData.status_id"></custom-select>
            <custom-input :placeholder="'Fahrt-Suche'" :right-icon="'search-icon'" class="search-block" v-model="searchData.search"></custom-input>
            <a href="{{route("storeTripView")}}" class="button">Fahrt hinzufügen</a>
        </div>
    </div>
    <div class="custom-table">
        <div class="custom-table__head">
            <div></div>
            <div>
                №
            </div>
            <div>
                Status
            </div>
            <div>
                Route / Name der Fahrt
            </div>
            <div>
                Zeitraum
            </div>
            <div>
                Busse
            </div>
            <div>
                Kunde
            </div>
            <div>
                Zahlung
            </div>
        </div>
        <div class="custom-table__body custom-scroll" @scroll="scroll">
            <a class="custom-table__row" v-for="trip in trips" v-if="trips.length" :href="trip.profile_url">
                <div class="day-block" :class="trip.calendar_type">
                    <div class="day-name">@{{ trip.calendar_day }}</div>
                    <div class="date">@{{ trip.calendar_date }}</div>
                </div>
                <div class="gray-light-text">
                    @{{ trip.id }}
                </div>
                <div class="link-text">@{{ trip.status.name }}</div>
                <div>@{{ trip.name }}</div>
                <div>
                    <div>@{{ trip.start_date_f }}</div>
                    <div>@{{ trip.end_date_f }}</div>
                </div>
                <div class="bus-numbers">
                    <div class="numbers">
                        <div class="bus-number" v-for="number in getNumbersBus(trip).numbers">@{{number}}</div>
                    </div>
                    <div class="additional" v-if="getNumbersBus(trip).additional">@{{ getNumbersBus(trip).additional }}</div>
                </div>
                <div class="link-text">
                    <span v-if="trip.client">@{{ trip.client.name }}</span>
                </div>
                <div>
                    <div>@{{ trip.paid }} € <span class="gray-light-text">Ausgleich</span></div>
                    <div>@{{ trip.cost }} € <span class="gray-light-text">Gesamt</span></div>
                </div>
            </a>
            <div v-if="!trips.length" class="empty-table">
                Keine aktiven Reisen
            </div>
        </div>
    </div>
@endsection
@section("css")
    <link rel="stylesheet" href="{{asset("css/tripsList.css")}}">
@endsection
@section("js")
    @if(session()->has("notification"))
        <script>
            openNotification = true;
        </script>
        <?php session()->forget("notification"); ?>
    @endif
    <script src="{{asset("pages/tripsList.js")}}"></script>
@endsection
