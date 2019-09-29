<template>
    <div class="info-tab" v-if="trip">
        <div class="info-tab__content">
            <div class="status-block">
                <div class="status-select">
                    <custom-select :items="statuses" :label="'Reisestatus'" v-model="trip.status_id" @change="changeStatus"></custom-select>
                </div>
                <div class="dates-block">
                    <div>Fahrtdatum</div>
                    <div class="dates">
                        {{trip.start_date_f}} – {{trip.end_date_f}}
                    </div>
                    <div class="gray-light-text">
                        {{trip.times_left}}
                    </div>
                </div>
            </div>
            <div class="info-table">
                <div class="info-col">
                    <div class="info-table__row" v-if="trip.type">
                        <div class="gray-light-text">Leistungen</div>
                        <div>{{trip.type}}</div>
                    </div>
                    <div class="info-table__row" v-if="trip.passengers_quantity">
                        <div class="gray-light-text">Personen-Anzahl</div>
                        <div>{{trip.passengers_quantity}} pers.</div>
                    </div>
                    <div class="info-table__row">
                        <div class="gray-light-text">Preis</div>
                        <div>
                            {{trip.cost}} €
                            <span class="gray-light-text">
                            ({{trip.paid}} € Ausgleich)
                        </span>
                        </div>
                    </div>
                </div>
                <div class="info-col" v-if="trip.client">
                    <div class="info-table__row" v-if="trip.client.name">
                        <div class="gray-light-text">Kunde</div>
                        <div>{{trip.client.name}}</div>
                    </div>
                    <div class="info-table__row" v-if="trip.client.representative_name">
                        <div class="gray-light-text">Verantwortlicher</div>
                        <div>{{trip.client.representative_name}}</div>
                    </div>
                    <div class="info-table__row" v-if="trip.client.phone">
                        <div class="gray-light-text">Telefon</div>
                        <div>
                            {{trip.client.phone}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="gray-light-text content-title" v-if="trip.buses && trip.buses.length">
                Busse
            </div>
            <div class="bus-list__container" v-if="trip.buses && trip.buses.length">
                <div class="bus-list__item" v-for="bus in trip.buses" :key="bus.id">
                    <div class="bus-number">
                        {{bus.plate_number}}
                    </div>
                    <div class="default-text">
                        {{bus.type}} / {{bus.model}}
                    </div>
                    <div class="gray-light-text">
                        {{bus.seats_quantity}} Orte
                    </div>
                </div>
            </div>
            <div class="gray-light-text content-title" v-if="trip.misc">
                Zusatzinformation
            </div>
            <div class="default-text" v-html="trip.misc" v-if="trip.misc"></div>
        </div>
    </div>
</template>

<script>
    import CustomSelect from "@vue/components/UI/CustomSelect.vue";
    import {Router} from "@vue/init.js";

    export default {
        name: "TripInfoTab",
        props:{trip:Object},
        data: function(){
            return {
                statuses:[],
            }
        },
        components:{
            CustomSelect,
        },
        mounted() {
            Router.get("listTripStatusData").then((url)=>{
                axios.get(url).then(({data})=>{
                    this.statuses = data.data;
                });
            })
        },
        methods:{
            changeStatus(){
                Router.get("storeTripAction").then((url)=>{
                    axios.post(url,{
                        id:this.trip.id,
                        status_id:this.trip.status_id,
                    })
                })
            }
        }
    }
</script>

<style scoped lang="scss">
    @import "@vue/../sass/_variables.scss";

    .info-table{
        font-family: 'Inter-Medium', Helvetica, Arial, sans-serif;
        font-style: normal;
        font-weight: normal;
        font-size: 14px;
        line-height: 28px;
        color: rgba(0, 0, 0, 0.8);
        margin-bottom: 31px;
        grid-template-columns: repeat(auto-fill,minmax(400px,1fr));
        display: grid;
        &__row {
            display: flex;
            height: 28px;
            align-items: center;
            >div:first-child{
                width: 160px;;
            }
        }
        &__splitter{
            height: 9px;
        }
    }

    .bus-list{
        &__container{
            margin-bottom: 30px;
        }
        &__item{
            display: flex;
            align-self: center;
            margin-bottom: 15px;
            .default-text{
                margin: 0 15px 0 12px;
            }
        }
    }

    .content-title{
        margin-bottom: 15px;
    }

    .status-block{
        display: grid;
        grid-template-columns: repeat(8,1fr);
        grid-column-gap: 25px;
        .dates-block{
            grid-column: span 5;
            margin: 11px 0;
            .dates{
                font-family: $Inter-SemiBold;
                font-style: normal;
                font-weight: 600;
                font-size: 16px;
                line-height: 22px;
                margin-top: 10px;
            }
        }
        .status-select{
            grid-column: span 3;
        }
    }

    .info-tab{
        display: grid;
        grid-template-columns: repeat(12,1fr);
        grid-column-gap: 25px;
        &__content{
            grid-column: span 8;
        }
    }
</style>