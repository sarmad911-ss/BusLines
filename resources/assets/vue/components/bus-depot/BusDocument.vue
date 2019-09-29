<template>
    <div class="document">
        <div
            v-if="typeof value === 'undefined'"
            class="document__empty"
            @click="$refs.fileInput.click()"
        >
            <i class="icon plus-icon default no-hover"></i>
            Dokument hochladen
        </div>
        <div v-else class="document__full">
            <img src="/images/file.png" alt="">
            <div class="document__name m-8">{{value.name}}</div>
            <a download :href="value.url" class="link-text m-8">
                <i class="icon default download-icon"></i> Dokument herunterladen
            </a>
            <div class="gray-links m-8">
                <div class="gray-light-text" @click="$refs.fileInput.click()">
                    <i class="icon default replace-icon"></i> Ändern
                </div>
                <div class="gray-light-text" @click="deleteFile">
                    <i class="icon default delete-icon"></i> Löschen
                </div>
            </div>
            <div class="gray-light-text last-update-text" v-if="value.last_update">
                <div>letzte Änderungen</div>
                <div>
                    <span v-if="value.last_update.user">{{value.last_update.user.name}}</span> {{value.last_update.time}}
                </div>
            </div>
        </div>
        <input type="file" hidden @change="saveFile" ref="fileInput">
    </div>
</template>

<script>
    import {toFormData} from "@vue/components/helpers/objectToQuery.js";
    import {Router} from "@vue/init.js";
    export default {
        name: "BusDocument",
        props:['value','busId'],
        data: function(){
            return {
                file: null,
            }
        },
        methods:{
            inputClick(){

            },
            saveFile(e){
                if(e.target.files.length){
                    let filedata = {
                        file:e.target.files[0],
                        bus_id: this.busId,
                    };
                    if(this.value && this.value.id)
                        filedata.id = this.value.id;
                    Router.get("storeBusFileAction").then((url)=>{
                       axios.post(url,toFormData(filedata)).then(({data})=>{
                           if(!this.value || !this.value.id)
                               this.$emit("onnew",data.data);
                           this.$emit("input",data.data);
                       });
                    });
                }
            },
            deleteFile(){
                Router.get("deleteBusFileAction").then((url)=>{
                    axios.post(url,{id:this.value.id}).then(({data})=>{
                       this.$emit("ondelete",this.value.id);
                    });
                })
            }
        }
    }
</script>

<style scoped lang="scss">
    .document{
        border: 1px solid rgba(35, 138, 196, 0.2);
        box-sizing: border-box;
        border-radius: 8px;
        min-height: 232px;
        &__empty{
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            height: 100%;
            cursor:pointer;
            font-family: 'Inter-Medium', Helvetica, Arial, sans-serif;
            font-style: normal;
            font-weight: 500;
            font-size: 12px;
            color: #238AC4;
            min-height: 232px;
            .icon{
                width:20px;
                height:20px;
                margin-bottom:20px;
            }
        }

        &__full{
            padding: 20px;
            img{
                width: 35px;
                height: 45px;
            }
            .icon{
                width: 12px;
                height: 17px;
                margin-right: 6px;
            }
            .gray-links{
                display: flex;
                >div{
                    cursor: pointer;
                    &:first-child{
                        margin-right: 16px;
                    }
                }

            }
            .gray-light-text{
                display: flex;
            }
            .m-8{
                margin: 10px 0;
            }
            .last-update-text{
                display:block;
            }
        }
        &__name{
            font-family: 'Inter-SemiBold', Helvetica, Arial, sans-serif;
            font-style: normal;
            font-weight: 600;
            font-size: 14px;
            line-height: 22px;
            color: rgba(0, 0, 0, 0.8);
            max-height: 45px;
            overflow: hidden;
            word-break: break-word;
        }
    }
</style>