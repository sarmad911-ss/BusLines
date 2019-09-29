import {Vue,Router} from "@vue/init.js";
import Pagination from "@vue/components/UI/Pagination.vue";

window.app = new Vue({
    el: "#app",
    data:function(){
        return {
            calculations:[],
            meta: {},
        };
    },
    methods:{
        onData(data){
            this.calculations = data.data;
            this.meta = data.meta;
        },
        calcDistance(calculation){
            return Math.round(parseInt(calculation.inside_distance)+parseInt(calculation.outside_distance)/10)/100
        }
    },
    mounted() {
        Router.get("listCalculationsData").then((url)=>{
            axios.get(url).then(({data})=>{
                this.onData(data);
                this.hideLoader();
            });
        });
    },
    components:{
        Pagination,
    },
});
