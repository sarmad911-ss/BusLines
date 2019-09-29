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
            <input
                    :type="type"
                    v-model="currValue"
                    :step="step"
                    :min="min"
                    :max="max"
                    :placeholder="placeholder"
                    ref="input"
                    :maxlength="maxLength"
                    @blur="$emit('blur')"
                    :name="name"
                    :required="required"
                    :readonly="readonly"
            >
            <div class="custom-input__addon" v-if="this.rightIcon">
                <i class="icon default" :class="rightIcon" @click="$emit('right-click')"></i>
            </div>
            <div v-if="type==='number' && showArrows" class="custom-input__addon right default arrows">
                <div @click="stepUp">
                    <i class="icon default input-arrow-icon"></i>
                </div>
                <div @click="stepDown">
                    <i class="icon default input-arrow-icon reverse"></i>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
    export default {
        name: "CustomInput",
        props: {
            value:null,
            leftIcon:String,
            rightIcon:String,
            type:{
                default:'text'
            },
            min:{type:Number,default:0},
            max:Number,
            step:{
                type: Number,
            },
            placeholder:String,
            showArrows:{
                default:true,
            },
            required:{
                default:false,
            },
            readonly:{
                default:false,
            },
            label: String,
            notRequiredText:{
                default:false
            },
            maxLength:{
                default:200,
                type: Number,
            },
            name:{
                type:String,
                default:'',
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
            validate(value){
                if(this.type=='number'){
                    if(this.step){
                        if(this.step % 1 === 0)
                            value = parseInt(value);
                        else
                            value = parseFloat(value);
                    }
                    if(typeof this.min !== 'undefined')
                        value = Math.max(value,this.min);
                    if(typeof this.max !== 'undefined')
                        value = Math.min(value,this.max);
                }
                return value;
            }
        },
        computed:{
            currValue:{
                get(){
                    if(this.type === "number" && (this.value === null || typeof this.value === "undefined" || isNaN(this.value)))
                        return 0.00;
                    return this.value;
                },
                set(val){
                    val = this.validate(val);
                    this.$emit('input',val);
                    this.$emit('change',val);
                }
            },
        }
    }
</script>

<style scoped lang="scss">
    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    input[type=date]::-webkit-inner-spin-button,
    input[type=date]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    input{
        -moz-appearance: textfield;
    }
</style>
