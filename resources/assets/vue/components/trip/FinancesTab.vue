<template>
    <div class="finances-tab">
        <div class="cost-block">
            <div>
                <span class="cost-with-nds">{{tripData.cost}}</span><span class="gray-light-text">€</span>
            </div>
            <div class="gray-text">
                Preis inkl. MwSt.
            </div>
        </div>
        <div class="finance-row">
            <div class="input-with-prefix">
                <custom-input @blur="storeBusInfo" :min="0" :label="'Maut'" :step="0.01" v-model="tripData.maut.value" :placeholder="'0'"></custom-input>
                <span class="input-prefix">€</span>
            </div>
            <custom-checkbox @change="storeBusInfo" v-model="tripData.maut.client_pay" :label="'Kunde/Auftraggeber übernimmt die Kosten'"></custom-checkbox>
            <custom-checkbox @change="storeBusInfo" v-model="tripData.maut.document_display" :label="'Im Dokument anzeigen'"></custom-checkbox>
        </div>
        <div class="finance-row">
            <div class="input-with-prefix">
                <custom-input @blur="storeBusInfo" v-model="tripData.parken.value" :min="0" :label="'Parken'" :step="0.01" :placeholder="'0'"></custom-input>
                <span class="input-prefix">€</span>
            </div>
            <custom-checkbox @change="storeBusInfo" v-model="tripData.parken.client_pay" :label="'Kunde/Auftraggeber übernimmt die Kosten'"></custom-checkbox>
            <custom-checkbox @change="storeBusInfo" v-model="tripData.parken.document_display" :label="'Im Dokument anzeigen'"></custom-checkbox>
        </div>
        <div class="finance-row">
            <div class="input-with-prefix">
                <custom-input @blur="storeBusInfo" v-model="tripData.spesen.value" :min="0" :label="'Spesen'" :step="0.01" :placeholder="'0'"></custom-input>
                <span class="input-prefix">€</span>
            </div>
            <custom-checkbox @change="storeBusInfo" v-model="tripData.spesen.client_pay" :label="'Kunde/Auftraggeber übernimmt die Kosten'"></custom-checkbox>
            <custom-checkbox @change="storeBusInfo" v-model="tripData.spesen.document_display" :label="'Im Dokument anzeigen'"></custom-checkbox>
        </div>
        <div class="table-title">
            <span>Fahrt-Transaktionen</span>
            <span class="link-text"  @click="storeTransactionModal(null)">
                <i class="icon plus-icon"></i>
                Transaktion hinzufügen
            </span>
        </div>
        <div class="custom-table">
            <div class="custom-table__head gry-light-text">
                <div>№</div>
                <div>Übertragen</div>
                <div>Summe</div>
                <div>Datum der Transaktion</div>
                <div>Beschreibung</div>
                <div>Dokument</div>
                <div>letzte Änderungen</div>
            </div>
            <div class="custom-table__body">
                <div class="custom-table__row" v-for="transaction in tripData.transactions"  @click="storeTransactionModal(transaction)">
                    <div class="font-12">{{transaction.id}}</div>
                    <div v-if="transaction.purpose">{{transaction.purpose.name}}</div>
                    <div>{{transaction.cost}} <span class="gray-light-text">€</span></div>
                    <div>12:30 12.03.19</div>
                    <div class="gray-text font-12">{{transaction.misc}}</div>
                    <div class="link-text font-12 doc-block" v-if="transaction.file"><i class="icon empty-file-icon"></i>{{transaction.file.name}}</div>
                    <div class="gray-light-text font-12 doc-block" v-else><i class="icon empty-file-icon"></i>Ohne Dokument</div>
                    <div class="gray-text font-12" v-if="transaction.last_update">
                        <span v-if="transaction.last_update.user">{{transaction.last_update.user.name}}<br></span>
                        {{transaction.last_update.time}}
                    </div>
                </div>
            </div>
        </div>
        <modal v-bind:opened="storeModal" :title="modalTitle" @onclose="onModalClose">
            <transaction-form v-model="storeTransaction" @onsave="onModalClose" @onnewtransaction="onNewTransaction"></transaction-form>
        </modal>
    </div>

</template>

<script>
    import CustomInput from "@vue/components/UI/CustomInput.vue";
    import CustomCheckbox from "@vue/components/UI/CustomCheckbox.vue";
    import {Router} from "@vue/init.js";
    import {toFormData} from "@vue/components/helpers/objectToQuery.js";
    import Modal from "@vue/components/UI/Modal.vue";
    import TransactionForm from "@vue/components/finances/TransactionForm.vue";

    export default {
        name: "FinancesTab",
        props:{
            trip:Object,
        },
        components:{
            CustomInput,CustomCheckbox, Modal, TransactionForm,
        },
        data:function(){
            return {
                tripData : this.trip,
                storeTransaction : {},
                storeModal : false,
            }
        },
        methods:{
            storeBusInfo(){
                Router.get("storeTripAction").then((url)=>{
                    let trip = toFormData(this.tripData);
                    axios.post(url,trip).then(({data})=>{
                        this.tripData = data.data;
                    })
                })
            },
            storeTransactionModal(transaction){
                if(transaction===null)
                    transaction = {trip_id:this.tripData.id};
                this.storeTransaction = transaction;
                this.storeModal = true;
            },
            onNewTransaction(transaction){
                this.tripData.transactions.splice(0,0,transaction);
            },
            onModalClose(){
                this.storeTransaction = {};
                this.storeModal = false;
            },
        },
        computed:{
            modalTitle:function(){
                if(Object.keys(this.storeTransaction).length>1)
                    return 'Transaktion bearbeiten';
                return 'Transaktion hinzufügen';
            }
        }
    }
</script>

<style scoped lang="scss">
    @import "@vue/../sass/_variables.scss";

    .cost-block{
        margin-bottom: 25px;
    }
    .cost-with-nds{
        font-family: $Inter-SemiBold;
        font-style: normal;
        font-weight: 600;
        font-size: 30px;
        line-height: 22px;
        color: $main-color;
    }
    .finance-row{
        display: grid;
        grid-template-columns: 170px  200px 300px;
        align-items: flex-end;
        .input-with-prefix{
            display: flex;
            align-items: flex-end;
            margin-right: 50px;
            .input-prefix{
                height: 45px;
                margin-left:10px;
            }
        }
        .checkbox-group{
            height: 68px;
        }
    }
    .table-title{
        display: flex;
        justify-content: space-between;
        margin-top: 25px;
        margin-bottom: 10px;
        .plus-icon{
            width: 14px;
            height: 14px;
        }
    }
    .empty-file-icon{
        width: 12px;
        min-width: 12px;
        height: 15px;
        min-height: 15px;
        margin-right: 8px;
    }
    .custom-table{
        &__head, &__row{
            display: grid;
            grid-template-columns: 10% 15% 7% 12% 24% 19% 13%;
            word-break: break-word;
        }
        .doc-block{
            display: flex;
            align-items: center;
        }
    }
</style>