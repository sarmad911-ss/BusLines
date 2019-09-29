<template>
    <div class="saved-notification" v-if="value">
        <i class="icon default success-icon no-hover"></i>
        <span>{{text}}</span>
        <i class="icon default remove-icon gray__theme" @click="$emit('input',false)"></i>
    </div>
</template>

<script>
    export default {
        name: "Notification",
        props: {
            value:{
                type:Boolean,
                default: false,
            },
            text:{
                default: 'Änderungen gespeichert',
            }
        },
        watch:{
            value(val){
                if(val){
                    setTimeout(()=>{
                        this.$emit("input",false);
                    },2500)
                }
            }
        }
    }
</script>

<style scoped>

</style>
