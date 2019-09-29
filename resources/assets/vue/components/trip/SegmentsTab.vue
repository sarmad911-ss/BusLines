<template>
    <div class="segments-tab">
        <div class="segments-container">
            <div>
                <div class="distance-block">
                    <div>
                        <div>
                            <span class="distance__main">{{tripData.distanceF}}</span> <span class="gray-light-text">km</span>
                        </div>
                        <div class="gray-text">
                            Gesamt-KM
                        </div>
                    </div>
                    <div>
                        <div>
                            <span class="distance">{{tripData.insideDistanceF}}</span> <span class="gray-light-text">km</span>
                        </div>
                        <div class="gray-text">
                            Innerhalb Deutschland
                        </div>
                    </div>
                    <div>
                        <div>
                            <span class="distance">{{tripData.outsideDistanceF}}</span> <span class="gray-light-text">km</span>
                        </div>
                        <div class="gray-text">
                            Im Ausland
                        </div>
                    </div>
                </div>
                <div>
                    <custom-checkbox :label="'Bus vor Ort'" v-model="tripData.bus_inside" :disabled="true"></custom-checkbox>
                </div>
            </div>
            <div>
                <div class="block-title">Sektor</div>
                <div class="routes-list">
                    <div class="route-way" v-for="(segment,index) in tripData.segments">
                        <div class="route-number">
                            <i class="icon default route-icon no-hover"></i>
                            <span class="blue-text">{{index}}</span>
                        </div>
                        <div class="route-data">
                            <div><span class="gray-text">von </span>{{segment.from}}</div>
                            <div><span class="gray-text">bis </span>{{segment.to}}</div>
                            <div>
                                <img src="/public/images/germany.png" class="germany-flag" v-if="segment.inside">
                                <img src="/public/images/notgermany.png" class="germany-flag" v-else>
                                <span class="bold-text">{{Math.round(segment.distance/1000)}}</span>
                                <span class="gray-text"> km</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="block-title span-2">Busse</div>

            <div class="bordered" v-for="(bus,index) in tripData.buses">
                <div class="bus-main-info">
                    <span class="bus-number">{{bus.plate_number}}</span>
                    <span>{{bus.type}} / {{bus.model}}</span>
                    <span class="gray-light-text"> {{bus.seats_quantity}} Orte</span>
                </div>
                <div class="inputs">
                    <div>
                        <custom-input v-model="bus.km_start" @blur="storeBusInfo(index)" :label="'AB-KM'" :placeholder="'100 000'" :type="'number'" :step="1" :min="0" :showArrows="false"></custom-input>
                        <div class="input-prefix gray-light-text">km</div>
                    </div>
                    <div>
                        <custom-input v-model="bus.km_end" @blur="storeBusInfo(index)" :label="'EB-KM'" :placeholder="'100 000'" :type="'number'" :step="1" :min="0" :showArrows="false"></custom-input>
                        <div class="input-prefix gray-light-text">km</div>
                    </div>
                    <div>
                        <custom-input v-model="bus.km_inside" @blur="storeBusInfo(index)" :label="'KM vor Ort'" :placeholder="'100 000'" :type="'number'" :step="1" :min="0" :showArrows="false"></custom-input>
                        <div class="input-prefix gray-light-text">km</div>
                    </div>
                    <div>
                        <custom-input v-model="bus.work_hours" @blur="storeBusInfo(index)" :label="'Lenk-/Arbeitsstunden'" :placeholder="'100 000'" :type="'number'" :step="1" :min="0" :showArrows="false"></custom-input>
                        <div class="input-prefix gray-light-text">h</div>
                    </div>
                </div>
                <div class="info-table">
                    <div class="info-col">
                        <div class="info-table__row">
                            <div class="gray-light-text">tatsächliche Gesamt-KM</div>
                            <div class="default-text">
                                {{bus.km_passed}}
                                <span class="gray-light-text">
                            km
                            </span>
                            </div>
                        </div>
                        <div class="info-table__row">
                            <div class="gray-light-text">Diff. kalk. KM und tats. KM</div>
                            <div class="default-text">
                                {{bus.km_diff}}
                                <span class="gray-light-text">
                            km
                            </span>
                            </div>
                        </div>
                        <div class="info-table__row">
                            <div class="gray-light-text">mehr als 500 km</div>
                            <div class="default-text">
                                {{Math.max(bus.km_inside-500,0)}}
                                <span class="gray-light-text">
                            km
                            </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    import CustomCheckbox from "@vue/components/UI/CustomCheckbox";
    import CustomInput from "@vue/components/UI/CustomInput";
    import {Router} from "@vue/init.js";
    import {toFormData} from "@vue/components/helpers/objectToQuery.js";

    export default {
        name: "SegmentsTab",
        props:{
          trip:Object,
        },
        data:function(){
            return {
                tripData : this.trip,
            }
        },
        components:{
            CustomCheckbox,CustomInput,
        },
        methods:{
            storeBusInfo(index){
                Router.get("storeTripAction").then((url)=>{
                    let trip = toFormData(this.tripData);
                    axios.post(url,trip).then(({data})=>{
                        this.tripData = data.data;
                    })
                })
            }
        }
    }
</script>

<style scoped lang="scss">
    @import "@vue/../sass/_variables.scss";
    .segments-tab{
        display: grid;
        grid-template-columns: repeat(12,1fr);
        column-gap: 25px;

        .segments-container{
            grid-column: span 10;
            display: grid;
            grid-template-columns: 50% 50%;
            column-gap: 25px;
            row-gap: 25px;
        }

        .distance-block{
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 17px;
            padding-right: 20%;
            .distance{
                font-family: $Inter-Regular;
                font-style: normal;
                font-weight: normal;
                font-size: 14px;
                line-height: 22px;
                color: $main-color;
                &__main{
                    font-family: $Inter-SemiBold;
                    font-style: normal;
                    font-weight: 600;
                    font-size: 30px;
                    color: $main-color;
                }
            }
        }

        .block-title{
            font-family: $Inter-SemiBold;
            font-style: normal;
            font-weight: 600;
            font-size: 14px;
            line-height: 17px;
            display: flex;
            align-items: center;
            color: rgba(0, 0, 0, 0.8);
            &.span-2{
                grid-column: span 2;
            }
        }

        .routes-list{
            margin-top: 21px;
            .total-distance{
                margin-left: 23px
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

        .bordered{
            border: 1px solid rgba(35, 138, 196, 0.2);
            box-sizing: border-box;
            border-radius: 8px;
            padding:26px;

            .bus-main-info{
                display: flex;
                .gray-light-text{
                    margin-left:13px;
                }
            }

            .inputs{
                display: grid;
                grid-template-columns: 43% 37%;
                column-gap: 25px;
                margin-bottom: 20px;
                margin-top: 10px;
                &>div{
                    display: flex;
                    align-items: flex-end;
                }
                .input-prefix{
                    height: 45px;
                    margin-left:10px;
                }
            }
            .info-table{
                &__row{
                    display: flex;
                    &>div:first-child{
                        min-width: 200px;
                    }
                }
            }
        }
    }
</style>