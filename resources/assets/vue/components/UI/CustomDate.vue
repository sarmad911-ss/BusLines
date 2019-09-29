<template>
    <div class="custom-input__block">
        <div class="custom-input__label" v-if="label">
            {{label}}
            <span v-if="notRequiredText" class="gray-text">не обязательно</span>
        </div>
        <div class="custom-input__group">
            <div class="custom-input__addon left" v-if="leftIcon">
                <i class="icon default" :class="leftIcon"></i>
            </div>
            <Datepicker v-model="currVal"
                        :format="'dd/MM/yyyy'"
                        :calendar-button="true"
                        :calendar-button-icon="'icon default input-arrow-icon reverse'"
                        :wrapper-class="'date-input'"
                        :placeholder="placeholder"
                        :clear-button="true"
                        :clear-button-icon="'icon default remove-icon reverse'"
            ></Datepicker>
        </div>
    </div>

</template>

<script>
    import Datepicker from 'vuejs-datepicker';

    export default {
        name: "CustomDate",
        props: {
            value:null,
            leftIcon:String,
            rightIcon:String,
            type:{
                default:'text'
            },
            min:Number,
            max:Number,
            step:Number,
            placeholder:String,
            showArrows:{
                default:true,
            },
            required:{
                default:false,
            },
            label: String,
            notRequiredText:{
                default:false
            },
        },
        components:{
            Datepicker
        },
        computed:{
            currVal:{
                set: function(val){
                    if(val instanceof Date) {
                        let date = val;
                        val = date.getFullYear() + "-" + ((date.getMonth() + 1).toString().length == 1 ? "0" + (date.getMonth() + 1) : (date.getMonth() + 1)) + "-" + (date.getDate().toString().length == 1 ? "0" + date.getDate() : date.getDate());
                    }
                    this.$emit("input",val);
                },
                get: function(){
                    return this.value;
                }
            }
        }
    }
</script>

<style scoped lang="scss">
    .custom-input__block{
        font-family: "Inter-Regular", Helvetica, Arial, sans-serif;
        font-style: normal;
        font-weight: normal;
        font-size: 14px;
    }

</style>