import {Vue,Router} from "@vue/init.js";
import StoreWaypointsTab from "@vue/components/trip/StoreWaypointsTab.vue";
import StoreInfoTab from "@vue/components/trip/StoreInfoTab.vue";

window.app = new Vue({
    el: "#app",
    data:function(){
        return {
            tab:'StoreWaypointsTab',
            trip:{

            },
        };
    },
    methods:{
        setStoreWaypointsTab(){
            this.tab = 'StoreWaypointsTab';
        },
        setStoreInfoTab(){
            this.tab = 'StoreInfoTab';
        },
        onCalcMap(data){
            Object.assign(this.trip,data);
        },
    },
    mounted(){
      if(typeof window.trip_id !== 'undefined'){
          Router.get("storeTripData").then((url)=>{
              axios.get(url+"?id="+window.trip_id).then(({data})=>{
                  this.trip = data.data;
                  this.hideLoader();
              })
          })
      }else{
          this.hideLoader();
      }
    },
    components:{
        StoreWaypointsTab,StoreInfoTab,
    }
});
