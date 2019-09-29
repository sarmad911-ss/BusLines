import {Vue,Router} from "@vue/init.js";
import Pagination from "@vue/components/UI/Pagination.vue";
import Modal from "@vue/components/UI/Modal.vue";
import TransactionForm from "@vue/components/finances/TransactionForm.vue";

window.app = new Vue({
    el: "#app",
    data:function(){
        return {
            transactions:[],
            meta: {},
            storeModal:false,
            storeTransaction: {},
        };
    },
    methods:{
        onData(data){
            this.transactions = data.data;
            this.meta = data.meta;
        },
        storeTransactionModal(transaction){
            if(transaction===null)
                transaction = {};
            this.storeTransaction = transaction;
            this.storeModal = true;
        },
        onNewTransaction(transaction){
            this.transactions.splice(0,0,transaction);
        },
        onModalClose(){
            this.transactions.forEach((transaction,index)=>{
               if(transaction.id === this.storeTransaction.id)
                   this.transactions[index] = this.storeTransaction;
            });
            this.storeTransaction = {};
            this.storeModal = false;
        },
    },
    computed:{
      modalTitle:function(){
          if(Object.keys(this.storeTransaction).length>0)
          return 'Transaktion bearbeiten';
          return 'Transaktion hinzufügen';
      }
    },
    mounted() {
        Router.get("listFinancesData").then((url)=>{
            axios.get(url).then(({data})=>{
                this.onData(data);
                this.hideLoader();
            });
        });
    },
    components:{
        Pagination, Modal, TransactionForm,
    }
});