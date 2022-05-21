<template>
<div class="popupwrapper" :title="id">

        <div class="btncontainer">
                <h4>{{ headertext }}</h4>
                <div class="closebtn" tabindex="true" @click="remove(id)">&times;</div>
        </div>
        <router-link :to="{ name: 'chat', params: {userid: userid}}" @click="remove(id)">
        <div class="nottext">
            {{ username }}{{ contenttext }}
        </div>
        </router-link>
</div>
</template>

<script>
import store from '@/store'
import router from "@/router";
export default {
    name: "popupNotification",
    store: store,
    data() {
        return {
            username: '',
        }
    },
    props: {
        headertext: String,
        contenttext: String,
        id: Number,
        userid: Number,
    },
    methods: {
        remove(id){
            store.commit('removeNotificationById', id);
        },
        async getUser() {
            let response = await fetch(store.getters.apiserver + 'users/' + this.userid,{
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json;charset=utf-8',
                    'Access-Control-Allow-Origin': '<origin>',
                    'Authorization': 'Bearer ' + store.getters.gettoken,
                }
            });
            let result = await response.json(); //ответ в json
            let code = await response.status; //код ответа
            switch (await code) {
                case 200:
                    this.username = result[0].name;
                    break;
            }
        }
    },
    created() {
        this.getUser()
    }
}
</script>

<style scoped>
.popupwrapper{
    background-color: rgba(0, 0, 0, 0.45);
    width: 300px;
    border-radius: 8px;
    padding: 8px;
    margin-bottom: 8px;
    color: var(--white-color);
    display: block;
}
a {
    color: var(--white-color);
}
.btncontainer {
    display: flex;
    justify-content: space-between;
}
.closebtn {
    background-color: rgba(0, 0, 0, 0.3);
    text-align: center;
    border-radius: 4px;
    width: 25px;
    height: 25px;
}
.popupwrapper h4 {
    letter-spacing: 0px;
}
.nottext {

}
</style>