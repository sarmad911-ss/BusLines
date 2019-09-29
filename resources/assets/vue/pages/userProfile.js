import {Vue,Router} from "@vue/init.js";
import Trumbowyg from "@vue/components/UI/Trumbowyg.vue";
import CustomInput from "@vue/components/UI/CustomInput.vue";
import CustomDateTime from "@vue/components/UI/CustomDateTime.vue";

window.app = new Vue({
    el: "#app",
    data(){
        return {
            user: {
                id:this.id,
                photo_url:null,
                name:null,
                email:null,
                password:null,
                dob:null,
                misc:null,
                photo:null,
                phone:null,
                representatives:[],
            },
            roles:[],
            errors:null,
            roleWithRepresentatives : 3,
        }
    },
    methods:{
        getFile(event){
            if (event.target.files[0]) {
                this.user.photo = event.target.files[0];
                this.getBase64(event.target.files[0]).then(
                    image => this.user.photo_url = image
                );
            }
        },
        getBase64(file) {
            return new Promise((resolve, reject) => {
                const reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onload = () => resolve(reader.result);
                reader.onerror = error => reject(error);
            });
        },
        toggleEditMode() {
            this.editMode = !this.editMode;
        },
        save(){
            if(this.errors)
                if(Object.keys(this.errors).length==0) this.errors = null;
            if(this.errors)
                return false;
            let data = new FormData();
            Object.keys(this.user).forEach((key)=>{
                if(typeof this.user[key]!=="undefined" && this.user[key]!==null && key!=="photo_url" && key!=="representatives")
                    data.append(key,this.user[key]);
            });
            Router.get("storeUserAction").then((url)=>{
                axios.post(url,data).then(({data})=>{
                    this.user = data.data;
                    Router.get("listUsersView").then((url)=>{
                        location.href = url;
                    })
                }).catch(({response})=>{
                    if(response.data.errors)
                        this.errors = response.data().errors;
                });
            })
        },
        checkEmail(){
            if(this.errors) {
                this.errors.email = null;
                delete this.errors.email;
            }
            Router.get("checkEmailAction").then((url)=>{
                axios.post(url,this.user).then(({data})=>{
                    if(data.data.errors)
                        this.errors = data.data.errors;
                })
            })
        },
        callPhotoInput(){
            this.$refs.loaderInput.click();
        }
    },
    mounted(){
        if(user_id){
            Router.get("singleUserData").then((url)=>{
                axios.get(`${url}?id=${user_id}`).then(({data})=>{
                    this.user = data.data;
                    this.hideLoader();
                })
            })
        }else{
            this.hideLoader();
        }
        Router.get("getUserRolesData").then((url)=>{
            axios.get(url).then(({data})=>{
                this.roles = data.data;
            })
        })
    },
    computed:{
        passwordRequired(){
            return typeof this.user.id == "undefined";
        },
        pageTitle(){
            return typeof this.user.id == "undefined" ? "Benutzer hinzufügen" : "Benutzer bearbeiten";
        }
    },
    components:{
        Trumbowyg, CustomInput, CustomDateTime,
    },
});