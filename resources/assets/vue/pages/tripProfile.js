import {Vue,Router} from "@vue/init.js";
import {toUrl} from "@vue/components/helpers/objectToQuery.js";
import CustomSelect from "@vue/components/UI/CustomSelect.vue";
import TripInfoTab from "@vue/components/trip/TripInfoTab.vue";
import SegmentsTab from "@vue/components/trip/SegmentsTab.vue";
import FinancesTab from "@vue/components/trip/FinancesTab.vue";
import DocumentsTab from "@vue/components/trip/DocumentsTab.vue";

window.app = new Vue({
    el: "#app",
    data:function(){
        return {
            trip:{},
            tab: "TripInfoTab",

        }
    },
    methods:{
        setInfoTab(){
            this.tab = 'TripInfoTab';
        },
        setSegmentsTab(){
            this.tab = 'SegmentsTab';
        },
        setFinancesTab(){
            this.tab = 'FinancesTab';
        },
        setDocumentsTab(){
            this.tab = 'DocumentsTab';
        },
        deleteTrip(){
            let confirmed = confirm("Удалить поездку?");
            if(confirmed){
                Router.get("deleteTripAction").then((url)=>{
                    window.location.href = url+"?id="+this.trip.id;
                })
            }
        },

    },
    mounted() {
        if(window.tripId){
            Router.get("profileTripData").then((url)=>{
                let query = toUrl({id:window.tripId});
                axios.get(url+"?"+query).then(({data})=>{
                    this.trip = data.data;
                    this.hideLoader();
                });
            });
        }
    },
    components:{
        CustomSelect,TripInfoTab,SegmentsTab,FinancesTab,DocumentsTab
    }
});