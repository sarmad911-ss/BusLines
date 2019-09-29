<template>
        <div class="bus-layout">
            <div class="page-title col-12">{{formTitle}}</div>
            <custom-select
                    :label="'Fahrzeugtyp'"
                    :items="types"
                    v-model="bus.type_id"
                    :class="'col-6'"
            >
            </custom-select>
            <custom-input
                    :label="'Model'"
                    :class="'col-6'"
                    v-model="bus.model"
            >
            </custom-input>

            <custom-input
                    :label="'Bus Kennzeichen'"
                    :class="'col-4'"
                    v-model="bus.plate_number"
                    :max-length="12"
            >
            </custom-input>
            <custom-input
                    :label="'Sitzplätze'"
                    :class="'col-2'"
                    v-model="bus.seats_quantity"
                    :type="'number'"
                    :min="0"
                    :step="1"
                    :max="100"
            >
            </custom-input>
            <custom-date-time
                    :label="'Erstzulassung'"
                    :class="'col-3'"
                    v-model="bus.release_date"
            >
            </custom-date-time>
            <div class="col-3 with-text">
                <custom-input
                        :label="'KM-Stand'"
                        :class="'col-2'"
                        v-model="bus.mileage_start"
                        :type="'number'"
                        :min="0"
                        :step="1"
                        show-arrows="false"
                >
                </custom-input>
                <span class="gray-light-text">tausend km</span>
            </div>
            <custom-select
                    :label="'Eigentümer'"
                    :items="owners"
                    v-model="bus.owner_id"
                    :class="'col-6'"
            >
            </custom-select>
            <custom-input
                    :label="'Fahrzeug-ID'"
                    :class="'col-6'"
                    v-model="bus.vin"
                    :placeholder="'Fahrzeug-ID'"
            >
            </custom-input>


            <div class="col-12">
                <div class="custom-input__label misc-info">
                    Zusatzinformationen
                    <span class="gray-text">nicht unbedingt notwendig</span>
                </div>
                <trumbowyg v-model="bus.misc" id="busMisc"></trumbowyg>
            </div>


            <custom-date-time
                    v-for="date in bus.dates"
                    :key="date.id"
                    :label="`${date.name}`"
                    v-model="date.date"
                    :class="'col-4'">
            </custom-date-time>

            <div class="col-12">
                <button @click="saveBus" class="button">Speichern</button>
            </div>
        </div>
</template>

<script>
    import {Router} from "@vue/init.js";
    import Trumbowyg from "@vue/components/UI/Trumbowyg.vue";
    import {toFormData} from "@vue/components/helpers/objectToQuery.js";
    import CustomSelect from "@vue/components/UI/CustomSelect.vue";
    import CustomInput from "@vue/components/UI/CustomInput.vue";
    import CustomDateTime from "@vue/components/UI/CustomDateTime.vue";

    export default {
        name: "BusForm",
        data:function(){
          return {
              types:[],
              owners:[],
              euro_norm:[],
              bus:{},
              saved: false,
          }
        },
        methods:{
            saveBus(){
                Router.get("storeBusAction").then((url)=>{
                    let data = toFormData(this.bus);
                    axios.post(url,data).then(({data})=>{
                        this.$emit("onsave",data.data);
                        this.saved = true;
                    });
                });
            }
        },
        props: {busId:{required: false, default:null}},
        mounted() {
            Router.get("storeBusData").then((url)=>{
                let nurl = this.busId ? url+"?id="+this.busId : url;
                axios.get(nurl).then(({data})=>{
                   this.bus = data.data;
                   this.hideLoader();
                });
            });
            Router.get("listOwnersData").then((url)=>{
                axios.get(url).then(({data})=>{
                    this.owners = data.data;
                });
            });
            Router.get("listBusEuroNormsData").then((url)=>{
                axios.get(url).then(({data})=>{
                    this.euro_norm = data.data;
                });
            });
            Router.get("listBusTypesData").then((url)=>{
                axios.get(url).then(({data})=>{
                    this.types = data.data;
                });
            });
        },
        computed:{
            formTitle:function(){
                return  this.busId === null ? "Bus hinzufügen" : "Bus erstellen / Bearbeiten";
            }
        },
        components:{
            Trumbowyg,CustomSelect,CustomInput,CustomDateTime
        },
    }
</script>

<style scoped lang="scss">
        .bus-layout{
            grid-column: span 8;
            display: grid;
            grid-template-columns: repeat(12,1fr);
            grid-template-rows: repeat(8,auto);
            grid-column-gap: 25px;
            .col-12 {
                grid-column: span 12;
            }
            .col-6{
                grid-column: span 6;
            }.col-5{
                grid-column: span 5;
            }.col-4{
                grid-column: span 4;
            }.col-3{
                grid-column: span 3;
            }.col-2{
                grid-column: span 2;
            }.col-1{
                grid-column: span 1;
            }
        }
        .with-text{
            display: flex;
            align-items: flex-end;
            span{
                min-width: max-content;
                height: 45px;
                margin-left:12px;
            }
        }
        .misc-info{
            margin-top: 16px;
        }
</style>