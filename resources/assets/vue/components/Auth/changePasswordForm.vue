<template>
    <div>
        <form v-if="!notification" @submit.prevent="change">
            <div class="form-title">
                Neues Passwort
            </div>
            <div class="form-subtitle">
                Geben Sie Ihr neues Passwort ein, um sich anzumelden
            </div>
            <div class="custom-input__group">
                <div class="custom-input__addon left">
                    <i class="icon default password-icon"></i>
                </div>
                <input :type="passwordInputType" name="password" v-model="password" placeholder="Passwort">
                <div class="custom-input__addon">
                    <i class="icon default eye-icon" @click="togglePassword"></i>
                </div>
            </div>
            <p class="errors" v-if="errors && errors.password">
                {{errors.password[0]}}
            </p>
            <input :class="{disabled:hideSubmitButton}" :disabled="hideSubmitButton"  type="submit" value="Zu senden" class="button">
        </form>
        <div v-else>
            <div class="form-title">Passwort geändert</div>
            <div class="form-subtitle">Sie können sich jetzt mit einem neuen Passwort anmelden.</div>
            <a href="/login" class="button">Zurück zum Eingang</a>
        </div>
    </div>
</template>

<script>
    import {Router} from "@vue/init.js";
    export default {
        name: "changePasswordForm",
        props: ['forgotKey'],
        data(){
          return {
              password: null,
              passwordInputType: 'password',
              errors: null,
              hideSubmitButton :false,
              notification:false,
          }
        },
        methods:{
            togglePassword(){
                this.passwordInputType = this.passwordInputType==="password" ? "text" : 'password';
            },
            change(){
                this.hideSubmitButton = true;
                Router.get("changePasswordAction").then((url)=>{
                    axios.post(url,{key:this.forgotKey,password:this.password})
                        .then(({data})=>{
                            if(data.success)
                                this.notification = true;
                        })
                        .catch(({response})=>{
                            this.hideSubmitButton = false;
                            if(response.data && response.data.errors)
                                this.errors= response.data.errors;
                        })
                });
            }
        }
    }
</script>

<style scoped>

</style>