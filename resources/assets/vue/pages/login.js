import {Vue,Router} from "@vue/init.js";

import LoginForm from "@vue/components/Auth/LoginForm.vue";
import ForgotPasswordForm from "@vue/components/Auth/ForgotPasswordForm.vue";

window.app = new Vue({
    el: "#app",
    data(){
        return {
            component:"login-form"
        }
    },
    methods:{
        changeForm(form){
            this.component = form;
        },

    },
    mounted(){

    },
    components:{
        LoginForm,ForgotPasswordForm
    }
});