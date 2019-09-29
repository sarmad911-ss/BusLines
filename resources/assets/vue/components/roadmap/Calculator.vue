<template>
    <div>
        <div class="calculator-params">
            <div>
                Reisedauer
                <div class="settings-input">
                    <custom-input v-model="days" :type="'number'" :min="1" :step="1"></custom-input>
                    tage
                </div>
            </div>
            <div>
                Gewinn in %
                <div class="settings-input">
                    <custom-input v-model="profitPercent" :type="'number'" :min="0" :step="1"></custom-input>
                    %
                </div>
            </div>
            <div>
                Kilometer mit Mvst.
                <div class="kilometers-input">
                    <custom-input v-model="insideDistanceInKm" :type="'number'" :min="0" :step="0.01"></custom-input>
                    km.
                </div>
            </div>
            <div>
                sonstige Ausgaben
                <div class="settings-input">
                    <custom-input v-model="otherCosts" :type="'number'" :min="0" :step="0.01" :show-arrows="false"></custom-input>
                    €
                </div>
            </div>
            <div>
                KM vor Ort
                <div class="settings-input">
                    <custom-input v-model="kmInside" :type="'number'" :min="0" :step="1" :show-arrows="false"></custom-input>
                    km
                </div>
            </div>
            <div class="large-block">
                <custom-checkbox v-model="vars.kmInsideWithNds" :label="'KM vor Ort zzgl. Mwst.'" @change="calc"></custom-checkbox>
            </div>
        </div>
        <div v-if="insideDistance || outsideDistance">
            <div class="result-data-costs">
                <div class="result-data-costs__row">
                    <div class="settings-input">
                        <custom-input v-model="costWithNds" @blur="calcByCost" :type="'number'" :show-arrows="false" :min="0" :step="0.01" :class="'big'"></custom-input> €
                    </div>
                    <div>
                        <b>{{calculated.profit}}</b> € ({{profitPercent}}%)
                    </div>
                </div>
                <div class="result-data-costs__row">
                    <div>
                        Gesamtpreis inkl. Mwst.
                    </div>
                    <div>
                        Gewinn
                    </div>
                </div>
            </div>
            <span class="link-text" @click="$emit('create-trip')">
                <i class="icon default marker-icon"></i>
                Neue Fahrt erstellen
            </span>


            <table class="gray-text result-table">
                <tr>
                    <td>Gesamtpreis ohne Mwst.</td>
                    <td><span class="black-text">{{calculated.costWithoutNds}}</span> €</td>
                </tr>
                <tr>
                    <td>Ausgaben Gesamt</td>
                    <td><span class="black-text">{{this.totalCost}}</span> €</td>
                </tr>
                <tr class="sub-row">
                    <td>Sprit</td>
                    <td><span class="black-text">{{this.fuelCost}}</span> €</td>
                </tr>
                <tr class="sub-row">
                    <td>Lohn Busfahrer</td>
                    <td><span class="black-text">{{vars.driverSalary}}</span> €</td>
                </tr>
                <tr>
                    <td>Verwaltungskosten</td>
                    <td><span class="black-text">{{this.adminCost}}</span> €</td>
                </tr>
            </table>

            <table class="gray-text result-table">
                <tr>
                    <td>Mwst.</td>
                    <td><span class="black-text">{{vars.nds}}</span> %</td>
                </tr>
                <tr>
                    <td>Preis pro KM</td>
                    <td><span class="black-text">{{vars.adminCostsByKm}}</span> € je KM</td>
                </tr>
                <tr>
                    <td>Dieselverbrauch durchschntl.</td>
                    <td><span class="black-text">{{vars.taxOnKm}}</span> € je KM</td>
                </tr>
                <tr>
                    <td>Diesel-Preis je lt.</td>
                    <td><span class="black-text">{{vars.taxOnFuel}}</span> € за л</td>
                </tr>
                <tr>
                    <td>Lohn Busfahrer</td>
                    <td><span class="black-text">{{vars.driverSalary}}</span> € pro Tag</td>
                </tr>
            </table>
            <a class="link-text to-settings" href="/settings">Einstellung für Kalkulation ändern</a>
        </div>
    </div>
</template>

<script>
    import CustomInput from "@vue/components/UI/CustomInput.vue";
    import CustomCheckbox from "@vue/components/UI/CustomCheckbox.vue";
    import {Router} from "@vue/init.js";
    import {toUrl} from "@vue/components/helpers/objectToQuery.js";
    export default {
        name: "Calculator",
        props: ['insideDistance','outsideDistance','vars'],
        data(){
            return {
                calculated:{
                    profit: 0,
                    costWithoutNds: 0,
                    costWithNds: 0,
                },
            }
        },
        computed:{
            fuelCost:function(){
                return Math.round((this.kilometers*this.vars.taxOnFuel*this.vars.taxOnKm)*100)/100;
            },
            driverCost:function(){
                return  Math.round((this.vars.driverSalary*this.vars.days)*100)/100;
            },
            totalCost:function () {
                return  Math.round((this.fuelCost+this.driverCost+this.otherCosts)*100)/100;
            },
            adminCost:function(){
                return  Math.round((this.kilometers*this.vars.adminCostsByKm)*100)/100;
            },
            costWithNds:{
                get:function(){
                    return  Math.round((this.calculated.costWithNds)*100)/100;
                },
                set:function(cost){
                    this.calculated.costWithNds = cost;
                }
            },
            profitPercent:{
                get(){
                    return  (this.vars.profitPercent);
                },
                set(percent){
                    this.$emit("changeVars",{profitPercent:Math.round(percent)});
                    this.calc();
                }
            },
            kilometers:function(){
                return Math.round((this.insideDistance+this.outsideDistance)/100)/10+parseInt(this.vars.kmInside);
            },
            days:{
                get:function(){
                    return this.vars.days;
                },
                set:function(days){
                    this.$emit("changeVars",{days:parseInt(days === "" ? 0 : days)});
                    this.calc();
                }
            },
            otherCosts:{
                get:function(){
                  return this.vars.otherCosts;
                },
                set:function(cost){
                    this.$emit("changeVars",{otherCosts:parseFloat(cost === "" ? 0 : cost)});
                  this.calc();
                }
            },
            kmInside:{
                get:function(){
                  return this.vars.kmInside;
                },
                set:function(km){
                  km = km.toString().length === 0 ? 0 : km;
                    this.$emit("changeVars",{kmInside:parseFloat(km)});
                  this.calc();
                }
            },
            insideDistanceInKm:{
                get: function(){
                    return Math.round(this.insideDistance/100)/10;
                },
                set: function(val){
                    this.$emit("changeInsideDistance",parseFloat((val+"").trim())*1000);
                }
            }
        },
        methods:{
            calc(){
                this.calculated.profit = Math.round((((this.totalCost+this.adminCost)/(1-(this.vars.profitPercent/100)))*(this.vars.profitPercent/100))*100)/100;
                this.calculated.costWithoutNds = Math.round((this.calculated.profit+this.totalCost+this.adminCost)*100)/100;
                let NDSKilometers = this.insideDistance;
                if(this.vars.kmInsideWithNds)
                    NDSKilometers+=parseInt(this.vars.kmInside)*1000;
                this.calculated.costWithNds = Math.round((this.calculated.costWithoutNds+(NDSKilometers/1000)*(this.vars.nds/100))*100)/100;
                this.$emit("changeCost",this.calculated.costWithNds);
            },
            calcByCost(){
                this.calculated.costWithNds = parseFloat(this.calculated.costWithNds);
                let NDSKilometers = this.insideDistance;
                if(this.vars.kmInsideWithNds)
                    NDSKilometers+=parseInt(this.vars.kmInside)*1000;
                this.calculated.costWithoutNds = this.calculated.costWithNds - ((NDSKilometers/1000)*(this.vars.nds/100));
                this.calculated.costWithoutNds = Math.round(this.calculated.costWithoutNds*100)/100;
                // this.calculated.costWithoutNds = Math.round((this.calculated.costWithNds/((this.vars.nds/100)+1))*100)/100;
                this.calculated.profit = Math.round((this.calculated.costWithoutNds - this.adminCost - this.totalCost)*100)/100;
                this.$emit("changeCost",this.calculated.costWithNds);
                this.$emit("calculatedPercent",Math.round((this.calculated.profit/ this.calculated.costWithoutNds)*100));
            }
        },
        watch:{
            insideDistance:function () {
                this.calc();
            },
            outsideDistance:function () {
                this.calc();
            },
            vars: {
                handler: function (val, oldVal) {
                    this.calc();
                },
                deep: true

            }
        },
        components:{
            CustomInput,CustomCheckbox
        },
        mounted:function(){

        }
    }
</script>

<style scoped lang="scss">
    .calculator-params{
        display: flex;
        flex-wrap: wrap;
        margin-top: 25px;
        &>div{
            margin-right: 10px;
            min-width: 150px;
            max-width: 150px;
            &.large-block{
                min-width: 300px;
                max-width: 300px;
                align-items: center;
                display: flex;
            }
        }
    }
    .settings-input{
        margin-right: 25px;
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
    .result-data-costs{
        margin-bottom: 20px;
         &__row{
             display: flex;
             flex-wrap: wrap;
             align-items: center;
             &>div:first-child{
                 margin-right: 39px;
                 width: 165px;
                 min-width: 165px;
             }
         }
     }
    .result-table{
        width: 100%;
        line-height: 28px;
        font-size: 12px;
        margin-top: 20px;
        .sub-row td:first-child{
            padding-left: 25px;
        }
        tr td:first-child{
            width: 38%;
        }
        tr td:last-child{
            font-size: 14px;
        }
        &.gray-text{
            color: rgba(0, 0, 0, 0.4);
        }
    }
    .to-settings{
        margin: 12px 0;
        font-size: 12px;
    }
</style>
