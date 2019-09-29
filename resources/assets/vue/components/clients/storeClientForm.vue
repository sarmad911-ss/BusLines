<template>
    <form class="client-form" action="/" @submit.prevent="save">
        <div class="page-title col-12">{{formTitle}}</div>
        <div class="row">
            <custom-input
                :label="'Kunde'"
                :placeholder="'Firmen Name'"
                v-model="client.name"
                class="col-6"
                :required="true"
                :name="'name'"
            ></custom-input>
        </div>
        <div class="row">
            <custom-input
                :label="'Repräsentativer Name'"
                :placeholder="'Repräsentativer Name'"
                v-model="client.representative_name"
                class="col-5"
                :required="true"
                :name="'representative_name'"
            ></custom-input>
            <custom-input
                :label="'Repräsentativer Nachname'"
                :placeholder="'Repräsentativer Nachname'"
                v-model="client.representative_last_name"
                class="col-5"
                :required="true"
                :name="'representative_last_name'"
            ></custom-input>
        </div>
        <div class="row">
            <custom-input
                :label="'Code'"
                :placeholder="'Code'"
                v-model="client.code"
                class="col-2"
                :required="false"
                :name="'code'"
            ></custom-input>
            <custom-input
                :label="'Stadt'"
                :placeholder="'Stadt'"
                v-model="client.city"
                class="col-4"
                :required="false"
                :name="'city'"
            ></custom-input>
            <custom-input
                :label="'Adresse'"
                :placeholder="'Adresse'"
                v-model="client.address"
                class="col-6"
                :required="false"
                :name="'address'"
            ></custom-input>
        </div>
        <div class="row">
            <custom-input
                :label="'Repräsentatives Telefon'"
                :placeholder="'Repräsentatives Telefon'"
                v-model="client.phone"
                class="col-6"
                :required="false"
                :name="'phone'"
                :notRequiredText="true"
            ></custom-input>
            <custom-input
                :label="'e-mail'"
                :placeholder="'e-mail'"
                v-model="client.email"
                class="col-6"
                :notRequiredText="true"
                :required="false"
                :name="'email'"
            ></custom-input>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="custom-input__label misc-info">
                    Zusatzinformation
                    <span class="gray-text">nicht unbedingt notwendig</span>
                </div>
                <trumbowyg v-model="client.misc" id="clientMisc"></trumbowyg>
            </div>
        </div>
        <div style="display:flex; align-items: center;">
            <button class="button">Speichern</button>
            <div style="color: red; margin-left: 20px;">
                <p v-for="(error) in errors">{{error[0]}}</p>
            </div>
        </div>
    </form>
</template>

<script>
    import CustomInput from "@vue/components/UI/CustomInput";
    import Trumbowyg from "@vue/components/UI/Trumbowyg";
    import {Router} from "@vue/init.js";
    import {toFormData} from "@vue/components/helpers/objectToQuery.js";

    export default {
        name: "storeClientForm",
        components:{
            CustomInput,Trumbowyg
        },
        data(){
            return {
                client: {},
                errors: {},
            }
        },
        props: ['clientId'],
        methods:{
          save(){
              console.log(1);
            Router.get("storeClientAction").then((url)=>{
                let client = toFormData(this.client);
                axios.post(url,client).then(({data})=>{
                    this.$emit("onsave",data.data);
                }).catch(({response})=>{
                    this.errors = response.data.errors;
                })
            })
          }
        },
        computed:{
            formTitle:function(){
                return  typeof this.clientId === 'undefined' ? "Fügen Sie einen Client" : "veraendern";
            }
        },
        watch:{
            clientId:function(val){
                if(typeof val === "undefined")
                    this.client = {};
                else
                    Router.get("storeClientData").then(url=>{
                        axios.get(url+"?id="+val).then(({data})=>{
                            this.client = data.data;
                        })
                    })
            }
        }
    }
</script>

<style scoped lang="scss">

    .row{
        display:grid;
        grid-template-columns: repeat(12,1fr);
        column-gap: 25px;
    }
    .col-12 {
        grid-column: span 12;
    }
    .col-10{
        grid-column: span 10;
    }
    .col-8{
        grid-column: span 8;
    }
    .col-6{
        grid-column: span 6;
    }
    .col-5{
        grid-column: span 5;
    }
    .col-4{
        grid-column: span 4;
    }
    .col-3{
        grid-column: span 3;
    }
    .col-2{
        grid-column: span 2;
    }
    .col-1{
        grid-column: span 1;
    }

</style>
