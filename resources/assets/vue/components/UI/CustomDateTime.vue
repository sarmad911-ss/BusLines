<template>
    <div class="custom-input__block">
        <div class="custom-input__label" v-if="label">
            {{label}}
            <span v-if="notRequiredText" class="gray-text">nicht unbedingt notwendig</span>
        </div>
        <div class="custom-input__group">
            <div class="custom-input__addon left" v-if="leftIcon">
                <i class="icon default" :class="leftIcon"></i>
            </div>
            <span class="icon default remove-icon" v-if="value" @click="$emit('input','')"></span>
            <datetime
                    v-model="currVal"
                    :type="type"
                    :format="format"
                    ref="picker"
            >
            </datetime>
            <span class="icon default input-arrow-icon reverse" @click="openPicker"></span>
        </div>
    </div>

</template>

<script>
    import {Datetime} from 'vue-datetime';
    import 'vue-datetime/dist/vue-datetime.css';

    export default {
        name: "CustomDateTime",
        props: {
            value:null,
            leftIcon:String,
            rightIcon:String,
            placeholder:String,
            required:{
                default:false,
            },
            label: String,
            notRequiredText:{
                default:false
            },
            noTime:{
                type:Boolean,
                default: true,
            }
        },
        components:{
            Datetime
        },
        computed:{
            currVal:{
                set: function(val){
                    if(val === null || typeof val === 'undefined' || val.length===0)
                        this.$emit("input",val);
                    else
                        if(!this.noTime) {
                            let string = "";
                            let date = new Date(val);
                            string += date.getHours().toString().length == 1 ? 0 : "";
                            string += date.getHours().toString();
                            string += ":";
                            string += date.getMinutes().toString().length == 1 ? 0 : "";
                            string += date.getMinutes().toString();
                            string += " ";
                            string += (date.getMonth() + 1).toString().length == 1 ? 0 : "";
                            string += (date.getMonth() + 1).toString();
                            string += ".";
                            string += date.getDate().toString().length == 1 ? 0 : "";
                            string += date.getDate().toString();
                            string += ".";
                            string += date.getFullYear().toString().slice(2);
                            this.$emit("input", string);
                        }
                        else{
                            let string = "";
                            let date = new Date(val);
                            string += date.getFullYear().toString();
                            string += "-";
                            string += (date.getMonth() + 1).toString().length == 1 ? 0 : "";
                            string += (date.getMonth() + 1).toString();
                            string += "-";
                            string += date.getDate().toString().length == 1 ? 0 : "";
                            string += date.getDate().toString();
                            this.$emit("input", string);
                        }
                },
                get: function(){
                    try {
                        if(typeof this.value === "undefined" || this.value === null)
                            return "";
                        return new Date(this.value).toISOString();
                    }
                    catch (e) {
                        return "";
                    }
                }
            },
            format:function(){
                return this.noTime ? "dd/MM/yyyy" : "HH:mm dd/MM/yyyy";
            },
            type:function(){
                return this.noTime ? 'date' : 'datetime';
            }
        },
        methods:{
            openPicker(){
                this.$refs.picker.$el.getElementsByClassName('vdatetime-input')[0].click();
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

    .icon{
        width:11px;
        height:11px;
        &.remove-icon{
            margin-left:10px;
        }
        &.input-arrow-icon{
            margin-right: 10px;
        }

    }

</style>