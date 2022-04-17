<template>
<div class="container">
    <div class="profileheaderwrapper">
        <div class="profileheader">
            <div class="avatarwrapper">
                <div class="avatarimg" style="background-image: url(/icons/user.svg);"></div>
            </div>
            <div class="userinfo">
                <div class="userinfowrapper">
                    <h3>{{ username }}</h3>
                    <span class="status">{{ userstatus }}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="useractions">
        <iconbutton image="/icons/interface/addfriend.svg" @click="sendfriendrequest">Добавить в друзья</iconbutton>
        <router-link :to="{ name: 'chat', params: {userid: $route.params.id}}">
            <iconbutton image="/icons/interface/new-message.svg">Отправить сообщение</iconbutton>
        </router-link>
    </div>
    <div class="userstatistics">
        <div class="userstatisticswrapper">
            <h2>Статистика</h2>
            <div>Был онлайн: {{ userupdate }}</div>
            <div>Зарегистрирован: {{ userdate }}</div>
            <div>Айди пользователя: {{ $route.params.id }}</div>
        </div>
    </div>
</div>
</template>

<script>
import thebutton from "@/components/thebutton";
import iconbutton from "@/components/iconbutton";
import store from "@/store";
import router from "@/router";


export default {
    name: "ProfileView",
    components: {
        thebutton, iconbutton
    },
    data() {
        return {
            username: '',
            userstatus: '',
            userdate: '',
            userupdate: '',

        }
    },
    props: ['id'],
    methods: {
        async sendfriendrequest($friend_id){
            let response = await fetch(store.getters.apiserver + 'friends/' + this.id, {
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
                    break;
                default: //Другая ошибка
                    showalert('Ошибка!', 'Ошибка при загрузки списка заявок: ' + code);
                    break;
            }
        }
    },
    async created() {
        let response = await fetch(store.getters.apiserver + 'users/' + this.id,{
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
                this.username = result.name;
                this.userstatus = result.status;
                this.userdate = result.created_at;
                this.userupdate= result.updated_at;
                break;
            case 404:
                showalert('Ошибка 404', 'Такого пользователя не существует');
                router.push('/');
                break;
            default:
                showalert('Ошибка', 'Ошибка: ' + code);
                router.push('/');
                break;
        }

    }
}
</script>

<style scoped>
.profileheaderwrapper {
    width: 100%;
    height: 300px;
    background-color: var(--main-color);
    display: flex;
    justify-content: center;
    color: var(--white-color);
}
.profileheader {
    width: 640px;
    height: 100%;

    display: flex;
}
.avatarwrapper {
    height: 100%;
    padding: 32px;
    display: flex;
    align-items: center;
    width: 50%;
}
.userinfo {
    width: 50%;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
}
.avatarimg {
    width: 200px;
    height: 200px;
    background-size: 50%;
    background-position: center;
    background-repeat: no-repeat;
    background-color: white;
    border-radius: 1000px;
}
.useractions {
    width: 100%;
    padding: 8px var(--padding);
    display: flex;
    justify-content: center;
}
.userstatistics {
    padding: 8px var(--padding);
    display: flex;
    justify-content: center;
    text-align: center;
}
.userstatistics h2 {
    color: var(--main-color);
}
@media (max-width: 656px) {
    .useractions {
        flex-wrap: wrap;
    }
    .btn {
        margin-bottom: 8px;
    }
}
@media (max-width: 550px) {
    .avatarwrapper {
        width: auto;
    }
    .userinfo {
        width: auto;
    }
}
@media (max-width: 440px) {
    .avatarimg {
        width: 125px;
        height: 125px;
    }
}
@media (max-width: 384px) {
    .profileheader {
        flex-direction: column;
        align-items: center;
    }
    .userinfo {
        height: auto;
    }
    .avatarwrapper {
        height: auto;
    }
    .profileheaderwrapper {
        height: auto;
    }
}

</style>