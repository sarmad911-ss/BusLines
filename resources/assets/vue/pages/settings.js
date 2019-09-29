import {Vue,Router} from "@vue/init.js";
import CalcSettingsList from "@vue/components/settings/CalcSettingsList.vue";
import CompanySettingsList from "@vue/components/settings/CompanySettingsList.vue";

window.app = new Vue({
    el: "#app",
    data(){
      return {
          component:"CompanySettingsList",
          tab:"company",
      }
    },
    methods:{
        showCompanySettings(){
            this.component = 'CompanySettingsList';
            this.tab = "company";
        },
        showCalcSettings(){
            this.component = 'CalcSettingsList';
            this.tab = 'calc';
        }
    },
    components:{
        CalcSettingsList,CompanySettingsList
    }
});