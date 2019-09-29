<template>
    <div class="custom-input__block">
        <div class="custom-input__label" v-if="label">
            {{label}}
            <span v-if="notRequiredText" class="gray-text">nicht unbedingt notwendig</span>
        </div>
        <div class="custom-input__group" :class="{opened:opened}">
            <div class="custom-select" @click="toggle">
                <div class="custom-select__value" :class="{placeholder: noValue}">
                    <span v-html="currentValue" class="bus-data-value"></span>
                    <div class="custom-input__addon">
                        <i class="icon default input-arrow-icon reverse"></i>
                    </div>
                </div>
                <div class="custom-select__list custom-scroll" :class="{opened:opened}">
                    <div class="custom-select__item" @click="addBuss">
                        <i class="icon default plus-icon"></i> Bus hinzufügen
                    </div>
                    <div class="custom-select__item" v-for="item in allBusses" @click="select(item.id)" :class="{active:item.id==value,busy:item.busy}">
                        <div class="bus-number">{{item.plate_number}}</div><span class="elipsis">{{item.type}}/{{item.model}}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>


    export default {
        name: "CustomBusSelect",
        props: {
            value:null,
            items:{
              type:Object,
              default:{available:[],busy:[]},
            },
            placeholder:{
                type:String,
                default: "Wert auswählen"
            },
            required:{
                default:false,
            },
            label: String,
            notRequiredText:{
                default:false
            },
        },
        data:function(){
          return {
              opened:false,
          }
        },
        methods:{
            stepUp(){
                if(this.type!=="number") return false;
                this.$refs.input.stepUp();
                this.$emit('input',this.$refs.input.value);
            },
            stepDown(){
                if(this.type!=="number") return false;
                this.$refs.input.stepDown();
                this.$emit('input',this.$refs.input.value);
            },
            select(id){
                this.$emit("input",id);
                this.$emit("change",id);
            },
            toggle(){
                this.opened = !this.opened;
            },
            addBuss(){
                this.openModal("newBus",{});
            },
        },
        computed:{
            currentValue:function(){
                if( this.value !== null && typeof this.value !== 'undefined')
                {
                    let name  = null;
                    this.allBusses.forEach((item)=>{
                        if(item.id == this.value)
                        {
                            name=`<div class="bus-number">${item.plate_number}</div> <span class="elipsis">${item.type}/ ${item.model}</span>`;
                        }
                    });
                    return name;
                }
                return this.placeholder;
            },
            noValue(){
                return this.value === null || typeof this.value == 'undefined';
            },
            allBusses(){
                let buses = JSON.parse(JSON.stringify(this.items.available));
                this.items.busy.forEach((bus)=>{
                    bus.busy = true;
                    buses.push(bus);
                });
                return buses;
            }
        },
    }
</script>

<style scoped lang="scss">
    .custom-input__group{
        border-radius: 8px;
        &.opened{
            border-radius: 8px 8px 0 0;
        }
    }
    .custom-select {
        width: 100%;
        position: relative;
        cursor: pointer;
        font-family: "Inter-Regular", Helvetica, Arial, sans-serif;
        font-style: normal;
        font-weight: normal;
        font-size: 14px;
        &__list {
            z-index: 12;
            position: absolute;
            left: -1;
            right: -1;
            top: 32px;
            display: none;
            background-color: white;
            border: 1px solid rgba(35, 138, 196, 0.2);
            box-sizing: border-box;
            border-radius: 0 0 8px 8px;
            &.opened {
                display: block;
                max-height: 227px;
                overflow-y: scroll;
            }
        }
        &__value{
            padding-left: 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            &.placeholder{
                color: rgba(0,0,0,0.4);
            }
            .input-arrow-icon{
                width:8px;
                height:4px;
            }
            .bus-data-value{
                display: flex;
                align-items: center;
                width: 83%;

            }
        }
        &__item{
            height:45px;
            align-items: center;
            display: flex;
            padding: 18px;
            box-sizing: border-box;
            &:hover, &.active{
                background-color: rgba(35, 138, 196, 0.2);
            }
            &.active{
                font-family: 'Inter-SemiBold', Helvetica, Arial, sans-serif;
                font-style: normal;
                font-weight: 600;
                font-size: 14px;
                color: #238AC4;
            }
            .plus-icon{
                width:14px;
                height: 14px;
                margin-right: 9px;
            }
            &:nth-child(odd){
                background: rgba(35, 138, 196, 0.03);
                &:hover, &.active{
                    background-color: rgba(35, 138, 196, 0.2);
                }
            }
            &.busy{
                background-color: rgba(0, 0, 0, 0.05) !important;
                color: rgba(0, 0, 0, 0.4);
                .bus-number{
                    border-color: rgba(0, 0, 0, 0.4);
                    color: rgba(0, 0, 0, 0.4);
                }
            }
        }
    }
</style>