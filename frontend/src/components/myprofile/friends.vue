<template>
    <section class="friends module">
        <h1>Мои друзья</h1>
        <hr>
        <div class="friendscontainer">

            <div class="friendswrapper" v-for="friend in friends">
                <router-link :to="{ name: 'profile', params: {id: friend.frienduser ? friend.frienduser.id : friend.userfriend.id}}">
                    <h3 class="name">{{ friend.frienduser ? friend.frienduser.name : friend.userfriend.name}}</h3>
                </router-link>
                <div class="actions">
                    <router-link to="">
                        <iconbutton image="/icons/interface/new-message.svg">Перейти в диалог</iconbutton>
                    </router-link>
                    <router-link to="">
                        <iconbutton image="/icons/interface/remove.svg" class="disabled ">Удалить из друзей</iconbutton>
                    </router-link>
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
            friends: {}
        }
    },
    methods: {
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
@media (max-width: 578px) {
    .actions .btn {
        margin-bottom: 8px;
    }
    .friendswrapper {
        text-align: center;
    }
}

</style>