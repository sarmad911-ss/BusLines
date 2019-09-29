<template>
    <div class="modal__container" :class="{opened:modalOpened}" @click="close">
        <div class="modal__block">
            <div class="modal__head">
                <div class="modal__close"><i class="icon remove-icon" @click="close"></i></div>
                <div class="modal__title">{{title}}</div>
            </div>
            <div class="modal__body custom-scroll">
                <slot></slot>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Modal",
        props: {
            component: null,
            opened: false,
            title:"",
        },
        data:function(){
          return {
              modalOpened: this.opened,
          }
        },
        methods:{
            open(){
                this.modalOpened = true;
                document.getElementsByTagName("body")[0].classList.add("noscroll");
            },
            close(e){
                if(e.target.classList.contains("remove-icon") || e.target.classList.contains("modal__container")){
                    this.hide();
                }
            },
            show(params){
                this.open();
            },
            hide(){
                this.modalOpened = false;
                this.$emit("onclose");
                document.getElementsByTagName("body")[0].classList.remove("noscroll");
            }
        },
        watch:{
            opened(val){
                this.modalOpened = val;
            }
        }
    }
</script>

<style scoped lang="scss">
    .modal{
        &__container{
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            right: 0;
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 100;
            &.opened{
                display:flex;
            }
            background-color: rgba(35,138,196,0.3);
        }
        &__block{
            min-height: 200px;
            min-width: 300px;
            background: #FFFFFF;
            box-shadow: 0px 0px 4px rgba(0, 0, 0, 0.01), 0px 0px 25px rgba(0, 0, 0, 0.01);
            border-radius: 8px;
            padding: 19px 17.5px 20px 32px;
            box-sizing: border-box;

            .remove-icon{
                width: 14.5px;
                height: 14.5px;
            }
        }
        &__body{
            max-height: 500px;
            max-width: 800px;
            overflow-y: scroll;
            padding-right: 20px;
        }
        &__close{
            display: flex;
            justify-content: flex-end;
        }
        &__title{
            font-family: 'Inter-SemiBold', Helvetica, Arial, sans-serif;
            font-style: normal;
            font-weight: 600;
            font-size: 14px;
            line-height: 22px;
            color: rgba(0, 0, 0, 0.8);
        }
    }
</style>