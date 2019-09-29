import {Vue,Router} from "@vue/init.js";
import BusDocument from "@vue/components/bus-depot/BusDocument.vue";
import CustomDateTime from "@vue/components/UI/CustomDateTime.vue";
import {toFormData} from "@vue/components/helpers/objectToQuery.js";

window.app = new Vue({
    el: "#app",
    data: function(){
        return {
            bus: null,
            tab: "info",
        }
    },
    mounted:function(){
        if(window.busId)
        {
            Router.get("profileBusData").then((url)=>{
                axios.get(url+"?id="+window.busId).then(({data})=>{
                    this.bus = data.data;
                    this.hideLoader();
                })
            })
        }
    },
    methods:{
        setTabInfo(){
          this.tab = "info";
        },
        setTabDates(){
          this.tab = "dates";
        },
        setTabDocs(){
          this.tab = "docs";
        },
        deleteBus(){
          let confirmed = confirm("Удалить автобус?");
          if(confirmed){
              Router.get("deleteBusAction").then((url)=>{
                  window.location.href = url+"?id="+this.bus.id;
              })
          }
        },
        storeBus(){
          Router.get("storeBusAction").then((url)=>{
              let data = toFormData(this.bus);
              axios.post(url,data).then(({data})=>{
                  this.bus = data.data;
              })
          })
        },
        deleteFile(id){
            this.bus.files.forEach((file,index)=>{
                if(file.id==id) {
                    this.bus.files.splice(index, 1);
                    return false;
                }
            })
        },
        newFile(file){
            this.bus.files.push(file);
        }
    },
    computed:{
        formattedReleaseDate(){
            if(this.bus)
                return this.bus.release_date.split("-")[0];
            else
                return "";
        },
        lastUpdate(){
            let string ="";
            if(this.bus && this.bus.last_update){
                if(this.bus.last_update.user)
                    string+=this.bus.last_update.user.name;
                if(this.bus.last_update.time)
                    string+=this.bus.last_update.time;
            }
            return string;
        }
    },
    components:{
        BusDocument, CustomDateTime
    }
});