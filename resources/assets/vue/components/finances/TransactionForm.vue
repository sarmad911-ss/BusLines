<template>
    <div class="transaction-form">
        <custom-select :label="'Zweck'" :items="directions" v-model="transDirection"></custom-select>
        <custom-input :label="'Beschreibung'" v-model="transDescription" :placeholder="'Transaktionsbeschreibung'"></custom-input>
        <div class="transaction-form__row">
            <custom-date-time :label="'Transaktionsdatum'" :no-time="true" v-model="transDate" class="date-input"></custom-date-time>
            <custom-input
                    :label="'Suma'"
                    :type="'number'"
                    :min="0"
                    :step="0.01"
                    v-model="transCost"
                    class="summ-input"
                    :show-arrows="false"
            ></custom-input>
            <span class="gray-light-text">€</span>
        </div>
        <div class="file-block" v-if="transaction.id">
            <img src="/images/file.png" alt="">
            <div class="file-block__info">
                <div class="file-block__name" v-if="transaction.file">{{transaction.file.name}}</div>
                <div  v-if="transaction.file">
                    <a v-if="transaction.file.url" :href="transaction.file.url" class="link-text"><i class="icon download-icon"></i>Dokument herunterladen</a>
                </div>
                <div v-else>
                    <span class="link-text" @click="$refs.fileLoader.click()"><i class="icon upload-icon"></i>Dokument hochladen</span>
                </div>
                <div class="file-buttons-row" v-if="transaction.file">
                    <span class="gray-light-text" @click="$refs.fileLoader.click()">
                        <i class="icon replace-icon"></i> Ändern
                    </span>
                    <span class="gray-light-text" @click="removeFile">
                        <i class="icon delete-icon"></i> Löschen
                    </span>
                </div>
            </div>
        </div>
        <div>
            <button class="button" @click="save" v-if="value.id">Speichern</button>
            <button class="button" @click="save" v-else>HInzufügen</button>
        </div>
        <input type="file" hidden ref="fileLoader" @change="addFile">
    </div>
</template>

<script>
    import CustomSelect from "@vue/components/UI/CustomSelect.vue";
    import CustomInput from "@vue/components/UI/CustomInput.vue";
    import CustomDateTime from "@vue/components/UI/CustomDateTime.vue";
    import {Router} from "@vue/init.js";
    import {toFormData} from "@vue/components/helpers/objectToQuery.js";

    export default {
        name: "TransactionForm",
        props: {
            value:null,
        },
        data:function(){
          return {
              transaction:this.value,
              directions:[],
          }
        },
        methods:{
            save(){
                let id = this.transaction.id;
                Router.get("storeFinancesAction").then((url)=>{
                    if(isNaN(this.transaction.cost))
                        this.transaction.cost = 0;
                    axios.post(url,toFormData(this.transaction)).then(({data})=>{
                        this.transaction = data.data;
                        if(typeof id !== "undefined" && id !== null)
                            this.$emit("input",this.transaction);
                        else
                            this.$emit("onnewtransaction",this.transaction);
                        this.$emit("onsave");
                    })
                });
            },
            removeFile(){
                this.transaction.file = undefined;
                this.transaction.file_id = undefined;
            },
            addFile(e){
                if(e.target.files.length){
                    this.$set(this.transaction,"file",{file:e.target.files[0],name:e.target.files[0].name});
                    this.transaction.file_id = undefined;
                }
            },
        },
        watch:{
            value:function(transaction){
                this.transaction = transaction;
            }
        },
        mounted:function () {
            Router.get("listPurposeData").then((url)=>{
                axios.get(url).then(({data})=>{
                    this.directions = data.data;
                })
            });
        },
        computed:{
            transDescription:{
                get: function(){
                    return this.transaction.misc ? this.transaction.misc : "";
                },
                set: function(value){
                    this.$set(this.transaction,'misc',value);
                }
            },
            transDirection:{
                get: function(){
                    return this.transaction.purpose_id;
                },
                set: function(value){
                    this.$set(this.transaction,'purpose_id',value);
                }
            },
            transDate:{
                get: function(){
                    return this.transaction.date ? this.transaction.date : "";
                },
                set: function(value){
                    this.$set(this.transaction,'date',value);
                }
            },
            transCost:{
                get: function(){
                    return this.transaction.cost ? this.transaction.cost : "";
                },
                set: function(value){
                    this.$set(this.transaction,'cost',value);
                }
            }
        },
        components: {
            CustomSelect, CustomInput, CustomDateTime,
        }
    }
</script>

<style scoped lang="scss">
    .transaction-form{
        min-width:383px;
        &__row{
            display: flex;
            align-items: flex-end;
            .date-input{
                width: 181px;
                min-width: 181px;
                margin-right: 25px;
            }
            .summ-input{
                width:78px;
                min-width: 78px;
                margin-right: 10px;
            }
            .gray-light-text{
                height: 45px;
            }
        }
        .file-block{
            border: 1px solid rgba(35, 138, 196, 0.2);
            box-sizing: border-box;
            border-radius: 8px;
            padding: 14px 25px 11px 25px;
            display:flex;
            .icon{
                width:20px;
                height: 20px;
                margin-right: 6px;
            }
            &__name{
                margin-bottom: 13px;
            }
            img{
                margin-right: 35px;
            }
        }
        .file-buttons-row{
            display:flex;
            margin-top: 10px;
            .gray-light-text{
                display:flex;
                &:first-child{
                    margin-right:10px;
                }
            }
        }
        .button{
            margin-bottom: 0;
        }
    }

</style>
