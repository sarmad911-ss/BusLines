import {Vue,Router} from "@vue/init.js";
import Pagination from "@vue/components/UI/Pagination.vue";
import Notification from "@vue/components/UI/Notification.vue";
window.app = new Vue({
    el: "#app",
    data:function(){
        return {
            busses:[],
            meta: {},
            notification: true,
        };
    },
    methods:{
        onData(data){
            this.busses = data.data;
            this.meta = data.meta;
        }
    },
    mounted() {
        Router.get("listBusData").then((url)=>{
            axios.get(url).then(({data})=>{
                this.onData(data);
                this.hideLoader();
            });
        });
    },
    components:{
        Pagination, Notification,
    },
    computed:{
        showNotification:{
            get: function () {
                if(window.openNotification)
                    return this.notification;
                else
                    return false;
            },
            set: function(val){
                this.notification = val;
            }
        }
    }
});