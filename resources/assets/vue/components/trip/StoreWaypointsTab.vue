<template>
    <div class="roadmap-tab">
        <div class="waypoints-column">
            <div class="bordered">
                <div>
                    <div>
                        <p>Тип поездки</p>
                        <div class="radio-buttons">
                            <button class="button" :class="{selected:type_id===1}" @click="type_id=1">Тур</button>
                            <button class="button" :class="{selected:type_id===2}" @click="type_id=2">Транспорт</button>
                        </div>
                    </div>
                    <div v-if="type_id===2">
                        <p>Стадия</p>
                        <div class="radio-buttons">
                            <button class="button" :class="{selected:stage==='thitherward'}" @click="switchStage('thitherward')">Туда</button>
                            <button class="button" :class="{selected:stage==='backward'}" @click="switchStage('backward')">Обратно</button>
                        </div>
                    </div>
                </div>
                <div v-show="(stage==='thitherward' && type_id===2) || type_id===1">
                    <div v-if="start_waypoints.length">
                        <div class="waypoint-row waypoint-titles">
                            <div>Пункт сбора</div>
                            <div>Дата сбора</div>
                        </div>
                        <div class="waypoint-row" v-for="(waypoint,i) in start_waypoints">
                            <div class="waypoint-input-group">
                                <div class="custom-input__group">
                                    <div class="custom-input__addon left">
                                        <i class="icon default marker-icon"></i>
                                    </div>
                                    <input type="text" :value="waypoint.name" :ref="`startWaypoint${i}`">
                                </div>
                                <div class="waypoint-icons">
                                    <i class="icon default remove-icon gray__theme" @click="removeStartWaypoint(i)"></i>
                                    <i class="icon default arrow-icon gray__theme" @click="startWaypointToLower(i)"></i>
                                    <i class="icon default arrow-icon reverse gray__theme" @click="startWaypointToUpper(i)"></i>
                                </div>
                            </div>
                            <custom-date-time :no-time="false"  :placeholder="'Выберите дату'" v-model="waypoint.date"></custom-date-time>
                            <span class="link-text" @click="toggleModal(waypoint)"><i class="icon large calc-icon"></i></span>
                            <modal :opened="waypoint.opened | bool" @onclose="waypoint.opened=false" :title="'Коментарий'">
                                <textarea class="comment-area" v-model="waypoint.comment" rows="5"></textarea>
                            </modal>
                        </div>
                    </div>
                    <div class="add-marker-block">
                    <span class="link-text" @click="addNewEmptyStartWaypoint">
                        <i class="icon default marker-icon"></i>
                        Добавить пункт сбора
                    </span>
                    </div>
                    <div v-show="border_waypoint">
                        Граница
                        <div class="waypoint-input-group">
                            <div class="custom-input__group">
                                <div class="custom-input__addon left">
                                    <i class="icon default marker-icon"></i>
                                </div>
                                <input type="text" :value="borderWaypointName" ref="borderWaypoint">
                            </div>
                            <div class="waypoint-icons">
                                <i class="icon default remove-icon gray__theme" @click="removeBorderMarker"></i>
                            </div>
                        </div>
                    </div>
                    <div v-if="end_waypoints.length">
                        <div class="waypoint-row waypoint-titles">
                            <div>Пункт назначения</div>
                            <div>Дата прибытия</div>
                        </div>
                        <div class="waypoint-row" v-for="(waypoint,i) in end_waypoints">
                            <div class="waypoint-input-group">
                                <div class="custom-input__group">
                                    <div class="custom-input__addon left">
                                        <i class="icon default marker-icon"></i>
                                    </div>
                                    <input type="text" :value="waypoint.name" :ref="`endWaypoint${i}`">
                                </div>
                                <div class="waypoint-icons">
                                    <i class="icon default remove-icon gray__theme" @click="removeEndWaypoint(i)"></i>
                                    <i class="icon default arrow-icon gray__theme" @click="endWaypointToLower(i)"></i>
                                    <i class="icon default arrow-icon reverse gray__theme" @click="endWaypointToUpper(i)"></i>
                                </div>
                            </div>
                            <custom-date-time :no-time="false" :placeholder="'Выберите дату'" v-model="waypoint.date"></custom-date-time>
                            <span class="link-text" @click="toggleModal(waypoint)"><i class="icon large calc-icon"></i></span>
                            <modal :opened="waypoint.opened | bool" @onclose="waypoint.opened=false" :title="'Коментарий'">
                                <textarea class="comment-area" v-model="waypoint.comment" rows="5"></textarea>
                            </modal>
                        </div>
                    </div>
                    <div class="add-marker-block">
                        <span class="link-text" @click="addNewEmptyEndWaypoint">
                            <i class="icon default marker-icon"></i>
                            Добавить пункт назначения
                        </span>
                    </div>

                    <div class="depo-row">
                        <custom-date-time :no-time="false" :label="'Дата выезда из депо'" v-model="startWaypoint.date"></custom-date-time>
                        <custom-date-time :no-time="false" :label="'Дата прибытия в депо'" v-model="endWaypoint.date"></custom-date-time>
                        <span class="gray-light-text" style="margin: 11px 0;">Депо<br>{{startWaypoint.name}}</span>
                    </div>
                </div>
                <div v-show="(stage==='backward' && type_id===2)">
                    <div v-if="start_waypoints_reversed.length">
                        <div class="waypoint-row waypoint-titles">
                            <div>Пункт сбора</div>
                            <div>Дата сбора</div>
                        </div>
                        <div class="waypoint-row" v-for="(waypoint,i) in start_waypoints_reversed">
                            <div class="waypoint-input-group">
                                <div class="custom-input__group">
                                    <div class="custom-input__addon left">
                                        <i class="icon default marker-icon"></i>
                                    </div>
                                    <input type="text" :value="waypoint.name" :ref="`startWaypointReversed${i}`">
                                </div>
                                <div class="waypoint-icons">
                                    <i class="icon default remove-icon gray__theme" @click="removeStartWaypointReversed(i)"></i>
                                    <i class="icon default arrow-icon gray__theme" @click="startWaypointToLowerReversed(i)"></i>
                                    <i class="icon default arrow-icon reverse gray__theme" @click="startWaypointToUpperReversed(i)"></i>
                                </div>
                            </div>
                            <custom-date-time :no-time="false" :placeholder="'Выберите дату'" v-model="waypoint.date"></custom-date-time>
                            <span class="link-text" @click="toggleModal(waypoint)"><i class="icon large calc-icon"></i></span>
                            <modal :opened="waypoint.opened | bool" @onclose="waypoint.opened=false" :title="'Коментарий'">
                                <textarea class="comment-area" v-model="waypoint.comment" rows="5"></textarea>
                            </modal>
                        </div>
                    </div>
                    <div class="add-marker-block">
                            <span class="link-text" @click="addNewEmptyStartWaypointReversed">
                                <i class="icon default marker-icon"></i>
                                Добавить пункт сбора
                            </span>
                    </div>
                    <div v-show="border_waypoint_reversed">
                        Граница
                        <div class="waypoint-input-group">
                            <div class="custom-input__group">
                                <div class="custom-input__addon left">
                                    <i class="icon default marker-icon"></i>
                                </div>
                                <input type="text" :value="borderWaypointReversedName" ref="borderReverseWaypoint">
                            </div>
                            <div class="waypoint-icons">
                                <i class="icon default remove-icon gray__theme" @click="removeBorderReversedMarker"></i>
                            </div>
                        </div>
                    </div>
                    <div v-if="end_waypoints_reversed.length">
                        <div class="waypoint-row waypoint-titles">
                            <div>Пункт назначения</div>
                            <div>Дата прибытия</div>
                        </div>
                        <div class="waypoint-row" v-for="(waypoint,i) in end_waypoints_reversed">
                            <div class="waypoint-input-group">
                                <div class="custom-input__group">
                                    <div class="custom-input__addon left">
                                        <i class="icon default marker-icon"></i>
                                    </div>
                                    <input type="text" :value="waypoint.name" :ref="`endWaypointReversed${i}`">
                                </div>
                                <div class="waypoint-icons">
                                    <i class="icon default remove-icon gray__theme" @click="removeEndWaypointReversed(i)"></i>
                                    <i class="icon default arrow-icon gray__theme" @click="endWaypointToLowerReversed(i)"></i>
                                    <i class="icon default arrow-icon reverse gray__theme" @click="endWaypointToUpperReversed(i)"></i>
                                </div>
                            </div>
                            <custom-date-time :no-time="false" :placeholder="'Выберите дату'" v-model="waypoint.date"></custom-date-time>
                            <span class="link-text" @click="toggleModal(waypoint)"><i class="icon large calc-icon"></i></span>
                            <modal :opened="waypoint.opened | bool" @onclose="waypoint.opened=false" :title="'Коментарий'">
                                <textarea class="comment-area" v-model="waypoint.comment" rows="5"></textarea>
                            </modal>
                        </div>
                    </div>
                    <div class="add-marker-block">
                            <span class="link-text" @click="addNewEmptyEndWaypointReversed">
                                <i class="icon default marker-icon"></i>
                                Добавить пункт назначения
                            </span>
                    </div>

                    <div class="depo-row">
                        <custom-date-time :no-time="false" :label="'Дата выезда из депо'" v-model="startWaypointReversed.date"></custom-date-time>
                        <custom-date-time :no-time="false" :label="'Дата прибытия в депо'" v-model="endWaypointReversed.date"></custom-date-time>
                        <span class="gray-light-text" style="margin: 11px 0;">Депо<br>{{startWaypoint.name}}</span>
                    </div>
                </div>
                <button class="button light make-route-button" @click="calcRoutes">Проложить маршрут</button>
            </div>

            <div class="cost-info-row">
                <div>
                    Расстояние внутри
                    <div class="settings-input">
                        <custom-input :type="'number'" :showArrows="false" v-model="insideDistanceKm"></custom-input>
                        км
                    </div>
                </div>
                <div>
                    Расстояние снаружи
                    <div class="settings-input">
                        <custom-input :type="'number'" :showArrows="false" v-model="outsideDistanceKm"></custom-input>
                        км
                    </div>
                </div>
                <div>
                    Сумма
                    <div style="height: 67px; display: flex; align-items: center;">
                        {{insideDistanceKm+outsideDistanceKm}} <span class="gray-light-text" style="margin-left:10px"> км</span>
                    </div>
                </div>
            </div>
            <div class="cost-info-row" style="grid-template-columns: 25% 40% 35%;">
                <div>
                    Стоимость
                    <div class="settings-input">
                        <custom-input :type="'number'" :showArrows="false" v-model="trip.cost"></custom-input>
                        €
                    </div>
                </div>
                <div class="link-text" style="align-items: center; margin-top: 23px;" @click="saveCalculation"><i class="icon large calc-icon"></i> Рассчитать стоимость</div>
            </div>
            <div class="button" @click="$emit('setinfotab')">Продолжить</div>
        </div>
        <div class="map-column">
            <div ref="map" id="map"></div>
            <div class="distance__block" v-if="allDistance">
                <div>
                    <span class="distance__length">{{allDistance}}</span> км
                    <div>Entfernung</div>
                </div>
            </div>
            <div class="map-tooltip" v-if="(insideDistance || outsideDistance) && !borderMarkerExists">
                <i class="icon large redmarker-icon"></i> Punk für Grenzübergang manuell markieren
            </div>
        </div>
    </div>
</template>

<script>
    import CustomInput from "@vue/components/UI/CustomInput.vue";
    import gmapsInit from '@vue/components/roadmap/gmapInit.js';
    import {Router} from "@vue/init.js";
    import {toUrl} from "@vue/components/helpers/objectToQuery.js";
    import CustomDateTime from "../UI/CustomDateTime";
    import Modal from "../UI/Modal";

    export default {
        name: "StoreWaypointsTab",
        components: {
            CustomInput, CustomDateTime, Modal
        },
        props:{
            trip:Object,
            default: {},
        },
        data:function () {
            return {
                type_id:1,
                stage: 'thitherward',
                startWaypoint:{},
                endWaypoint:{},
                startWaypointReversed:{},
                endWaypointReversed:{},
                start_waypoints: [],
                end_waypoints: [],
                start_waypoints_reversed:[],
                end_waypoints_reversed:[],
                border_waypoint:undefined,
                border_waypoint_reversed:undefined,
                directionsService : null,
                directionsDisplay : null,
                startAutocomplete: null,
                endAutocomplete: null,map : null,
                google: null,
                center: {lat: 52.520008, lng: 13.404954},
                zoom : 6,
                autocompleteOptions: {'type' : "address"},
                routes: this.trip.segments,
                distance: 0,
                polylineOptions : {
                    strokeColor: '#238AC4',
                    strokeOpacity: 1,
                    strokeWeight: 5,
                },
                polylines:[],
                waypointMarkers : [],
                insideDistance: 0,
                outsideDistance : 0,
                optimizeWaypoints : false,
                waypoints: [],
                mapStyles:[
                    {
                        "featureType": "administrative",
                        "elementType": "all",
                        "stylers": [
                            {
                                "visibility": "off"
                            }
                        ]
                    },
                    {
                        "featureType": "administrative.country",
                        "elementType": "all",
                        "stylers": [
                            {
                                "visibility": "on"
                            }
                        ]
                    },
                    {
                        "featureType": "administrative.country",
                        "elementType": "geometry.stroke",
                        "stylers": [
                            {
                                "lightness": "3"
                            },
                            {
                                "saturation": "52"
                            },
                            {
                                "color": "#238ac4"
                            }
                        ]
                    },
                    {
                        "featureType": "administrative.country",
                        "elementType": "labels.text",
                        "stylers": [
                            {
                                "lightness": "18"
                            },
                            {
                                "weight": "1.74"
                            }
                        ]
                    },
                    {
                        "featureType": "administrative.province",
                        "elementType": "all",
                        "stylers": [
                            {
                                "visibility": "off"
                            }
                        ]
                    },
                    {
                        "featureType": "administrative.province",
                        "elementType": "geometry.fill",
                        "stylers": [
                            {
                                "visibility": "simplified"
                            }
                        ]
                    },
                    {
                        "featureType": "administrative.province",
                        "elementType": "labels.text",
                        "stylers": [
                            {
                                "lightness": "22"
                            }
                        ]
                    },
                    {
                        "featureType": "administrative.locality",
                        "elementType": "all",
                        "stylers": [
                            {
                                "visibility": "simplified"
                            }
                        ]
                    },
                    {
                        "featureType": "administrative.land_parcel",
                        "elementType": "all",
                        "stylers": [
                            {
                                "visibility": "on"
                            }
                        ]
                    },
                    {
                        "featureType": "landscape",
                        "elementType": "all",
                        "stylers": [
                            {
                                "visibility": "simplified"
                            },
                            {
                                "hue": "#0066ff"
                            },
                            {
                                "saturation": 74
                            },
                            {
                                "lightness": 100
                            }
                        ]
                    },
                    {
                        "featureType": "landscape.natural",
                        "elementType": "all",
                        "stylers": [
                            {
                                "visibility": "on"
                            }
                        ]
                    },
                    {
                        "featureType": "landscape.natural.landcover",
                        "elementType": "all",
                        "stylers": [
                            {
                                "visibility": "simplified"
                            }
                        ]
                    },
                    {
                        "featureType": "landscape.natural.terrain",
                        "elementType": "all",
                        "stylers": [
                            {
                                "saturation": "16"
                            }
                        ]
                    },
                    {
                        "featureType": "landscape.natural.terrain",
                        "elementType": "geometry",
                        "stylers": [
                            {
                                "saturation": "0"
                            }
                        ]
                    },
                    {
                        "featureType": "poi",
                        "elementType": "all",
                        "stylers": [
                            {
                                "visibility": "off"
                            }
                        ]
                    },
                    {
                        "featureType": "road",
                        "elementType": "all",
                        "stylers": [
                            {
                                "visibility": "simplified"
                            }
                        ]
                    },
                    {
                        "featureType": "road.highway",
                        "elementType": "all",
                        "stylers": [
                            {
                                "visibility": "off"
                            },
                            {
                                "weight": 0.6
                            },
                            {
                                "saturation": "99"
                            },
                            {
                                "lightness": "77"
                            },
                            {
                                "hue": "#00a7ff"
                            }
                        ]
                    },
                    {
                        "featureType": "road.highway",
                        "elementType": "geometry",
                        "stylers": [
                            {
                                "visibility": "on"
                            }
                        ]
                    },
                    {
                        "featureType": "road.arterial",
                        "elementType": "all",
                        "stylers": [{
                            "visibility": "off"
                        }
                        ]
                    },
                    {
                        "featureType": "road.local",
                        "elementType": "all",
                        "stylers": [
                            {
                                "visibility": "on"
                            }
                        ]
                    },
                    {
                        "featureType": "transit",
                        "elementType": "all",
                        "stylers": [
                            {
                                "visibility": "simplified"
                            }
                        ]
                    },
                    {
                        "featureType": "water",
                        "elementType": "all",
                        "stylers": [
                            {
                                "visibility": "simplified"
                            },
                            {
                                "lightness": "-29"
                            },
                            {
                                "gamma": "5.86"
                            },
                            {
                                "hue": "#00a7ff"
                            }
                        ]
                    }
                ]
            }
        },
        methods:{
            toggleModal(waypoint){
                this.$set(waypoint,'opened',!waypoint.opened);
            },
            switchStage(stage){
                this.stage = stage;
                if(stage === 'backward'){
                    if(this.start_waypoints_reversed.length===0){
                        this.start_waypoints_reversed = JSON.parse(JSON.stringify(this.end_waypoints)).reverse();
                    }
                    if(this.end_waypoints_reversed.length===0){
                        this.end_waypoints_reversed = JSON.parse(JSON.stringify(this.start_waypoints)).reverse();
                    }
                    if((typeof this.border_waypoint_reversed === "undefined") && (typeof this.border_waypoint !== "undefined")) {
                        let lat = this.border_waypoint.location.lat();
                        let lng = this.border_waypoint.location.lng();
                        this.border_waypoint_reversed = {
                            name: this.border_waypoint.name,
                            location: new google.maps.LatLng(lat,lng),
                            stopover: this.border_waypoint.stopover,
                        }
                    }
                }else{
                    if(this.start_waypoints.length===0){
                        this.start_waypoints = JSON.parse(JSON.stringify(this.end_waypoints_reversed)).reverse();
                    }
                    if(this.end_waypoints.length===0){
                        this.end_waypoints = JSON.parse(JSON.stringify(this.start_waypoints_reversed)).reverse();
                    }
                    if((typeof this.border_waypoint === "undefined") && (typeof this.border_waypoint_reversed !== "undefined"))
                    {
                        let lat = this.border_waypoint_reversed.location.lat();
                        let lng = this.border_waypoint_reversed.location.lng();
                        this.border_waypoint = {
                            name: this.border_waypoint_reversed.name,
                            location: new google.maps.LatLng(lat,lng),
                            stopover: this.border_waypoint_reversed.stopover,
                        }
                    }
                }
                this.start_waypoints.forEach((waypoint,index)=>{
                    setTimeout(()=>{
                        let autocomplete = new google.maps.places.Autocomplete(this.$refs[`startWaypoint${index}`][0], this.options);
                        autocomplete.addListener('place_changed', function() {
                            var place = autocomplete.getPlace();
                            waypoint.location = place.formatted_address;
                            waypoint.name = place.formatted_address;
                        });
                    },200)
                });
                this.start_waypoints_reversed.forEach((waypoint,index)=>{
                    setTimeout(()=>{
                        let autocomplete = new google.maps.places.Autocomplete(this.$refs[`startWaypointReversed${index}`][0], this.options);
                        autocomplete.addListener('place_changed', function() {
                            var place = autocomplete.getPlace();
                            waypoint.location = place.formatted_address;
                            waypoint.name = place.formatted_address;
                        });
                    },200)
                });
                this.end_waypoints.forEach((waypoint,index)=>{
                    setTimeout(()=>{
                        let autocomplete = new google.maps.places.Autocomplete(this.$refs[`endWaypoint${index}`][0], this.options);
                        autocomplete.addListener('place_changed', function() {
                            var place = autocomplete.getPlace();
                            waypoint.location = place.formatted_address;
                            waypoint.name = place.formatted_address;
                        });
                    },200)
                });
                this.end_waypoints_reversed.forEach((waypoint,index)=>{
                    setTimeout(()=>{
                        let autocomplete = new google.maps.places.Autocomplete(this.$refs[`endWaypointReversed${index}`][0], this.options);
                        autocomplete.addListener('place_changed', function() {
                            var place = autocomplete.getPlace();
                            waypoint.location = place.formatted_address;
                            waypoint.name = place.formatted_address;
                        });
                    },200)
                });
            },
            getSettings(){
                return new Promise((success,reject)=>{
                    Router.get("listSettingsData").then((url)=>{
                        let query = toUrl({items:['depo']});
                        axios.get(url+"?"+query).then(({data})=>{
                            data.data.forEach((setting,index)=>{
                                if(setting.key==="depo") {
                                    console.log(Object.keys(this.startWaypoint));
                                    if(Object.keys(this.startWaypoint).length<2)
                                        this.$set(this, 'startWaypoint', JSON.parse(setting.value));
                                    if(Object.keys(this.endWaypoint).length<2)
                                        this.$set(this, 'endWaypoint', JSON.parse(setting.value));
                                    if(Object.keys(this.startWaypointReversed).length<2)
                                        this.$set(this, 'startWaypointReversed', JSON.parse(setting.value));
                                    if(Object.keys(this.endWaypointReversed).length<2)
                                        this.$set(this, 'endWaypointReversed', JSON.parse(setting.value));
                                }
                                if(index===data.data.length-1)
                                    success();
                            });
                        })
                    })
                })

            },
            saveCalculation(){
                let calculationData = {
                    start_waypoints: this.start_waypoints,
                    end_waypoints: this.end_waypoints,
                    border_waypoint: this.border_waypoint,
                    border_waypoint_reversed: this.border_waypoint_reversed,
                    start_waypoints_reversed: this.start_waypoints_reversed,
                    end_waypoints_reversed: this.end_waypoints_reversed,
                    inside_distance: this.insideDistance,
                    outside_distance: this.outsideDistance,
                    type_id: this.type_id,
                    stage: this.stage,
                    cost: this.cost,
                };
                Router.get("storeCalculationAction").then((url)=>{
                    axios.post(url,calculationData).then(({data})=>{
                        let link = data.data.link;
                        if(this.trip?.id ?? false)
                            link+="&trip_id="+this.trip.id;
                        location.href = link;
                    })
                })
            },
            waypointInsideCountry(i){
                let borderIndexes = [];
                if(typeof this.border_waypoint !== "undefined")
                    borderIndexes.push(this.start_waypoints.length);
                if(typeof this.border_waypoint_reversed !=="undefined"){
                    let count = 0;
                    count += this.start_waypoints.length;
                    count += this.start_waypoints_reversed.length;
                    if(typeof this.border_waypoint !== "undefined")
                        count++;
                    count += this.end_waypoints.length;
                    if(typeof this.border_waypoint_reversed !== "undefined")
                        count++;
                    borderIndexes.push(count-1);
                }
                if(borderIndexes.length===0)
                    return true;
                else{
                    if(borderIndexes.length===1)
                        return i<borderIndexes[0];
                    else
                        return i<borderIndexes[0] || i>=borderIndexes[1];
                }
            },
            removeStartWaypoint(id){
                this.start_waypoints.splice(id,1);
            },
            startWaypointToUpper(id){
                if(id===0) return false;
                let wp = {};
                Object.assign(wp,this.start_waypoints[id-1]);
                Object.assign(this.start_waypoints[id-1],this.start_waypoints[id]);
                Object.assign(this.start_waypoints[id],wp);
            },
            startWaypointToLower(id){
                if(id===this.start_waypoints.length-1) return false;
                let wp = {};
                Object.assign(wp,this.start_waypoints[id+1]);
                Object.assign(this.start_waypoints[id+1],this.start_waypoints[id]);
                Object.assign(this.start_waypoints[id],wp);
            },
            addNewEmptyStartWaypoint(){
                if(this.start_waypoints.length === 0 || this.start_waypoints[this.start_waypoints.length-1].name !== null) {
                    let waypoint = {
                        location: null,
                        stopover: true,
                        name: null,
                        opened: false,
                    };
                    this.start_waypoints.push(waypoint);
                    setTimeout(()=>{
                        let autocomplete = new google.maps.places.Autocomplete(this.$refs[`startWaypoint${this.start_waypoints.length-1}`][0], this.options);
                        autocomplete.addListener('place_changed', function() {
                            var place = autocomplete.getPlace();
                            waypoint.location = place.formatted_address;
                            waypoint.name = place.formatted_address;
                        });
                        this.$refs[`startWaypoint${this.start_waypoints.length-1}`][0].focus()
                    },200)
                }
            },
            removeEndWaypoint(id){
                this.end_waypoints.splice(id,1);
            },
            endWaypointToUpper(id){
                if(id===0) return false;
                let wp = {};
                Object.assign(wp,this.end_waypoints[id-1]);
                Object.assign(this.end_waypoints[id-1],this.end_waypoints[id]);
                Object.assign(this.end_waypoints[id],wp);
            },
            endWaypointToLower(id){
                if(id===this.end_waypoints.length-1) return false;
                let wp = {};
                Object.assign(wp,this.end_waypoints[id+1]);
                Object.assign(this.end_waypoints[id+1],this.end_waypoints[id]);
                Object.assign(this.end_waypoints[id],wp);
            },
            addNewEmptyEndWaypoint(){
                if(this.end_waypoints.length === 0 || this.end_waypoints[this.end_waypoints.length-1].name !== null) {
                    let waypoint = {
                        location: null,
                        stopover: true,
                        name: null,
                        opened: false,
                    };
                    this.end_waypoints.push(waypoint);
                    setTimeout(()=>{
                        let autocomplete = new google.maps.places.Autocomplete(this.$refs[`endWaypoint${this.end_waypoints.length-1}`][0], this.options);
                        autocomplete.addListener('place_changed', function() {
                            var place = autocomplete.getPlace();
                            waypoint.location = place.formatted_address;
                            waypoint.name = place.formatted_address;
                        });
                        this.$refs[`endWaypoint${this.end_waypoints.length-1}`][0].focus()
                    },200)
                }
            },
            removeBorderMarker(){
                this.border_waypoint = undefined;
            },
            removeStartWaypointReversed(id){
                this.start_waypoints_reversed.splice(id,1);
            },
            startWaypointToUpperReversed(id){
                if(id===0) return false;
                let wp = {};
                Object.assign(wp,this.start_waypoints_reversed[id-1]);
                Object.assign(this.start_waypoints_reversed[id-1],this.start_waypoints_reversed[id]);
                Object.assign(this.start_waypoints_reversed[id],wp);
            },
            startWaypointToLowerReversed(id){
                if(id===this.start_waypoints_reversed.length-1) return false;
                let wp = {};
                Object.assign(wp,this.start_waypoints_reversed[id+1]);
                Object.assign(this.start_waypoints[id+1],this.waypoints[id]);
                Object.assign(this.start_waypoints[id],wp);
            },
            addNewEmptyStartWaypointReversed(){
                if(this.start_waypoints_reversed.length === 0 || this.start_waypoints_reversed[this.start_waypoints_reversed.length-1].name !== null) {
                    let waypoint = {
                        location: null,
                        stopover: true,
                        name: null,
                        opened: false,
                    };
                    this.start_waypoints.push(waypoint);
                    setTimeout(()=>{
                        let autocomplete = new google.maps.places.Autocomplete(this.$refs[`startWaypointReversed${this.start_waypoints_reversed.length-1}`][0], this.options);
                        autocomplete.addListener('place_changed', function() {
                            var place = autocomplete.getPlace();
                            waypoint.location = place.formatted_address;
                            waypoint.name = place.formatted_address;
                        });
                        this.$refs[`startWaypoint${this.start_waypoints_reversed.length-1}`][0].focus()
                    },200)
                }
            },
            removeEndWaypointReversed(id){
                this.end_waypoints_reversed.splice(id,1);
            },
            endWaypointToUpperReversed(id){
                if(id===0) return false;
                let wp = {};
                Object.assign(wp,this.end_waypoints_reversed[id-1]);
                Object.assign(this.end_waypoints_reversed[id-1],this.end_waypoints_reversed[id]);
                Object.assign(this.end_waypoints_reversed[id],wp);
            },
            endWaypointToLowerReversed(id){
                if(id===this.end_waypoints_reversed.length-1) return false;
                let wp = {};
                Object.assign(wp,this.end_waypoints_reversed[id+1]);
                Object.assign(this.end_waypoints_reversed[id+1],this.end_waypoints_reversed[id]);
                Object.assign(this.end_waypoints_reversed[id],wp);
            },
            addNewEmptyEndWaypointReversed(){
                if(this.end_waypoints_reversed.length === 0 || this.end_waypoints_reversed[this.end_waypoints_reversed.length-1].name !== null) {
                    let waypoint = {
                        location: null,
                        stopover: true,
                        name: null,
                        opened: false,
                    };
                    this.end_waypoints_reversed.push(waypoint);
                    setTimeout(()=>{
                        let autocomplete = new google.maps.places.Autocomplete(this.$refs[`endWaypointReversed${this.end_waypoints_reversed.length-1}`][0], this.options);
                        autocomplete.addListener('place_changed', function() {
                            var place = autocomplete.getPlace();
                            waypoint.location = place.formatted_address;
                            waypoint.name = place.formatted_address;
                        });
                        this.$refs[`endWaypoint${this.end_waypoints_reversed.length-1}`][0].focus()
                    },200)
                }
            },
            removeBorderReversedMarker(){
                this.border_waypoint_reversed = undefined;
            },
            calcRoutes(){
                let waypoints = [];
                this.start_waypoints.forEach(function(waypoint){
                    waypoints.push({
                        location: waypoint.location,
                        stopover: waypoint.stopover,
                    })
                });
                if(typeof this.border_waypoint !== "undefined" && this.border_waypoint !== null)
                    waypoints.push({
                        location: this.border_waypoint.location,
                        stopover: true,
                    });
                this.end_waypoints.forEach(function(waypoint){
                    waypoints.push({
                        location: waypoint.location,
                        stopover: waypoint.stopover,
                    })
                });
                if(typeof this.endWaypoint !== "undefined" && this.type_id === 2 && this.startWaypointReversed.location !== this.endWaypoint.location && this.endWaypoint !== null)
                    waypoints.push({
                        location: this.endWaypoint.location,
                        stopover: true,
                    });
                if(typeof this.startWaypointReversed !== "undefined" && this.type_id === 2 && this.startWaypointReversed !== null)
                    waypoints.push({
                        location: this.startWaypointReversed.location,
                        stopover: true,
                    });
                this.start_waypoints_reversed.forEach(function(waypoint){
                    waypoints.push({
                        location: waypoint.location,
                        stopover: waypoint.stopover,
                    })
                });
                if(typeof this.border_waypoint_reversed !== "undefined" && this.border_waypoint_reversed !== null)
                    waypoints.push({
                        location: this.border_waypoint_reversed.location,
                        stopover: true,
                    });
                this.end_waypoints_reversed.forEach(function(waypoint){
                    waypoints.push({
                        location: waypoint.location,
                        stopover: waypoint.stopover,
                    })
                });
                console.log(waypoints,this.start_waypoint.location,this.type_id===2 ? this.endWaypointReversed.location : this.endWaypoint.location);
                if(waypoints.length===0) return false;
                let _this = this;
                if(this.start_waypoint && this.end_waypoint)
                    this.directionsService.route({
                        origin: this.start_waypoint.location,
                        destination: this.type_id===2 ? this.endWaypointReversed.location : this.endWaypoint.location,
                        waypoints: waypoints,
                        optimizeWaypoints: this.optimizeWaypoints,
                        travelMode: 'DRIVING'
                    }, function(response, status) {
                        if (status === 'OK') {
                            _this.directionsDisplay.setDirections(response);
                            let route = response.routes[0];
                            _this.routes = [];
                            for (var i = 0; i < route.legs.length; i++) {
                                let startAddress = route.legs[i].start_address;
                                let endAddress = route.legs[i].end_address;
                                if(_this.isBordermarker(route.legs[i].start_location))
                                    startAddress = "Grenzübergang";
                                if(_this.isBordermarker(route.legs[i].end_location))
                                    endAddress = "Grenzübergang";
                                _this.routes.push({
                                    from: startAddress,
                                    to: endAddress,
                                    distance:route.legs[i].distance.value,
                                    inside: _this.waypointInsideCountry(i-1),
                                })
                            }
                            _this.$emit("oncalc",{
                                start_waypoints: JSON.parse(JSON.stringify(_this.start_waypoints)),
                                end_waypoints: JSON.parse(JSON.stringify(_this.end_waypoints)),
                                start_waypoints_reversed: JSON.parse(JSON.stringify(_this.start_waypoints_reversed)),
                                end_waypoints_reversed: JSON.parse(JSON.stringify(_this.end_waypoints_reversed)),
                                segments: _this.routes,
                                start_waypoint: _this.start_waypoint,
                                end_waypoint: _this.end_waypoint,
                                start_waypoint_reversed: _this.start_waypoint_reversed,
                                end_waypoint_reversed: _this.end_waypoint_reversed,
                                border_waypoint: typeof _this.border_waypoint === "undefined" ? undefined :  JSON.parse(JSON.stringify(_this.border_waypoint)),
                                border_waypoint_reversed: typeof _this.border_waypoint_reversed === "undefined" ? undefined : JSON.parse(JSON.stringify(_this.border_waypoint_reversed)),
                                type_id: _this.type_id,
                            });
                            _this.$emit("enableinfotab");
                            _this.drawPolyline(response);
                            _this.drawMarkers(response);
                        } else {
                            window.alert('Directions request failed due to ' + status);
                        }

                    });
            },
            addBorderMarker(e){
                if(this.type_id===2 && this.stage==="backward"){
                    this.removeBorderReversedMarker();
                    this.border_waypoint_reversed = {
                        location: e.latLng,
                        name: "Grenzübergang",
                        stopover:true,
                    };
                }else{
                    this.removeBorderMarker();
                    this.border_waypoint = {
                        location: e.latLng,
                        name: "Grenzübergang",
                        stopover:true,
                    };
                }
                this.calcRoutes();
            },
            drawPolyline(response){
                for (var i=0; i<this.polylines.length; i++) {
                    this.polylines[i].setMap(null);
                }
                this.polylines.splice(0,this.polylines.length);
                var legs = response.routes[0].legs;
                for (var i = 0; i < legs.length; i++) {
                    var steps = legs[i].steps;
                    for (var j = 0; j < steps.length; j++) {
                        var nextSegment = steps[j].path;
                        var stepPolyline = new google.maps.Polyline(this.polylineOptions);
                        for (var k = 0; k < nextSegment.length; k++) {
                            stepPolyline.getPath().push(nextSegment[k]);
                        }
                        this.polylines.push(stepPolyline);
                        stepPolyline.setMap(this.map);
                        google.maps.event.addListener(stepPolyline, 'click', this.addBorderMarker);
                        google.maps.event.addListener(stepPolyline, 'mouseover', ()=>{
                            this.$refs.map.getElementsByClassName("gm-style")[0].getElementsByTagName("div")[0].classList.add("ebatCursor");
                            this.onPolilyne = true;
                        });
                        google.maps.event.addListener(stepPolyline, 'mouseout', ()=>{
                            this.$refs.map.getElementsByClassName("gm-style")[0].getElementsByTagName("div")[0].classList.remove("ebatCursor");
                            this.onPolilyne = true;
                        });
                    }
                }

            },
            isBordermarker(location){
                let bordermarkers = [];
                if(typeof this.border_waypoint !== "undefined")
                    bordermarkers.push(this.border_waypoint.location);
                if(typeof this.border_waypoint_reversed !== "undefined")
                    bordermarkers.push(this.border_waypoint_reversed.location);
                let is = false;
                bordermarkers.forEach((bordermarker)=>{
                    if(
                        Math.round(bordermarker.lat() * 100) === Math.round(location.lat() * 100)
                        &&
                        Math.round(bordermarker.lng() * 100) === Math.round(location.lng() * 100)
                    )
                        is = true;
                });
                return is;
            },
            drawMarkers(response){
                let inside = true;
                this.insideDistance = 0;
                this.outsideDistance = 0;
                let markers = [];
                response.routes[0].legs.forEach((leg,i)=>{
                    if(i==0){
                        markers.push({
                            position: leg.start_location,
                            title: leg.start_address,
                        });
                    }
                    markers.push({
                        position: leg.end_location,
                        title: leg.end_address,
                    });
                    if (this.isBordermarker(leg.start_location))
                        inside = !inside;
                    if(inside){
                        this.insideDistance+=leg.distance.value;
                    }else{
                        this.outsideDistance+=leg.distance.value;
                    }
                });
                this.waypointMarkers.forEach((marker)=>{
                    marker.setMap(null);
                });
                if(this.startWaypoint.location === this.endWaypoint.location)
                    markers.splice(markers.length-1,1);

                let beforeBorder = true;

                markers.forEach((markerData,i)=> {
                    markerData.map = this.map;
                    markerData.icon = {
                        // path: "M 75, 75 m -150, -75 a 75,75 0 1,0 150,0 a 75,75 0 1,0 -150,0",
                        path: "M -10, 0 a 10,10 0 1,0 20,0 a 10,10 0 1,0 -20,0",
                        fillColor: "#238AC4",
                        fillOpacity:1,
                        strokeOpacity:0,
                        scale:1,
                    };
                    markerData.label = {
                        text:(beforeBorder ? i+1 : i).toString(),
                        color:"white",
                        fontSize:"13px",
                        fontWeight:"500",
                        fontFamily:"'Inter-Medium', Helvetica, Arial, sans-serif"
                    };
                    // markerData.icon = {url:'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(`<svg width="20" class="marker-icon-svg" height="28" viewBox="0 0 20 28" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="10" cy="10" r="10" fill="#238AC4"/><text x="10" y="10" text-anchor="middle" fill="white">${i}</text><svg>`)};
                    if (this.isBordermarker(markerData.position)) {
                        markerData.icon = {url: "/images/redmarker.png"};
                        markerData.label = undefined;
                        beforeBorder = false;
                    }
                    this.waypointMarkers.push(new google.maps.Marker(markerData));
                })
            },
            reInitFields(){
                let _this = this;
                this.$nextTick(()=>{
                    this.start_waypoints.forEach((waypoint,index)=>{
                        setTimeout(()=>{
                            if(typeof this.$refs[`startWaypoint${index}`] === "undefined") return false;
                            let autocomplete = new google.maps.places.Autocomplete(this.$refs[`startWaypoint${index}`][0], this.options);
                            autocomplete.addListener('place_changed', function() {
                                var place = autocomplete.getPlace();
                                waypoint.location = place.formatted_address;
                                waypoint.name = place.formatted_address;
                                _this.start_waypoints.splice(index,1,waypoint);
                            });
                            this.$refs[`startWaypoint${index}`][0].focus()
                        },500)
                    });
                    this.end_waypoints.forEach((waypoint,index)=>{
                        setTimeout(()=>{
                            if(typeof this.$refs[`endWaypoint${index}`] === "undefined") return false;
                            let autocomplete = new google.maps.places.Autocomplete(this.$refs[`endWaypoint${index}`][0], this.options);
                            autocomplete.addListener('place_changed', function() {
                                var place = autocomplete.getPlace();
                                waypoint.location = place.formatted_address;
                                waypoint.name = place.formatted_address;
                                _this.end_waypoints.splice(index,1,waypoint);
                            });
                            this.$refs[`endWaypoint${index}`][0].focus()
                        },500)
                    });
                    this.start_waypoints_reversed.forEach((waypoint,index)=>{
                        setTimeout(()=>{
                            if(typeof this.$refs[`startWaypointReversed${index}`] === "undefined") return false;
                            let autocomplete = new google.maps.places.Autocomplete(this.$refs[`startWaypointReversed${index}`][0], this.options);
                            autocomplete.addListener('place_changed', function() {
                                var place = autocomplete.getPlace();
                                waypoint.location = place.formatted_address;
                                waypoint.name = place.formatted_address;
                                _this.start_waypoints_reversed.splice(index,1,waypoint);
                            });
                            this.$refs[`startWaypointReversed${index}`][0].focus()
                        },500)
                    });
                    this.end_waypoints_reversed.forEach((waypoint,index)=>{
                        setTimeout(()=>{
                            if(typeof this.$refs[`endWaypointReversed${index}`] === "undefined") return false;
                            let autocomplete = new google.maps.places.Autocomplete(this.$refs[`endWaypointReversed${index}`][0], this.options);
                            autocomplete.addListener('place_changed', function() {
                                var place = autocomplete.getPlace();
                                waypoint.location = place.formatted_address;
                                waypoint.name = place.formatted_address;
                                _this.end_waypoints_reversed.splice(index,1,waypoint);
                            });
                            this.$refs[`endWaypointReversed${index}`][0].focus()
                        },500)
                    });
                })
            }
        },
        async mounted() {
            this.google = await gmapsInit();
            this.getSettings().then(()=>{
                try {
                    if(typeof this.trip !== 'undefined' && this.trip.waypoints instanceof Array)
                        this.waypoints = this.trip.waypoints;
                    this.map = new google.maps.Map(this.$refs.map, {
                        zoom: this.zoom,
                        center: this.center,
                        disableDefaultUI: true,
                        zoomControl: true,
                        mapTypeControl: false,
                        caleControl: false,
                        streetViewControl: false,
                        rotateControl: false,
                        fullscreenControl: false,
                        styles: this.mapStyles,
                    });
                    this.directionsService = new google.maps.DirectionsService;
                    this.directionsDisplay = new google.maps.DirectionsRenderer({suppressPolylines: true,suppressMarkers:true});
                    let _this = this;

                    this.borderAutocomplete = new google.maps.places.Autocomplete(this.$refs.borderWaypoint, this.options);
                    this.borderAutocomplete.addListener('place_changed', function() {
                        var place = _this.borderAutocomplete.getPlace();
                        _this.border_waypoint = {location:place.geometry.location,name:place.name};
                    });

                    this.borderReverseAutocomplete = new google.maps.places.Autocomplete(this.$refs.borderReverseWaypoint, this.options);
                    this.borderReverseAutocomplete.addListener('place_changed', function() {
                        var place = _this.borderReverseAutocomplete.getPlace();
                        _this.border_waypoint_reversed = {location:place.geometry.location,name:place.name};
                    });
                    if(this.start_waypoints.length===0){
                        this.addNewEmptyStartWaypoint();
                    }
                    if(this.end_waypoints.length===0) {
                        this.addNewEmptyEndWaypoint();
                    }
                    this.directionsDisplay.setMap(this.map);
                    if(typeof this.trip?.startWaypoint !== "undefined")
                        this.start_waypoint = this.trip.startWaypoint;
                    if(typeof this.trip?.endWaypoint !== "undefined")
                        this.end_waypoint = this.trip.endWaypoint;
                } catch (error) {
                    console.error(error);
                }
                this.$nextTick(()=>{
                    this.reInitFields();
                });
            });
        },
        computed:{
            start_waypoint:{
                get:function () {
                    return this.startWaypoint;
                },
                set:function(value){
                    this.startWaypoint = value;
                    this.trip.start_waypoint = value;
                }
            },
            end_waypoint:{
                get:function () {
                    return this.endWaypoint;
                },
                set:function(value){
                    this.endWaypoint = value;
                    this.trip.end_waypoint = value;
                }
            },
            start_waypoint_reversed:{
                get:function () {
                    return this.startWaypointReversed;
                },
                set:function(value){
                    this.startWaypointReversed = value;
                    this.trip.start_waypoint_reversed = value;
                }
            },
            end_waypoint_reversed:{
                get:function () {
                    return this.endWaypointReversed;
                },
                set:function(value){
                    this.endWaypointReversed = value;
                    this.trip.end_waypoint_reversed = value;
                }
            },
            allDistance:{
                get: function () {
                    return Math.round((this.insideDistance+this.outsideDistance)/1000);
                },
            },
            tripCost:{
                get:function(){
                    return this.trip.cost;
                },
                set:function(val){
                    this.$emit('oncalc',{cost:val})
                }
            },
            borderMarkerExists: function(){
                return typeof this.border_waypoint !== "undefined";
            },
            borderWaypointName:function () {
                if(typeof this.border_waypoint === "undefined")
                    return '';
                return this.border_waypoint.name ?? '';
            },
            borderWaypointReversedName:function () {
                if(typeof this.border_waypoint_reversed === "undefined")
                    return '';
                return this.border_waypoint_reversed.name ?? '';
            },
            insideDistanceKm:{
                get: function(){
                    return Math.round((this.insideDistance)/1000);
                },
                set: function(value){
                    value = parseFloat(value);
                    this.insideDistance = value*1000;
                    this.trip.inside_distance = value*1000;
                }
            },
            outsideDistanceKm:{
                get: function(){
                    return Math.round((this.outsideDistance)/1000);
                },
                set: function(value){
                    value = parseFloat(value);
                    this.outsideDistance = value*1000;
                    this.trip.outside_distance = value*1000;
                }
            },
        },
        watch:{
            'trip':{
                immediate: true,
                handler:function(trip){
                    if(typeof trip.start_waypoint !== "undefined" && trip.start_waypoint !== null)
                        this.$set(this,'startWaypoint',trip.start_waypoint);
                    if(typeof trip.end_waypoint !== "undefined" && trip.end_waypoint !== null)
                        this.$set(this,'endWaypoint',trip.end_waypoint);
                    if(typeof trip.start_waypoint_reversed !== "undefined" && trip.start_waypoint_reversed !== null)
                        this.$set(this,'startWaypointReversed',trip.start_waypoint_reversed);
                    if(typeof trip.end_waypoint_reversed !== "undefined" && trip.end_waypoint_reversed !== null)
                        this.$set(this,'endWaypointReversed',trip.end_waypoint_reversed);
                    if(typeof trip.start_waypoints !== "undefined" && trip.start_waypoints !== null){
                        this.$set(this,'start_waypoints',trip.start_waypoints);
                    }
                    if(typeof trip.end_waypoints !== "undefined" && trip.end_waypoints !== null){
                        this.$set(this,'end_waypoints',trip.end_waypoints);
                    }
                    if(typeof trip.start_waypoints_reversed !== "undefined" &&  trip.start_waypoints_reversed !== null){
                        this.start_waypoints_reversed = trip.start_waypoints_reversed;
                    }
                    if(typeof trip.end_waypoints_reversed !== "undefined" && trip.end_waypoints_reversed !== null){
                        this.end_waypoints_reversed = trip.end_waypoints_reversed;
                    }
                    if(typeof trip.border_waypoint !== "undefined" && trip.border_waypoint !== null){
                        this.border_waypoint = trip.border_waypoint;
                        this.border_waypoint.location = new google.maps.LatLng(trip.border_waypoint.location.lat,trip.border_waypoint.location.lng);
                    }
                    if(typeof trip.border_waypoint_reversed !== "undefined" && trip.border_waypoint_reversed !== null){
                        this.border_waypoint_reversed = trip.border_waypoint_reversed;
                        this.border_waypoint_reversed.location = new google.maps.LatLng(trip.border_waypoint_reversed.location.lat,trip.border_waypoint_reversed.location.lng);
                    }
                    if(typeof trip.type_id!=="undefined"){
                        this.type_id = trip.type_id;
                    }
                    if(typeof trip.inside_distance !== "undefined" && trip.inside_distance !== null)
                        this.insideDistance = trip.inside_distance;
                    if(typeof trip.outside_distance !== "undefined" && trip.outside_distance !== null)
                        this.outsideDistance = trip.outside_distance;
                }
            }
        },
        filters:{
            bool:function(variable){
                return (variable ?? false) == true;
            }
        }
    }
</script>

<style scoped lang="scss">
    @import "@vue/../sass/_variables.scss";
    .comment-area{
        border: 1px solid rgba(35, 138, 196, 0.2);
        box-sizing: border-box;
        border-radius: 8px;
        margin-top: 5px;
        padding: 10px;
        min-width: 400px;
    }
    .cost-info-row{
        display: grid;
        grid-template-columns: 25% 25% 40%;
        grid-column-gap: 28px;
        margin-top: 20px;
        .custom-input__block{
            min-width: 118px;
        }
    }
    .depo-row{
        display: grid;
        grid-template-columns: 30% 30% 30%;
        grid-column-gap: 28px;
        align-items: end;
    }
    .settings-input{
        .custom-input__block{
            margin-right: 11px;
            max-width: 78px;
            &.big {
                width: 145px;
                max-width: 145px;
            }
        }
        display: flex;
        align-items: center;
        color: rgba(0,0,0,0.4);
    }
    .bordered{
        border: 1px solid rgba(35, 138, 196, 0.2);
        box-sizing: border-box;
        border-radius: 8px;
        padding: 22px 32px;
    }
    .add-waypoint{
        i{
            width: 12px;
            height: 17px;
            margin-right: 10px;
        }
    }
    .cost-row{
        display: flex;
        align-items: flex-end;
        .gray-light-text, .link-text{
            height: 45px;
        }
        .link-text{
            align-items: flex-start;
            margin-left: 29px;
        }
        i{
            width: 12px;
            height: 17px;
            margin-right: 10px;
        }
        .custom-input__block{
            width: 120px;
            max-width: 120px;
            margin-right: 13px;
        }

    }
    #map{
        min-height: 500px;
        max-height: 500px;
        border-radius: 8px;
    }
    .waypoint-row{
        display: grid;
        grid-template-columns: 8fr 4fr 1fr;
        &.waypoint-titles{
            margin-top: 20px;
        }
    }
    .waypoint-input-group{
        margin-top: 8px;
        margin-bottom: 12px;
        display: grid;
        grid-template-columns: 3fr 1fr;
        .waypoint-icons{
            display: flex;
            align-items: center;
            .remove-icon{
                margin: 0 5px;
            }
            .icon:nth-child(2){
                margin-right: 5px;
            }
        }
    }
    .add-marker-block{
        margin-bottom:10px;
    }

    .map-column{
        position: relative;
    }
    .distance{
        &__block{
            font-family: $Inter-Regular;
            font-style: normal;
            font-weight: normal;
            font-size: 14px;
            line-height: 22px;
            color: rgba(0, 0, 0, 0.6);
            position: absolute;
            top: 21px;
            left: 30px;
            background: #FFFFFF;
            box-shadow: 0px 0px 4px rgba(0, 0, 0, 0.05), 0px 0px 25px rgba(0, 0, 0, 0.05);
            border-radius: 8px;
            padding: 10px 18px;
        }

        &__length{
            font-family: $Inter-SemiBold;
            font-style: normal;
            font-weight: 600;
            font-size: 30px;
            line-height: 22px;
            color: #238AC4;
        }
    }
</style>
