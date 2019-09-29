<template>
    <div>
        <form @submit.prevent="forgot" v-if="!notification">
            <div class="form-title">Passwort Wiederherstellung</div>
            <div class="form-subtitle">Wir senden Ihnen einen Link zum Zurücksetzen Ihres Passworts per E-Mail.</div>
            <div class="custom-input__group">
                <div class="custom-input__addon left">
                    <i class="icon default username-icon"></i>
                </div>
                <input type="email" v-model="email" placeholder="Mail">
            </div>
            <p v-if="this.errors && this.errors.email">
                {{this.errors.email[0]}}
            </p>
            <div>
                <input :class="{disabled:hideSubmitButton}" :disabled="hideSubmitButton" type="submit" value="Versenden" class="button">
            </div>
            <a @click="toLogin" class="link-text">Zurück zum Eingang</a>
        </form>
        <div v-else>
            <div class="form-title">E-Mail gesendet</div>
            <div class="form-subtitle">Wir haben einen Brief gesendet, um Ihr Passwort an Ihre E-Mail-Adresse zurückzusetzen.</div>
            <button @click="toLogin" class="button">Zurück zum Eingang</button>
        </div>
    </div>

</template>

<script>
    import {Router} from "@vue/init.js";
    export default {
        name: "ForgotPasswordForm",
        data(){
            return {
                email: null,
                notification: null,
                errors: null,
                hideSubmitButton:false,
            }
        },
        methods: {
            forgot(){
                this.hideSubmitButton = true;
                this.errors = null;
                Router.get("forgotPasswordAction").then((url)=>{
                    axios.post(url,{email:this.email}).then(({data})=>{
                        this.hideSubmitButton = false;
                        if(data.notification)
                            this.notification = data.notification;
                    }).catch(({response})=>{
                        this.hideSubmitButton = false;
                        if(response.data.errors)
                            this.errors = response.data.errors;
                    })
                });
            },
            toLogin(){
                this.$emit("change-form","login-form")
            }
        }
    }
</script>

<style scoped>

</style>