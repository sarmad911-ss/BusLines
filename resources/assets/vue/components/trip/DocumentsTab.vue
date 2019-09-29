<template>
    <div class="docs-tab">
        <div class="generate-doc">
            <span class="link-text" @click="toggleGenerateDoc"><i class="icon default plus-icon"></i> Сгенерировать документ</span>
            <div class="generate-doc__list" :class="{opened:generateDocList}">
                <span @click="generateABDocument">Auftragsbestätigung</span>
                <span @click="generateFGDocument">Fahrauftrag</span>
            </div>
        </div>
        <div class="files-list">
            <trip-document @onsend="showSendedNotification" v-for="(file,index) in trip.files" :key="file.id" v-model="trip.files[index]" :trip-id="trip.id" @ondelete="deleteFile"></trip-document>
            <trip-document :trip-id="trip.id" @onnew="newFile"></trip-document>
        </div>
        <notification v-model="sended" :text="'Dokument an den Kunden gesendet'"></notification>
    </div>
</template>

<script>
    import TripDocument from "@vue/components/trip/TripDocument.vue";
    import Router from "@vue/../js/components/Router.js";
    import Notification from "../UI/Notification";

    export default {
        name: "DocumentsTab",
        props:{
            trip:Object,
        },
        data(){
            return{
                generateDocList : false,
                sended: false,
            }
        },
        methods:{
            deleteFile(id){
                this.trip.files.forEach((file,index)=>{
                    if(file.id==id) {
                        this.trip.files.splice(index, 1);
                        return false;
                    }
                })
            },
            newFile(file){
                this.trip.files.push(file);
            },
            toggleGenerateDoc(){
                this.generateDocList = !this.generateDocList;
            },
            generateABDocument(){
                Router.get("storeABAction").then((url)=>{
                    axios.post(url,{trip_id:this.trip.id}).then(({data})=>{
                        this.trip.files.push(data.data);
                        this.generateDocList = false;
                    });
                })
            },
            generateFGDocument(){
                Router.get("storeFGAction").then((url)=>{
                    axios.post(url,{trip_id:this.trip.id}).then(({data})=>{
                        this.trip.files.push(...data.data);
                        this.generateDocList = false;
                    });
                })

            },
            showSendedNotification(){
                this.sended = true;
            }
        },
        components:{
            TripDocument, Notification
        }
    }
</script>

<style scoped lang="scss">
    .files-list{
        display: grid;
        grid-template-columns: repeat(auto-fill,minmax(230px,1fr));
        grid-column-gap: 25px;
        grid-row-gap: 25px;
    }
    .generate-doc{
        margin-bottom: 20px;
        &__list{
            display:none;
            background: #FFFFFF;
            border: 1px solid rgba(35, 138, 196, 0.2);
            box-sizing: border-box;
            border-radius: 8px;
            margin-top: 10px;
            position: absolute;
            z-index: 20;
            span{
                display:block;
                cursor: pointer;
                padding: 12px 18px;
                &:nth-child(odd){
                    background: rgba(35, 138, 196, 0.03);
                }
            }
            &.opened{
                display:block;
            }
        }
    }
</style>
