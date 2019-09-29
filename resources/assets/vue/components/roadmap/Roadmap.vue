<template>
    <div class="roadmap-block">
        <div id="calculator">
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

                <p><span class="gray-light-text">Депо</span> <span class="gray-text">{{startWaypoint.name}}</span></p>

                <div v-show="(stage==='thitherward' && type_id===2) || type_id===1">
                    <div v-if="start_waypoints.length">
                        Пункт сбора
                        <div v-for="(waypoint,i) in start_waypoints" class="waypoint-input-group">
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
                        Пункт назначения
                        <div v-for="(waypoint,i) in end_waypoints" class="waypoint-input-group">
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
                    </div>
                    <div class="add-marker-block">
                        <span class="link-text" @click="addNewEmptyEndWaypoint">
                            <i class="icon default marker-icon"></i>
                            Добавить пункт назначения
                        </span>
                    </div>
                </div>
                <div v-show="(stage==='backward' && type_id===2)">
                    <div v-if="start_waypoints_reversed.length">
                        Пункт сбора
                        <div v-for="(waypoint,i) in start_waypoints_reversed" class="waypoint-input-group">
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
                        Пункт назначения
                        <div v-for="(waypoint,i) in end_waypoints_reversed" class="waypoint-input-group">
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
                    </div>
                    <div class="add-marker-block">
                        <span class="link-text" @click="addNewEmptyEndWaypointReversed">
                            <i class="icon default marker-icon"></i>
                            Добавить пункт назначения
                        </span>
                    </div>
                </div>
                <p><span class="gray-light-text">Депо</span> <span class="gray-text">{{startWaypoint.name}}</span></p>
                <button class="button light make-route-button" @click="calcRoutes">Проложить маршрут</button>
            </div>
            <calculator @changeInsideDistance="changeInsideDistanse" @calculatedPercent="setProfitPercent" id="calculatorComponent" :vars="vars" @changeVars="changeVars" @changeCost="setCost" @create-trip="createTrip" :inside-distance="insideDistance" :outside-distance="outsideDistance"></calculator>
        </div>
        <div class="map-block">
            <div ref="map" id="map"></div>
            <div class="map-tooltip" v-if="(insideDistance || outsideDistance) && !borderMarkerExists">
                <i class="icon large redmarker-icon"></i> Punk für Grenzübergang manuell markieren
            </div>
            <div class="routes-list" v-if="insideDistance || outsideDistance">
                <div class="total-distance">
                    <div>
                        <span class="big-blue-text">{{routeLength}}</span> km
                    </div>
                    Gesamtstrecke KM
                </div>
                <div>
                    <div v-for="(route,i) in routes" class="route-way">
                        <div class="route-number">
                            <i class="icon default route-icon no-hover"></i>
                            <span class="blue-text">{{i+1}}</span>
                        </div>
                        <div class="route-data">
                            <div><span class="gray-text">Von </span>{{route.from}}</div>
                            <div><span class="gray-text">Bis </span>{{route.to}}</div>
                            <div>
                                <img src="/public/images/germany.png" class="germany-flag" v-if="waypointInsideCountry(i-1)"></img>
                                <span class="bold-text">{{Math.round(route.distance.value/1000)}}</span>
                                <span class="gray-text"> km</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import gmapsInit from '@vue/components/roadmap/gmapInit.js';
    import Calculator from '@vue/components/roadmap/Calculator.vue';
    import {Router} from "@vue/init.js";
    import {toFormData} from "@vue/components/helpers/objectToQuery.js";
    import CustomInput from "@vue/components/UI/CustomInput.vue";
    import {toUrl} from "@vue/components/helpers/objectToQuery.js";

    export default {
        name: "Roadmap",
        data(){
          return{
              type_id:1,
              stage: 'thitherward',
              directionsService : null,
              directionsDisplay : null,
              autocomplete : null,
              borderAutocomplete: null,
              borderReverseAutocomplete:null,
              newAutocomplete: null,
              map : null,
              google: null,
              center: {lat: 52.520008, lng: 13.404954},
              zoom : 6,
              autocompleteOptions: {'type' : "address"},
              start_waypoints: [],
              end_waypoints: [],
              start_waypoints_reversed:[],
              end_waypoints_reversed:[],
              border_waypoint:undefined,
              border_waypoint_reversed:undefined,
              startWaypoint: {
                  location:"Breniger Str., 53913 Swisttal, Germany",
                  name:"Breniger Straße",
              },
              endWaypoint: {
                  location:"Breniger Str., 53913 Swisttal, Germany",
                  name:"Breniger Straße",
              },
              startWaypointReversed: {
                  location:"Breniger Str., 53913 Swisttal, Germany",
                  name:"Breniger Straße",
              },
              endWaypointReversed: {
                  location:"Breniger Str., 53913 Swisttal, Germany",
                  name:"Breniger Straße",
              },
              routes: [],
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
              cost: 0,
              vars:{
                  nds:19,
                  adminCostsByKm:0.5,
                  taxOnKm : 0.28,
                  taxOnFuel: 1.05,
                  driverSalary : 150,
                  days: 1,
                  otherCosts: 0,
                  profitPercent:20,
                  kmInside: 0,
                  kmInsideWithNds: false,
              },
              calculation_id : undefined,
              settingsNames : ['nds','admin_by_km','tax_by_km','fuel_cost','driver_cost',"depo"],
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
              ],
          }
        },
        methods:{
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
            },
            changeInsideDistanse(val){
                this.insideDistance = val;
            },
            getSettings(){
                Router.get("listSettingsData").then((url)=>{
                    let query = toUrl({items:this.settingsNames});
                    axios.get(url+"?"+query).then(({data})=>{
                        data.data.forEach((setting)=>{
                            if(setting.key==="nds")
                                this.$set(this.vars,'nds',parseFloat(setting.value));
                            if(setting.key==="fuel_cost")
                                this.$set(this.vars,'taxOnFuel',parseFloat(setting.value));
                            if(setting.key==="driver_cost")
                                this.$set(this.vars,'driverSalary',parseFloat(setting.value));
                            if(setting.key==="tax_by_km")
                                this.$set(this.vars,'taxOnKm',parseFloat(setting.value));
                            if(setting.key==="admin_by_km")
                                this.$set(this.vars,'adminCostsByKm',parseFloat(setting.value));
                            if(setting.key==="depo") {
                                this.$set(this, 'startWaypoint', JSON.parse(setting.value));
                                this.$set(this, 'endWaypoint', JSON.parse(setting.value));
                                this.$set(this, 'startWaypointReversed', JSON.parse(setting.value));
                                this.$set(this, 'endWaypointReversed', JSON.parse(setting.value));
                            }
                        });
                    })
                })
            },
            changeVars(vars){
                Object.assign(this.vars,vars);
            },
            setProfitPercent(percent){
              this.vars.profitPercent = percent;
            },
            saveCalculation(){
                // TODO rewrite this
                let calculationData = {
                    start_waypoints: this.start_waypoints,
                    end_waypoints: this.end_waypoints,
                    border_waypoint: this.border_waypoint,
                    border_waypoint_reversed: this.border_waypoint_reversed,
                    start_waypoints_reversed: this.start_waypoints_reversed,
                    end_waypoints_reversed: this.end_waypoints_reversed,
                    inside_distance: this.insideDistance,
                    outside_distance: this.outsideDistance,
                    profit_percent: this.vars.profitPercent ?? undefined,
                    km_inside: this.vars.kmInside ?? undefined,
                    km_inside_with_nds: this.vars.kmInsideWithNds ?? undefined,
                    other_costs: this.vars.otherCosts ?? undefined,
                    days: this.vars.days ?? undefined,
                    id: this.calculation_id,
                    type: this.type_id,
                    stage: this.stage,
                    cost: this.cost,
                };
                Router.get("storeCalculationAction").then((url)=>{
                    axios.post(url,calculationData).then(({data})=>{
                        this.calculation_id = data.data.id;
                    })
                })
            },
            loadCalculation(){
                let params = new URLSearchParams(window.location.search);
                let id = params.get("id");
                if(id !== null){
                    Router.get("storeCalculationData").then((url)=>{
                        axios.get(url+"?id="+id).then(({data})=>{
                            this.start_waypoints = data.data.start_waypoints ?? undefined;
                            this.start_waypoints_reversed = data.data.start_waypoints_reversed ?? undefined;
                            this.end_waypoints = data.data.end_waypoints ?? undefined;
                            this.end_waypoints_reversed = data.data.end_waypoints_reversed ?? undefined;
                            if(typeof (data.data.border_waypoint_reversed ?? undefined) !== "undefined") {
                                this.border_waypoint_reversed = data.data.border_waypoint_reversed ?? undefined;
                                this.border_waypoint_reversed.location = new google.maps.LatLng(this.border_waypoint_reversed.location.lat,this.border_waypoint_reversed.location.lng);
                            }
                            if(typeof (data.data.border_waypoint_reversed ?? undefined) !== "undefined") {
                                this.border_waypoint = data.data.border_waypoint ?? undefined;
                                this.border_waypoint.location = new google.maps.LatLng(this.border_waypoint.location.lat,this.border_waypoint.location.lng);
                            }
                            this.vars.days = isNaN(parseInt(data.data.days)) ? this.vars.days : parseInt(data.data.days);
                            this.vars.kmInside = isNaN(parseInt(data.data.km_inside)) ? this.vars.kmInside : parseInt(data.data.km_inside);
                            this.vars.kmInsideWithNds = data.data.km_inside_with_nds == true;
                            this.vars.otherCosts = isNaN(parseFloat(data.data.other_costs)) ? this.vars.otherCosts : parseFloat(data.data.other_costs);
                            this.vars.profitPercent = isNaN(parseFloat(data.data.profit_percent)) ? this.vars.profitPercent : parseFloat(data.data.profit_percent) ;
                            this.calculation_id = data.data.id;
                            this.type_id = parseInt(data.data.type ?? 1);
                            this.stage = data.data.stage;

                            this.insideDistance = parseInt(data.data.inside_distance);
                            this.outsideDistance = parseInt(data.data.outside_distance);
                            this.cost = isNaN(parseFloat(data.data.cost)) ? 0 : parseFloat(data.data.cost);
                        })
                    })
                }
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
                if(typeof this.border_waypoint !== "undefined")
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
                if(typeof this.endWaypoint !== "undefined" && this.type_id === 2 && this.startWaypointReversed.location !== this.endWaypoint.location)
                    waypoints.push({
                        location: this.endWaypoint.location,
                        stopover: true,
                    });
                if(typeof this.startWaypointReversed !== "undefined" && this.type_id === 2)
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
                if(typeof this.border_waypoint_reversed !== "undefined")
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
                if(waypoints.length===0) return false;
                let _this = this;
                if(this.startWaypoint && this.endWaypoint) {
                    let params ={
                        origin: this.startWaypoint.location,
                        destination: (this.type_id===2 ? this.endWaypointReversed.location : this.endWaypoint.location),
                        waypoints: waypoints,
                        optimizeWaypoints: this.optimizeWaypoints,
                        travelMode: 'DRIVING'
                    };
                    this.directionsService.route(params, function (response, status) {
                        if (status === 'OK') {
                            _this.directionsDisplay.setDirections(response);
                            let route = response.routes[0];
                            _this.routes = [];
                            for (var i = 0; i < route.legs.length; i++) {
                                let startAddress = route.legs[i].start_address;
                                let endAddress = route.legs[i].end_address;
                                if (_this.isBordermarker(route.legs[i].start_location))
                                    startAddress = "Grenzübergang";
                                if (_this.isBordermarker(route.legs[i].end_location))
                                    endAddress = "Grenzübergang";
                                _this.routes.push({
                                    from: startAddress,
                                    to: endAddress,
                                    distance: route.legs[i].distance,
                                })
                            }
                            _this.drawPolyline(response);
                            _this.drawMarkers(response);
                        } else {
                            window.alert('Directions request failed due to ' + status);
                        }

                    });
                }
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
            createTrip(){
                let trip = {
                    type_id: this.type_id,
                    start_waypoints: JSON.parse(JSON.stringify(this.start_waypoints)),
                    end_waypoints: JSON.parse(JSON.stringify(this.end_waypoints)),
                    start_waypoints_reversed: JSON.parse(JSON.stringify(this.start_waypoints_reversed)),
                    end_waypoints_reversed: JSON.parse(JSON.stringify(this.end_waypoints_reversed)),
                    segments: this.routes.map((segment)=>{
                        return {from:segment.from,to:segment.to,distance:segment.distance.value};
                    }),
                    start_waypoint: this.startWaypoint,
                    end_waypoint: this.endWaypoint,
                    start_waypoint_reversed: this.startWaypointReversed,
                    end_waypoint_reversed: this.endWaypointReversed,
                    border_waypoint: typeof this.border_waypoint === "undefined" ? undefined :  JSON.parse(JSON.stringify(this.border_waypoint)),
                    border_waypoint_reversed: typeof this.border_waypoint_reversed === "undefined" ? undefined : JSON.parse(JSON.stringify(this.border_waypoint_reversed)),
                    cost: this.cost,
                    inside_distance: this.insideDistance,
                    outside_distance: this.outsideDistance,
                };
                let params = new URLSearchParams(window.location.search);
                let trip_id = params.get("trip_id");
                if(trip_id !== null)
                    trip.id = trip_id;
                Router.get("storeTripAction").then((url)=>{
                    axios.post(url,trip).then(({data})=>{
                        location.href = data.data.store_url;
                    })
                })
            },
            setCost(value){
                this.cost = value;
                if(this.insideDistance)
                    this.saveCalculation();
            }
        },
        async mounted() {
            try {
                this.google = await gmapsInit();
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
                    styles:this.mapStyles,
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
                this.directionsDisplay.setMap(this.map);
                this.addNewEmptyStartWaypoint();
                this.addNewEmptyEndWaypoint();
            } catch (error) {
                console.error(error);
            }
            this.getSettings();
            this.loadCalculation();
            await this.$nextTick(()=>{
                this.hideLoader();
            });
        },
        computed:{
            routeLength: function(){
                let length = this.insideDistance+this.outsideDistance;
                return Math.round(length/100)/10;
            },
            insideDistanceKm: function(){
                let text =this.routes.length ? this.routes[0].distance.text.split(" ")[1] : "";
                return Math.round(this.insideDistance/100)/10+" "+text;
            },
            insideDistanceInKm: {
                get: function(){
                    return Math.round(this.insideDistance/100)/10;
                },
                set: function(val){
                    this.insideDistance = parseFloat((val+"").trim())*1000;
                }
            },
            outsideDistanceKm: function(){
                let text =this.routes.length ? this.routes[0].distance.text.split(" ")[1] : "";
                return Math.round(this.outsideDistance/100)/10+" "+text;
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
            }
        },
        components:{
            Calculator, CustomInput,
        },
        // watch:{
        //     cost:function () {
        //         this.saveCalculation();
        //     }
        // }
    }
</script>

<style scoped lang="scss">

    .roadmap-block{
        display: grid;
        grid-template-columns: repeat(12, 1fr);
        grid-column-gap: 25px;
        .page-title{
            display: flex;
            .link-text{
                margin-left: 20px;
                .icon{
                    margin-right: 6px;
                }
            }
        }
        #map{
            min-height: 500px;
            max-height: 500px;
            border-radius: 8px;
        }
        .add-marker-block{
            margin: 16px 0;
        }
        .waypoint-input-group{
            margin-top: 8px;
            margin-bottom: 12px;
            display: grid;
            grid-template-columns: 4fr 1fr;
            .waypoint-icons{
                display: flex;
                align-items: center;
                .remove-icon{
                    margin: 0 20px;
                }
                .icon:nth-child(2){
                    margin-right: 13px;
                }
            }
        }
        .routes-list{
            margin-top: 31px;
            border-left: 1px solid rgba(35, 138, 196, 0.2);
            padding-left: 23px;
            .total-distance{
                margin-bottom: 31px;
            }
            .route-way{
                display: flex;
                align-items: center;
                margin-bottom: 15px;
                .route-number{
                    margin-right: 15px;
                    display: flex;
                    align-items: center;
                    font-family: 'Inter-Medium', Helvetica, Arial, sans-serif;
                    font-style: normal;
                    font-weight: 500;
                    font-size: 12px;
                    .icon{
                        width:15px;
                        height:18px;
                        margin-right: 3px;
                    }
                }
                .germany-flag{
                    width: 16px;
                    margin-right: 8px;
                }
            }
        }
        #calculator{
            grid-column: span 6;
            .bordered{
                border: 1px solid rgba(35, 138, 196, 0.2);
                box-sizing: border-box;
                border-radius: 8px;
                padding: 22px 32px;
            }
        }
        .make-route-button{
            margin-top: 11px;
        }
        .map-block{
            grid-column: span 6;
        }
    }

    .kilometers-input{
        display: flex;
        align-items: center;
        .custom-input__block{
            width: 80%;
            margin-right: 11px;
        }
    }
</style>
