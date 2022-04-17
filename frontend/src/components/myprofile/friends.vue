<template>
    <section class="friends module">
        <div class="btnwrapper">
            <h3 :class="{active: showfriends}" @click="showfriendslist">Мои друзья</h3>
            <h3 :class="{active: showclaims}" @click="showclaimslist">Заявки</h3>
        </div>
        <hr>
        <div class="friendscontainer">
            <div class="friendswrapper" v-for="friend in friends" v-if="showfriends">
                <router-link :to="{ name: 'profile', params: {id: friend.frienduser ? friend.frienduser.id : friend.userfriend.id}}">
                    <h3 class="name">{{ friend.frienduser ? friend.frienduser.name : friend.userfriend.name}}</h3>
                </router-link>
                <div class="actions">
                    <router-link to="">
                        <iconbutton image="/icons/interface/new-message.svg">Перейти в диалог</iconbutton>
                    </router-link>
                        <iconbutton image="/icons/interface/remove.svg" @click="deletefriend(friend.frienduser ? friend.frienduser.id : friend.userfriend.id)">Удалить из друзей</iconbutton>
                </div>
            </div>
            <div class="friendscontainer" v-if="showclaims">
                <div class="friendswrapper" v-for="claim in claims">
                    <router-link :to="{ name: 'profile', params: {id: claim.userfriend.id}}">
                        <h3 class="accenttext">{{ claim.userfriend.name }}</h3>
                    </router-link>
                    <iconbutton image="/icons/interface/addfriend.svg" @click="approveclaim(claim.userfriend.id)">Подвтердить</iconbutton>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import Iconbutton from "@/components/iconbutton";
import store from "@/store";
export default {
    name: "friends",
    components: {Iconbutton},
    data() {
        return {
            friends: {},
            claims: {},
            showfriends: true,
            showclaims: false,
        }
    },
    methods: {
        showfriendslist() {
            this.getfriends();
            this.showfriends = true;
            this.showclaims = false;
        },
        async showclaimslist() {
            this.showfriends = false;
            this.showclaims = true;
            await this.getclaims();
        },
        async getfriends (){
            let response = await fetch(store.getters.apiserver + 'friends', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json;charset=utf-8',
                    'Access-Control-Allow-Origin': '<origin>',
                    'Authorization': 'Bearer ' + store.getters.gettoken,
                },
            });
            let result = await response.json(); //ответ в json
            let code = await response.status; //код ответа
            switch (await code) {
                case 200: //Успешно
                    this.friends = result;
                    break;
                default: //Другая ошибка
                    showalert('Ошибка!', 'Ошибка при загрузки списка друзей: ' + code);
                    break;
            }
        },
        async getclaims() {
            let response = await fetch(store.getters.apiserver + 'friends/unproved', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json;charset=utf-8',
                    'Access-Control-Allow-Origin': '<origin>',
                    'Authorization': 'Bearer ' + store.getters.gettoken,
                },
            });
            let result = await response.json(); //ответ в json
            let code = await response.status; //код ответа
            switch (await code) {
                case 200: //Успешно
                    this.claims = result;
                    break;
                default: //Другая ошибка
                    showalert('Ошибка!', 'Ошибка при загрузки списка заявок: ' + code);
                    break;
            }
        },
        async approveclaim(id){
            let response = await fetch(store.getters.apiserver + 'friends/' + id, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json;charset=utf-8',
                    'Access-Control-Allow-Origin': '<origin>',
                    'Authorization': 'Bearer ' + store.getters.gettoken,
                },
            });
            let result = await response.json(); //ответ в json
            let code = await response.status; //код ответа
            switch (await code) {
                case 200: //Успешно
                    showalert('Успешно!', result.message);
                    this.showclaims = false;
                    break;
                default: //Другая ошибка
                    showalert('Ошибка!', 'Ошибка при загрузки списка заявок: ' + code);
                    break;
            }
        },
        async deletefriend(id){
            let response = await fetch(store.getters.apiserver + 'friends/' + id, {
                method: 'DELETE',
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
                case 200: //Успешно
                    showalert('Успешно!', result.message);
                    this.showfriends = false;
                    break;
                default: //Другая ошибка
                    showalert('Ошибка!', 'Ошибка: ' + code);
                    break;
            }
        },
    },
    async created() {
        await this.getfriends();
    },
}
</script>

<style scoped>
.friends {
    width: 100%;
    padding: 16px;
}
hr {
    margin: 8px 0;
}
h3 {
    color: var(--main-color);
}
.friendswrapper {
    width: 100%;
    padding: 8px;
    border-radius: 20px;
    margin-bottom: 8px;
}
.actions {
    padding: 8px 0px;
}
.btnwrapper {
    display: flex;
}
.btnwrapper h3 {
    margin-right: 16px;
    color: var(--black-color);
}
h3.active {
    color: var(--accent-color);
    border-bottom: 2px solid var(--accent-color);
}
@media (max-width: 578px) {
    .actions .btn {
        margin-bottom: 8px;
    }
    .friendswrapper {
        text-align: center;
    }
}

</style>