@extends("pages.template")
@section("content")
<div v-if="bus">
    <div>
        <div class="last-update gray-text">
            letzte Änderungen <br>
            @{{lastUpdate}}
        </div>
        <div class="page-title">
            <span class="bus-number">@{{bus.plate_number}}</span> @{{ bus.type }}
        </div>
    </div>
    <div class="tabs">
        <div class="tabs__container">
            <span @click="setTabInfo" :class="{active:tab=='info'}" class="tabs__item">Information</span>
            <span @click="setTabDates" :class="{active:tab=='dates'}" class="tabs__item">Daten</span>
            <span @click="setTabDocs" :class="{active:tab=='docs'}" class="tabs__item">Dokumente</span>
        </div>
        <div class="links-block">
            <span @click="deleteBus"><i class="icon default marker-icon"></i> Bus löschen</span>
            <a :href="bus.store_url" class="link-text"><i class="icon default marker-icon"></i>Bus erstellen / Bearbeiten</a>
        </div>
    </div>
    <div v-if="tab=='info'" class="info-tab">
        <div class="layout-container">
            <div class="info-table">
                <div class="info-col">
                    <div class="info-table__row">
                        <div class="gray-text">Eigentümer</div>
                        <div class="link-text">@{{bus.owner}}</div>
                    </div>
                    <div class="info-table__row">
                        <div class="gray-text">Nummer</div>
                        <div>@{{bus.plate_number}}</div>
                    </div>
                    <div class="info-table__row">
                        <div class="gray-text">VIN</div>
                        <div>@{{bus.vin}}</div>
                    </div>
                    <div class="info-table__splitter"></div>

                    <div class="info-table__row">
                        <div class="gray-text">Fahrzeugtyp</div>
                        <div>@{{ bus.type }}</div>
                    </div>
                    <div class="info-table__row">
                        <div class="gray-text">Model</div>
                        <div>@{{bus.model}}</div>
                    </div>
                    <div class="info-table__row">
                        <div class="gray-text">Baujahr</div>
                        <div>@{{formattedReleaseDate}}</div>
                    </div>
                </div>
                <div class="info-col">
                    <div class="info-table__row">
                        <div class="gray-text">KM-Stand</div>
                    </div>
                    <div class="info-table__row subrow">
                        <div class="gray-text">Initial</div>
                        <div>@{{bus.mileage_start}}</div>
                    </div>
                    <div class="info-table__row subrow">
                        <div class="gray-text">Aktuell</div>
                        <div>@{{bus.mileage}}</div>
                    </div>
                </div>
            </div>
            <div class="gray-text">
                Zusatzinformationen
            </div>
            <p v-html="bus.misc" class="bus-misc"></p>
        </div>

    </div>
    <div v-if="tab=='dates'" class="dates-tab">
        <div class="layout-container">
            <div v-for="date in bus.dates" :key="date.id">
                <custom-date-time :label="date.name" v-model="date.date"></custom-date-time>
            </div>
        </div>
        <div class="row">
            <button class="button" @click="storeBus">Speichern</button>
        </div>
    </div>
    <div v-if="tab=='docs'" class="docs-tab">
        <bus-document v-for="(file,index) in bus.files" :key="file.id" v-model="bus.files[index]" :bus-id="bus.id" @ondelete="deleteFile"></bus-document>
        <bus-document :bus-id="bus.id" @onnew="newFile"></bus-document>
    </div>
    </div>
@endsection
@section("js")
    <script>
        busId = {{$bus->id}};
    </script>
    <script src="{{asset("pages/bus/profile.js")}}"></script>
@endsection
@section("css")
    <link rel="stylesheet" href="{{asset("css/busProfile.css")}}">
@endsection