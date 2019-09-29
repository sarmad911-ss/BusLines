<template>
    <div>
    <form class="info-tab-container" @submit.prevent="save">
        <div class="info-col__left">
            <div>
                <custom-input :label="'Route / Name der Fahrt'" :placeholder="'Route / Name der Fahrt'" class="name-input" v-model="trip.name" :require="true"></custom-input>
            </div>
            <div style="display:flex;">
                <custom-input :label="'Personen-Anzahl'" :type="'number'" :min="0" :step="1" class="people_count" v-model="trip.passengers_quantity" :require="true"></custom-input>
                <div style="margin:11px">
                    <div>
                        Busse
                    </div>
                    <div style="margin-top:-3px">
                        <div class="bus-item" v-for="(bus,index) in busIds" :key="index">
                            <custom-bus-select :items="buses" v-model="busIds[index]"></custom-bus-select>
                            <i class="icon default remove-icon" @click="removeBus(index)"></i>
                        </div>
                    </div>
                    <span @click="addNewBusSelect" class="link-text"><i class="icon plus-icon default"></i>Fügen Sie einen anderen Bus</span>
                </div>
            </div>
            <div>
                Услуги
            </div>
            <div style="display: flex; align-items: center;" v-for="(service,index) in trip.services_ids">
                <custom-select :items="services" class="client-select" style="margin-right: 22px" v-model="trip.services_ids[index]"></custom-select>
                <span @click="trip.services_ids.push(undefined)" v-if="service !== undefined && index===(trip.services_ids.length-1)" class="link-text"><i class="icon plus-icon default"></i>Добавить ещё услугу</span>
            </div>
            <div>Условия оплаты</div>
            <div style="display: flex; align-items: center;" v-for="(condition,index) in trip.conditions_ids">
                <custom-select :items="conditions" class="client-select" style="margin-right: 22px" v-model="trip.conditions_ids[index]"></custom-select>
                <span @click="trip.conditions_ids.push(undefined)" v-if="condition !== undefined && index===(trip.conditions_ids.length-1)" class="link-text"><i class="icon plus-icon default"></i>Добавить ещё условие</span>
            </div>
            <div>
                Kunde
            </div>
            <div style="display: flex; align-items: center;">
                <custom-client-select style="margin-right: 22px" class="client-select" v-model="trip.client_id" :items="clients" :require="true" @change="selectClient"></custom-client-select>
                <span @click="openModal('newClient',{});" class="link-text"><i class="icon edit-icon default"></i>Редактировать клиента</span>
            </div>
            <div class="info__row inputs-row" v-if="trip.client_id">
                <div>
                    <div class="gray-light-text">Verantwortlicher</div>
                    {{client.representative_name}}
                </div>
                <div>
                    <div class="gray-light-text">Telefon</div>
                    {{client.phone}}
                </div>
                <div>
                    <div class="gray-light-text">Почта</div>
                    {{client.email}}
                </div>
            </div>
            <div v-if="trip.client_id" style="margin-top:10px">
                <div class="gray-light-text">Adresse</div>
                {{client.address}}
            </div>
            <div class="info-col__left">
                <div class="misc-info">
                    <div class="custom-input__label">
                        Zusatzinformation
                        <span class="gray-text">nicht unbedingt notwendig</span>
                    </div>
                    <Trumbowyg :placeholder="'Fahrt-Informationen'" v-model="trip.misc"></Trumbowyg>
                </div>
                <button class="button" v-if="!trip.id">HInzufügen</button>
                <button class="button" v-else>herausgeben</button>
            </div>
        </div>
        <div class="info-col__right">
        <div class="sectors-title">Sektor</div>
        <div class="route-way" v-for="(route,index) in trip.segments" v-if="trip.segments">
            <div class="route-number">
                <i class="icon default route-icon no-hover"></i>
                <span class="blue-text">{{index+1}}</span>
            </div>
            <div class="route-data">
                <div><span class="gray-text">von </span>{{route.from}}</div>
                <div><span class="gray-text">bis </span>{{route.to}}</div>
                <div>
                    <img src="/public/images/germany.png" class="germany-flag" v-if="route.inside">
                    <img src="/public/images/notgermany.png" class="germany-flag" v-else>
                    <span class="bold-text">{{Math.round(route.distance/1000)}}</span>
                    <span class="gray-text"> km</span>
                </div>
            </div>
        </div>
    </div>
    </form>

        <modal id="newBus">
            <bus-form @onsave="busAdded"></bus-form>
        </modal>
        <modal id="newClient">
            <store-client-form @onsave="clientAdded" :clientId="trip.client_id"></store-client-form>
        </modal>
    </div>
</template>

<script>
    import CustomInput from "@vue/components/UI/CustomInput";
    import CustomSelect from "@vue/components/UI/CustomSelect";
    import CustomDateTime from "@vue/components/UI/CustomDateTime";
    import CustomCheckbox from "@vue/components/UI/CustomCheckbox";
    import CustomBusSelect from "@vue/components/UI/CustomBusSelect";
    import CustomClientSelect from "@vue/components/UI/CustomClientSelect";
    import Trumbowyg from "@vue/components/UI/Trumbowyg";
    import {Router} from "@vue/init.js";
    import Modal from "@vue/components/UI/Modal.vue";
    import BusForm from "@vue/components/bus-depot/BusForm.vue";
    import StoreClientForm from "@vue/components/clients/storeClientForm.vue";
    import {toFormData,toUrl} from "@vue/components/helpers/objectToQuery.js";

    export default {
        name: "StoreInfoTab",
        props: {trip:Object},
        components:{
            CustomInput,CustomSelect,CustomDateTime,Trumbowyg,CustomCheckbox,CustomBusSelect,Modal,BusForm,StoreClientForm,CustomClientSelect
        },
        data:function(){
            return {
                categories: [],
                buses:{available:[],busy:[]},
                clients:[],
                busIds:[undefined],
                client: {},
                services: [],
                conditions: [],
            }
        },
        methods:{
            selectClient(){
                if(typeof this.trip.client_id !== 'undefined')
                this.clients.forEach((client)=>{
                    if(client.id===this.trip.client_id)
                        this.client = client;
                });
            },
            getServices(){
                    Router.get("listServiceData").then(url=>{
                        axios.get(url).then(({data})=>{
                            this.services = data.data;
                        })
                    })
            },
            getConditions(){
                Router.get("listConditionData").then(url=>{
                    axios.get(url).then(({data})=>{
                        this.conditions = data.data;
                    })
                })
            },
            busAdded(bus){
                this.closeModal("newBus");
                if(this.busIds[0]===undefined){
                    this.busIds.splice(0,1,bus.id);
                }else
                    this.busIds.push(bus.id);
                this.getBuses();
            },
            clientAdded(client){
                this.closeModal("newClient");
                this.trip.client_id = client.id;
                this.getClients();
            },
            getBuses(){
                Router.get("listBusSelectData").then((url)=>{
                    let dates = {};
                    if(this.startTime ?? false)
                        dates.start_date = this.startTime;
                    if(this.endTime ?? false)
                        dates.end_date = this.endTime;
                    axios.get(url+"?"+toUrl(dates)).then(({data})=>{
                        this.buses = data.data;
                    });
                })
            },
            getClients(){
                Router.get("listClientsData").then((url)=>{
                    axios.get(url+"?all=true").then(({data})=>{
                        this.clients = data.data;
                        this.selectClient();
                    });
                })
            },
            addNewBusSelect(){
                this.busIds.push(undefined);
            },
            removeBus(index){
                this.busIds.splice(index,1);
            },
            save(){
                let ids = JSON.parse(JSON.stringify(this.busIds));
                if(typeof  this.trip.buses !== "undefined")
                    this.trip.buses.forEach((bus,index)=>{
                       if(ids.indexOf(bus.bus_id)>=0){
                           ids.splice(ids.indexOf(bus.bus_id),1);
                       }else{
                           this.trip.buses.splice(index,1);
                       }
                    });
                if(!(this.trip.buses instanceof Array))
                    this.trip.buses = [];
                ids.forEach((id)=>{
                   this.trip.buses.push({bus_id:id});
                });
                Router.get("storeTripAction").then((url)=>{
                    let trip = toFormData(this.trip);
                    axios.post(url,trip).then(({data})=>{
                        location.href = data.data.profile_url;
                    })
                })
            }
        },
        mounted:function(){
            Router.get("listTripTypeData").then((url)=>{
                axios.get(url).then(({data})=>{
                   this.categories = data.data;
                });
            });
            this.getBuses();
            this.getClients();
            this.getServices();
            this.getConditions();
            if(typeof this.trip.conditions_ids === "undefined" || this.trip.conditions_ids.length===0){
                this.trip.conditions_ids = [undefined];
            }
            if(typeof this.trip.services_ids === "undefined" || this.trip.services_ids.length===0){
                this.trip.services_ids = [undefined];
            }
        },
        watch:{
            "trip.conditions":{
              immediate: true,
              handler(val){
                  this.$set(this.trip,'conditions',val);
              }
            },
            "trip.services":{
              immediate: true,
              handler(val){
                  this.$set(this.trip,'services',val);
              }
            },
            "trip.buses":{
                // the callback will be called immediately after the start of the observation
                immediate: true,
                handler (val, oldVal) {
                    if(val instanceof Array)
                        this.busIds= val.map(function(item){
                            return item.bus_id;
                        });
                    if(this.busIds.length===0) this.busIds = [undefined];
                }
            },
            "trip.client_id":{
                // the callback will be called immediately after the start of the observation
                immediate: true,
                handler (val, oldVal) {
                    this.selectClient();
                }
            },
            "trip.conditions_ids":{
                immediate: true,
                handler (val, oldVal) {
                    this.$set(this.trip,"conditions_ids",val);
                }
            },
            "trip.services_ids":{
                immediate: true,
                handler (val, oldVal) {
                    this.$set(this.trip,"services_ids",val);
                }
            }
        },
        computed:{
            startTime(){
                return this.trip.start_waypoint?.date;
            },
            endTime(){
                return (this.trip.end_waypoint_reversed ?? this.trip.end_waypoint)?.date;
            }
        }
    }
</script>

<style scoped lang="scss">
    @import "@vue/../sass/_variables.scss";
    .info-tab-container{
        display: grid;
        grid-template-columns: repeat(12,1fr);
        column-gap: 25px;
    }
    .info__row{
        grid-column: span 12;
    }
    .info-col__left{
        grid-column: span 7;
    }
    .info-col__right{
        grid-column: span 5;
    }
    .sectors-title{
        font-family: $Inter-SemiBold;
        font-style: normal;
        font-weight: 600;
        font-size: 14px;
        line-height: 17px;
        color: rgba(0, 0, 0, 0.8);
    }
    .route-way{
        display: flex;
        align-items: center;
        margin-top: 15px;
        .route-number{
            margin-right: 15px;
            display: flex;
            align-items: center;
            font-family: $Inter-Medium;
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
    .name-input{
        width: 466px;
        min-width: 466px;
    }
    .inputs-row{
        display: flex;
        &>div{
            margin-right: 25px;
        }
    }
    .dates-row{
        .custom-input__block{
            width: 181px;
            min-width: 181px;
            margin-right: 32px;
        }
    }
    .category-select{
        width: 260px;
        min-width: 260px;
    }
    .people_count{
        width: 120px;
        min-width: 120px;
    }
    .bus-item{
        display: flex;
        align-items: center;
        .custom-input__block{
            width: 310px;
            max-width: 310px;
            margin-right: 21px;
        }
        .plus-icon{
            width: 14px;
            height: 14px;
        }
        .remove-icon{
            width:14px;
            height: 14px;
            margin-right:10px;
        }
    }
    .client-select{
        width: 310px;
        max-width: 310px;
    }
    .client-representative{
        width: 260px;
        max-width: 260px;
    }
    .client-phone{
        width: 220px;
        max-width: 220px;
    }
    .misc-info{
        .custom-input__label{
            margin-top: 10px;
            margin-bottom: 0;
        }
    }
</style>
