<template>
    <div class="pagination-container" v-if="meta.last_page!==1">
        <div :class="{disabled:isFirstPage}" v-if="showFirstLast">
            <a v-if="!isFirstPage" @click="getData(firstPage)" href="#">
                <i class="icon default pagination-arrow-icon reverse"></i>
            </a>
            <i v-else class="icon default pagination-arrow-icon reverse"></i>
        </div>
        <div :class="{disabled:isFirstPage}">
            <a v-if="!isFirstPage" @click="getData(prevPage)" href="#">
                <i class="icon default pagination-arrow-icon reverse"></i>
            </a>
            <i v-else class="icon default pagination-arrow-icon reverse"></i>
        </div>
        <div class="separator"></div>
        <ul class="paginator-links-container">
            <li v-for="link in links" class="pagination-link" :class="{active:link.current}">
                <a v-if="link.link" @click="getData(link.link)" href="#" >{{link.num}}</a>
                <span v-else>{{link.num}}</span>
            </li>
        </ul>
        <div class="separator"></div>
        <div :class="{disabled:isLastPage}">
            <a v-if="!isLastPage" @click="getData(nextPage)" href="#">
                <i class="icon default pagination-arrow-icon"></i>
            </a>
            <i v-else class="icon default pagination-arrow-icon"></i>
        </div>
        <div :class="{disabled:isLastPage}" v-if="showFirstLast">
            <a v-if="!isLastPage" @click="getData(lastPage)" href="#">
                <i class="icon default pagination-arrow-icon"></i>
            </a>
            <i v-else class="icon default pagination-arrow-icon"></i>
        </div>
    </div>
</template>

<script>
    import {toUrl} from "@vue/components/helpers/objectToQuery.js";

    export default {
        name: "Pagination",
        props: {
            meta:Object,
            onEachSide:{default:2, type:Number},
            showFirstLast:{default:false, type:Boolean},
            params:{default:function(){return {}},type:Object},
        },
        methods:{
            newLink(i){
                let pagParams = Object.assign(this.params,{page:i});
                return {
                    num:i,
                    link:this.meta.path+"?"+toUrl(pagParams),
                    current: i===this.meta.current_page,
                }
            },
            getData(url){
                this.showLoader();
                axios.get(url).then(({data})=>{
                    this.$emit("ondata",data);
                    this.hideLoader();
                })
            }
        },
        computed:{
            isFirstPage(){
                return this.meta.current_page === 1;
            },
            isLastPage(){
                return this.meta.current_page === this.meta.last_page;
            },
            firstPage(){
                return this.meta.path+"?page=1";
            },
            prevPage(){
               if(this.isFirstPage)
                   return this.firstPage;
               else
                   return this.meta.path+"?page="+(this.meta.current_page-1);
            },
            lastPage(){
                return this.meta.path+"?page="+this.meta.last_page;
            },
            nextPage(){
               if(this.isLastPage)
                   return this.lastPage;
               else
                   return this.meta.path+"?page="+(this.meta.current_page+1);
            },
            separator(){
                return {num:"...",link:null,current:false};
            },
            links(){
                let links = [];
                if(this.meta.last_page<=7) {
                    for (let i = 1; i <= this.meta.last_page; i++) {
                        links.push(this.newLink(i));
                    }
                    return links;
                }
                if(this.meta.current_page<3){
                    for (let i = 1; i <4; i++) {
                        links.push(this.newLink(i));
                    }
                    links.push(this.separator);
                    links.push(this.newLink(this.meta.last_page));
                    return links;
                }
                if(this.meta.current_page>=3 && this.meta.current_page<this.meta.last_page-2){
                    links.push(this.newLink(1));
                    if(this.meta.current_page-1!==2)
                    links.push(this.separator);
                    for (let i = this.meta.current_page-1; i <=this.meta.current_page+1; i++) {
                        links.push(this.newLink(i));
                    }
                    links.push(this.separator);
                    links.push(this.newLink(this.meta.last_page));
                    return links;
                }
                if(this.meta.current_page>this.meta.last_page-4){
                    links.push(this.newLink(1));
                    links.push(this.separator);
                    for (let i = this.meta.last_page-3; i <=this.meta.last_page; i++) {
                        links.push(this.newLink(i));
                    }
                    return links;
                }
            },

        }
    }
</script>

<style scoped lang="scss">
    .pagination-container{
        display: flex;
        list-style: none;
        height: 28px;
        align-items: center;
        font-family: 'Inter-Medium', Helvetica, Arial, sans-serif;
        font-style: normal;
        font-weight: 500;
        font-size: 14px;
        color: rgba(0, 0, 0, 0.8);
        margin-top: 30px;
        justify-content: flex-end;
        a{
            text-decoration: none;
        }
        .separator{
            border: 1px solid rgba(35, 138, 196, 0.2);
            height: 12px;
            margin:0 20px
        }
        .pagination-arrow-icon{
            width: 6px;
            height: 12px;
            margin: 0px 10px;
            background-color:#238AC4;

        }
        .disabled{
            .pagination-arrow-icon {
                cursor: default;
                background-color: rgba(35, 138, 196, 0.2);
            }
        }
        .paginator-links-container{
            display: flex;
            list-style: none;
            padding: 0px;
            align-items: center;
            .active{
                width:26px;
                height: 28px;
                background: #238AC4;
                border-radius: 5px;
                display: flex;
                align-items: center;
                justify-content: center;
                a{
                    color: white;
                }
            }
            .pagination-link{
                margin-right:30px;
                a{
                    color: black;
                }
                &.active{
                    margin-right: 20px;
                    margin-left: -10px;
                    a{
                        color: white;
                    }
                    &:first-child{
                        margin-left: 0;
                    }
                }
                &:last-child{
                    margin-right: 0;
                }
            }
        }
    }
</style>