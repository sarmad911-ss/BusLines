<template>
    <div class="checkbox-group" @click="toggle">
        <div class="checkbox-marker">
            <i class="icon checkbox-icon" v-if="value"></i>
        </div>
        <label>{{label}}</label>
    </div>
</template>

<script>
    export default {
        name: "CustomCheckbox",
        props: {
            value:{
                default:false,
            },
            label:{
                type: String,
            },
            disabled:{
                default: false,
            }
        },
        methods:{
            toggle(){
                if(!this.disabled){
                    this.$emit("input",!this.value);
                    this.$emit('change');
                }
            }
        }
    }
</script>

<style scoped lang="scss">
    .checkbox-group{
        font-family: 'Inter-Regular', Helvetica, Arial, sans-serif;
        font-style: normal;
        font-weight: normal;
        font-size: 14px;
        color: rgba(0, 0, 0, 0.8);
        display: flex;
        align-items: center;
        cursor: pointer;
        .checkbox-marker{
            border: 1px solid rgba(35, 138, 196, 0.2);
            box-sizing: border-box;
            border-radius: 8px;
            width:22px;
            min-width: 22px;
            height:22px;
            min-height: 22px;
            margin-right: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            .icon{
                width: 9px;
                height: 6px;
                background-color: #238AC4;
            }

        }
    }
</style>