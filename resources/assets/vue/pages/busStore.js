import {Vue,Router} from "@vue/init.js";
import BusForm from "@vue/components/bus-depot/BusForm.vue";

window.app = new Vue({
    el: "#app",
    data:function(){
        return {
        }
    },
    methods:{
      onSave(bus){
          Router.get("depotView").then((url)=>{
              location.href = url;
          })
      }
    },
    components:{
        BusForm
    }
});