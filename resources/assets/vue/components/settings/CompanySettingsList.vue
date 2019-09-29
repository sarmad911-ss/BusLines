<template>
    <div>
        <div class="logo-row">
            <div class="logo-image">
                <img :src="logo.value" alt="" >
            </div>
            <div class="logo-links">
                <div class="link-text" @click="clickLogoInput">
                    <i class="icon default marker-icon"></i> ein weiteres Logo
                </div>
                <div class="link-text gray-text" v-if="logo.value" @click="logo=''">
                    <i class="icon default marker-icon"></i> Logo löschen
                </div>
            </div>
            <input type="file" accept="image/*" @change="getFile" ref="logoInput">
        </div>
        <div class="grid-row">
            <custom-input class="col__4"
                  :label="'Firmen Name'"
                  :type="'text'"
                  v-model="companyName.value"
                  @blur="save"
            ></custom-input>
            <custom-input class="col__6"
                  :label="'Bank Name'"
                  :type="'text'"
                  v-model="bank_name.value"
                  @blur="save"
            ></custom-input>
        </div>
        <div class="grid-row">
            <custom-input class="col__4"
                  :label="'Stadt'"
                  :type="'text'"
                  v-model="city.value"
                  @blur="save"
            ></custom-input>
            <custom-input class="col__6"
                  :label="'Straße'"
                  :type="'text'"
                  v-model="street.value"
                  @blur="save"
            ></custom-input>
        </div>
        <div class="grid-row">
            <custom-input class="col__4"
                  :label="'E-Mail-Adresse'"
                  :type="'email'"
                  v-model="email.value"
                  @blur="save"
            ></custom-input>
            <custom-input class="col__6"
                  :label="'homepage'"
                  :type="'url'"
                  v-model="site.value"
                  @blur="save"
            ></custom-input>
        </div>
        <div class="mobile-row">
            <custom-input
                  :label="'Fax-Nummer'"
                  :type="'text'"
                  v-model="fax.value"
                  @blur="save"
            ></custom-input>
            <custom-input
                  :label="'Telefon-Nummer'"
                  :type="'text'"
                  v-model="phone.value"
                  @blur="save"
            ></custom-input>
            <custom-input
                  :label="'Handy-Nummer'"
                  :type="'text'"
                  v-model="mobile.value"
                  @blur="save"
            ></custom-input>
        </div>

        <div class="grid-row">
            <custom-input class="col__4"
                          :label="'IBAN'"
                          :type="'text'"
                          v-model="iban.value"
                          @blur="save"
            ></custom-input>
            <custom-input class="col__4"
                          :label="'BIC'"
                          :type="'text'"
                          v-model="bic.value"
                          @blur="save"
            ></custom-input>
        </div>

        <div class="grid-row">
            <div class="custom-input__block col__4">
                <div data-v-6d8af3ba="" class="custom-input__label">
                    Адрес депо</div>
                <div class="custom-input__group">
                    <div class="custom-input__addon left">
                        <i class="icon default marker-icon"></i>
                    </div>
                    <input type="text" ref="depoAddress" :value="depo.location">
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {Router} from "@vue/init.js";
    import CustomInput from "@vue/components/UI/CustomInput.vue";
    import gmapsInit from '@vue/components/roadmap/gmapInit.js';

    export default {
        name: "CompanySettingsList",
        data(){
            return {
                settingsNames : ['logo','company_name','address','phone','fax','mobile','email','site','bic','iban','bank_name','city','street','depo'],
                settings: [],
                saved: false,
                google: null,
            }
        },
        methods:{
            clickLogoInput(){
                console.log(this.$refs);
              this.$refs.logoInput.click();
            },
            getSettings(key){
                let value = null;
                this.settings.forEach((setting)=>{
                    if(setting.key===key)
                        value = setting;
                });

                if(value === null){
                    let setting = {
                        key:key,
                        value: "",
                    };
                    this.settings.push(setting);
                    value = setting;
                }
                return value;
            },
            setSetting(key,value){
                let index= null;
                this.settings.forEach((setting,i)=>{
                    if(setting.key===key) {
                        setting.value = value;
                        index = i;
                    }
                });
                if(index===null){
                    this.settings.push({
                        key:key,
                        value: value,
                    });
                }
            },
            save(){
                let data = new FormData();
                this.settings.forEach((setting,index)=>{
                    data.append(`items[${index}][key]`,setting['key']);
                    data.append(`items[${index}][value]`,setting['value']);
                });
                Router.get("storeSettingsAction").then((url)=>{
                    axios.post(url,data).then(({data})=>{
                        this.settings = data.data;
                        this.saved = true;
                        setTimeout(()=>{
                            this.saved = false;
                        },3000)
                    });
                })
            },
            getFile(event){
                if (event.target.files[0]) {
                    let logo = event.target.files[0];
                    this.getBase64(event.target.files[0]).then(
                        image => this.logo = image
                    );
                    Router.get("uploadLogoAction").then((url)=>{
                        let formdata = new FormData();
                        formdata.append("logo",logo);
                        axios.post(url,formdata).then(({data})=>{
                            if(data.data.url)
                                this.logo = data.data.url;
                        });
                    })
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
        },
        async mounted(){
            Router.get("listSettingsData").then((url)=>{
                let items = [];
                this.settingsNames.forEach(function(item){
                    items.push("items[]="+item);
                });
                items = items.join("&");
                axios.get(`${url}?${items}`,{'items':this.settingsNames}).then(({data})=>{
                    this.settings = data.data;
                    this.hideLoader();
                })
            });
            console.log(this.$refs.depoAddress);
            this.google = await gmapsInit();
            let startAutocomplete = new google.maps.places.Autocomplete(this.$refs.depoAddress, this.options);
            startAutocomplete.addListener('place_changed', () => {
                var place = startAutocomplete.getPlace();
                this.depo = {location:place.formatted_address,name:place.name};
            });
        },
        computed:{
            logo:{
                set(value){
                    this.setSetting("logo",value);
                    this.save();
                },
                get(){
                    return this.getSettings("logo");
                }
            },
            depo:{
                set(value){
                    this.setSetting("depo",JSON.stringify(value));
                    this.save();
                },
                get(){
                    let depo = this.getSettings("depo").value;
                    try {
                        return JSON.parse(depo)
                    }catch (e) {
                        return {name:"",location:""};
                    }
                }
            },
            companyName:{
                set(value){
                    this.setSetting("company_name",value);
                },
                get(){
                    return this.getSettings("company_name");
                }
            },
            city:{
                set(value){
                    this.setSetting("city",value);
                },
                get(){
                    return this.getSettings("city");
                }
            },
            bank_name:{
                set(value){
                    this.setSetting("bank_name",value);
                },
                get(){
                    return this.getSettings("bank_name");
                }
            },
            street:{
                set(value){
                    this.setSetting("street",value);
                },
                get(){
                    return this.getSettings("street");
                }
            },
            phone:{
                set(value){
                    this.setSetting("phone",value);
                },
                get(){
                    return this.getSettings("phone");
                }
            },
            fax:{
                set(value){
                    this.setSetting("fax",value);
                },
                get(){
                    return this.getSettings("fax");
                }
            },
            mobile:{
                set(value){
                    this.setSetting("mobile",value);
                },
                get(){
                    return this.getSettings("mobile");
                }
            },
            email:{
                set(value){
                    this.setSetting("email",value);
                },
                get(){
                    return this.getSettings("email");
                }
            },
            site:{
                set(value){
                    this.setSetting("site",value);
                },
                get(){
                    return this.getSettings("site");
                }
            },
            iban:{
                set(value){
                    this.setSetting("iban",value);
                },
                get(){
                    return this.getSettings("iban");
                }
            },
            bic:{
                set(value){
                    this.setSetting("bic",value);
                },
                get(){
                    return this.getSettings("bic");
                }
            },

        },
        components:{
            CustomInput
        },

    }
</script>

<style scoped lang="scss">
    .logo-row{
        display: flex;
        input{
            display: none;
        }
        .logo-image{
            padding:10px;
            border: 1px solid rgba(35, 138, 196, 0.2);
            box-sizing: border-box;
            border-radius: 8px;
            min-height: 61px;
            min-width: 206px;
            margin-right: 20px;
            img{
                max-height:50px;
                max-width:200px;
            }
        }
        .logo-links{
            width: 200px;
            .link-text{
                height: 30px;
                &.gray-text{
                    .icon{
                        background-color: rgba(0,0,0,0.6);
                    }
                }
            }
        }
    }
    .mobile-row{
        display: flex;
        .custom-input__block{
            width: 220px;
            max-width: 220px;
            min-width: 220px;
            margin-right: 25px;
        }
    }
</style>
