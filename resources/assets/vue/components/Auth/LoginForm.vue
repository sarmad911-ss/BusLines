<template>
    <form @submit.prevent="login">
        <div class="form-title">Einloggen</div>
        <div class="form-subtitle">Melden Sie sich an, um mit Reisen zu arbeiten</div>
        <custom-input
            :type="'email'"
            :placeholder="'Mail'"
            v-model="logindata.email"
            :left-icon="'username-icon'"
        ></custom-input>
        <span class="errors" v-if="errors.email">
			{{errors.email[0]}}
		</span>
        <custom-input
                :type="passwordInputType"
                :placeholder="'Passwort'"
                v-model="logindata.password"
                :left-icon="'password-icon'"
                :right-icon="'eye-icon'"
                @right-click="togglePassword"
        ></custom-input>
        <span class="errors" v-if="errors.password">
			{{errors.password[0]}}
		</span>
        <div>
            <input :class="{disabled:hideSubmitButton}" :disabled="hideSubmitButton" type="submit" value="Anmeldung" class="button">
        </div>
        <a @click="forgotPassword" class="link-text">Passwort vergessen?</a>
    </form>
</template>

<script>
    import {Router} from "@vue/init.js";
    import CustomInput from "@vue/components/UI/CustomInput.vue";

    export default {
        name: "LoginForm",
        data(){
          return {
              logindata:{},
              forgotLink:null,
              passwordInputType: 'password',
              errors:{},
              hideSubmitButton: false,
          }
        },
        methods:{
            togglePassword(){
                this.passwordInputType = this.passwordInputType==="password" ? "text" : 'password';
            },
            login(){
                this.hideSubmitButton = true;
                this.errors = {};
                Router.get("loginAction").then((url)=>{
                    axios.post(url,this.logindata).then(({data})=>{
                        if(data.redirect)
                            location.href = data.redirect;
                    }).catch(({response})=>{
                        this.hideSubmitButton = false;
                        this.errors = response.data.errors;
                    });
                })
            },
            forgotPassword(){
                this.$emit("change-form","forgot-password-form")
            }
        },
        mounted(){
            Router.get("forgotPasswordView").then((url)=>{
                this.forgotLink = url;
            })
        },
        components:{
            CustomInput
        }
    }
</script>

<style scoped>

</style>