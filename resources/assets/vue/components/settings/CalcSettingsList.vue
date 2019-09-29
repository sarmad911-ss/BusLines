<template>
    <div class="calc-settings">
        <div class="calc-settings__row">
            <div class="calc-settings__col">
                <custom-input
                        :label="'MwSt.'"
                        :type="'number'"
                        v-model="nds.value"
                        :min="0"
                        :placeholder="'0.00'"
                        @blur="save"
                        :step="0.01"
                ></custom-input>
                <span>%</span>
            </div>
            <div class="calc-settings__col">
                <custom-input
                        :label="'Faktor für Verwaltungskosten'"
                        :type="'number'"
                        v-model="adminByKm.value"
                        :min="0"
                        :step="0.01"
                        :placeholder="'0.00'"
                        @blur="save"
                ></custom-input>
                <span>€ pro km</span>
            </div>
            <div class="calc-settings__col">
                <custom-input
                        :label="'Mwst. für KM vor Ort'"
                        :type="'number'"
                        v-model="taxByKm.value"
                        :min="0"
                        :step="0.01"
                        :placeholder="'0.00'"
                        @blur="save"
                ></custom-input>
                <span>€ pro km</span>
            </div>
        </div>
        <div class="calc-settings__row">
            <div class="calc-settings__col">
                <custom-input
                        :label="'Treibstoff-Preis'"
                        :type="'number'"
                        v-model="fuelCost.value"
                        :min="0"
                        :step="0.01"
                        :placeholder="'0.00'"
                        @blur="save"
                ></custom-input>
                <span>€ pro km</span>
            </div>
            <div class="calc-settings__col">
                <custom-input
                        :label="'Lohn Busfahrer'"
                        :type="'number'"
                        v-model="driverCost.value"
                        :min="0"
                        :placeholder="'0.00'"
                        @blur="save"
                        :step="0.01"
                ></custom-input>
                <span>€ pro Tag</span>
            </div>
        </div>
    </div>
</template>

<script>
    import {Router} from "@vue/init.js";
    import CustomInput from "@vue/components/UI/CustomInput.vue";
    export default {
        name: "CalcSettingsList",
        data(){
            return {
                settingsNames : ['nds','admin_by_km','tax_by_km','fuel_cost','driver_cost'],
                settings: [],
                saved: false
            }
        },
        methods:{
            getSettings(key){
                let value = null;
                this.settings.forEach((setting)=>{
                    if(setting.key===key)
                        value = setting;
                });
                if(value === null){
                    value =  {
                        key: key,
                        value: null,
                    };
                    this.settings.push(value);
                }
                return value;
            },
            setSetting(key,value){
                this.settings.forEach((setting)=>{
                    if(setting.key===key)
                        setting.value = value;
                });
            },
            save(){
                let data = new FormData();
                this.settings.forEach((setting,index)=>{
                    data.append(`items[${index}][key]`,setting['key']);
                    data.append(`items[${index}][value]`,setting['value']);
                });
                Router.get("storeSettingsAction").then((url)=>{
                    axios.post(url,data).then(({data})=>{
                        this.settings = data.data;
                        this.saved = true;
                        setTimeout(()=>{
                            this.saved = false;
                        },3000)
                    });
                })
            }
        },
        mounted(){
            Router.get("listSettingsData").then((url)=>{
                let items = [];
                this.settingsNames.forEach(function(item){
                    items.push("items[]="+item);
                });
                items = items.join("&");
                axios.get(`${url}?${items}`,{'items':this.settingsNames}).then(({data})=>{
                    this.settings = data.data;
                })
            });
        },
        computed:{
            nds:{
                set(value){
                    this.setSetting("nds",value);
                },
                get(){
                    return this.getSettings("nds");
                }
            },
            adminByKm:{
                set(value){
                    this.setSetting("admin_by_km",value);
                },
                get(){
                    return this.getSettings("admin_by_km");
                }
            },
            taxByKm:{
                set(value){
                    this.setSetting("tax_by_km",value);
                },
                get(){
                    return this.getSettings("tax_by_km");
                }
            },
            fuelCost:{
                set(value){
                    this.setSetting("fuel_cost",value);
                },
                get(){
                    return this.getSettings("fuel_cost");
                }
            },
            driverCost:{
                set(value){
                    this.setSetting("driver_cost",value);
                },
                get(){
                    return this.getSettings("driver_cost");
                }
            },

        },
        components:{
            CustomInput,
        }
    }
</script>

<style scoped lang="scss">
    .calc-settings{
        color: rgba(0,0,0,0.4);
        &__row{
            display: flex;
        }
        &__col{
            max-width: 222px;
            min-width: 222px;
            display: flex;
            align-items: flex-end;
            .custom-input__block{
                max-width:110px;
                min-width:110px;
                margin-right: 10px;
            }
            span{
                height: 45px;
                display:inline-block;
            }
        }
    }
</style>
