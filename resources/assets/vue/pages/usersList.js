import {Vue,Router} from "@vue/init.js";
import Pagination from "@vue/components/UI/Pagination.vue";
import Notification from "@vue/components/UI/Notification.vue";

window.app = new Vue({
    el: "#app",
    data(){
        return {
            users: [],
            meta: {},
            userDetails: null,
            hideNotification: false,
        }
    },
    methods:{
        getList(){
            Router.get("listUserData").then((url)=>{
                axios.get(url).then(({data})=>{
                    this.onData(data);
                    this.hideLoader();
                })
            })
        },
        viewDetails(id){
            if(this.userDetails !== id)
                this.userDetails = id;
            else
                this.userDetails = null;
        },
        removeUser(id){
            let i = null;
            this.users.forEach((user,index)=>{
                if(user.id===id)
                    i=index;
            });
            if(i!==null){
                let confirmed = confirm("Удалить пользователя "+this.users[i].name+"?");
                if(confirmed)
                    Router.get("removeUserAction").then((url)=>{
                        axios.post(url,{id:id}).then(()=>{
                            this.users.splice(i,1);
                        })
                    })
            }
        },
        onData(data){
            this.users = data.data;
            this.meta = data.meta;
        }
    },
    mounted() {
        this.getList();
    },
    components:{
        Pagination, Notification
    },
    computed:{
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