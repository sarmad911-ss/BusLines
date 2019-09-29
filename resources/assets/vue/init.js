import Vue from "vue";
import Router from "@vue/../js/components/Router.js";
window.showLoader = function(){
    if(typeof loader !== "undefined" && !loader.classList.contains("show")) {
        loader.classList.add("show");
        document.getElementsByTagName("body")[0].classList.add("no-scroll");
    }
};
Vue.mixin({
    methods: {
        openModal(modalID, params){
            if(typeof window[modalID] !== "undefined") window[modalID].__vue__.show(params);
        },
        closeModal(modalID){
            if(typeof window[modalID] !== "undefined") window[modalID].__vue__.hide();
        },
        showLoader(){
            showLoader();
        },
        hideLoader(){
            if(typeof loader !== "undefined" && loader.classList.contains("show")) {
                loader.classList.remove("show");
                document.getElementsByTagName("body")[0].classList.remove("no-scroll");
            }
        }
    }
});
Vue.config.devtools = true;
require("@vue/../js/components/axios.js");
window._token = document.head.querySelector('meta[name="csrf-token"]').content;

export {Vue,Router};