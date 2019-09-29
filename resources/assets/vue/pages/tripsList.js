import {Vue,Router} from "@vue/init.js";
import Pagination from "@vue/components/UI/Pagination.vue";
import CustomDateTime from "@vue/components/UI/CustomDateTime.vue";
import CustomSelect from "@vue/components/UI/CustomSelect.vue";
import CustomInput from "@vue/components/UI/CustomInput.vue";
import {toUrl} from "@vue/components/helpers/objectToQuery.js";
import Notification from "@vue/components/UI/Notification.vue";

window.app = new Vue({
    el: "#app",
    data:function(){
        return {
            trips:[],
            meta: {},
            statuses:[],
            test:1,
            searchData:{
                start_date: null,
                status_id: null,
                search: null,
            },
            sort:{
                order_key: 'end_date',
                order_value: 'ASC'
            },
            hideNotification: false,
            end_of_data: false,
        };
    },
    methods:{
        addDay(){
            var date = new Date(this.searchData.start_date);
            date.setDate(date.getDate()+1);
            this.searchData.start_date = date.toISOString().slice(0,date.toISOString().indexOf("T"));
        },
        minDay(){
            var date = new Date(this.searchData.start_date);
            date.setDate(date.getDate()-1);
            this.searchData.start_date = date.toISOString().slice(0,date.toISOString().indexOf("T"));
        },
        onData(data){
            this.end_of_data = data.data.length === 0;
            this.trips = data.data;
            this.meta = data.meta;
            this.hideLoader();
        },
        getNumbersBus(trip){
            let numbers = [];
            let additional = 0;
            if(trip.buses !== null && typeof trip.buses !== "undefined") {
                additional = Math.max(trip.buses.length - 2, 0);
                trip.buses.map((bus) => {
                    numbers.push(bus.plate_number);
                });
            }
            if(numbers.length>2)
                numbers.splice(2,numbers.length);
            return {
                numbers: numbers,
                additional: additional,
            }
        },
        async search(){
            this.showLoader();
            Router.get("listTripData").then((url) => {
                setTimeout(()=>{
                    let params = toUrl(Object.assign(this.searchData, this.sort));
                    axios.get(url + "?" + params).then(({data}) => {
                        this.onData(data);
                        this.loading = false;
                        this.hideLoader();
                        $(".custom-table__body").scrollTop(0);
                    });
                },500);
            });
        },
        sortBy(key){
            if(this.sort.order_key===key)
                this.sort.order_value = this.sort.order_value === "DESC" ? "ASC" : "DESC";
            else
                this.sort.order_value = "DESC";
            this.sort.order_key=key;
            this.search();
        },
        isActive(key,order){
            return this.sort.order_key === key && this.sort.order_value === order;
        },
        scroll(e){
            if(e.target.scrollHeight - e.target.clientHeight - e.target.scrollTop < (e.target.scrollHeight/2) && !this.end_of_data) {
                this.end_of_data = true;
                Router.get("listTripData").then((url) => {
                    let params = JSON.parse(JSON.stringify(Object.assign(this.searchData, this.sort)));
                    let date = new Date(params.start_date);
                    date.setMonth(date.getMonth() + 1);
                    params.start_date = date.toISOString().slice(0, date.toISOString().indexOf("T"));
                    axios.get(url + "?" + toUrl(params)).then(({data}) => {
                        if(data.data.length > 0) {
                            this.end_of_data = true;
                            this.trips.append(...data.data);
                        }
                    });
                });
            }
        }
    },
    mounted() {
        this.showLoader();
        let date = new Date();
        date.setDate(date.getDate()-1);
        date.setMinutes(date.getMinutes()-date.getTimezoneOffset());
        this.searchData.start_date = date.toISOString().slice(0,date.toISOString().indexOf("T"));
        this.search();
        Router.get("listTripStatusData").then((url)=>{
            axios.get(url).then(({data})=>{
                this.statuses = data.data;
                this.statuses.splice(0,0,{id:null,name:"Alle"});
            });
        })
    },
    watch:{
        searchData: {
            deep: true,
            handler: function(){
                this.search();
            },
        }
    },
    components:{
        Pagination, CustomInput, CustomDateTime, CustomSelect, Notification,
    },
    computed:{
        paginationParams:function(){
            return Object.assign(Object.assign({},this.searchData),this.sort);
        },
        showNotification:{
            get: function () {
                if(window.openNotification)
                    return !this.hideNotification;
                else
                    return false;
            },
            set: function(val){
                this.hideNotification = !val;
            }
        }
    }
});
