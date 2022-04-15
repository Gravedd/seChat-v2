<template>
    <section class="settings module">
        <h2>Настройки</h2>
        <hr>
        <div class="infocontainer">
            <div class="item">
                <div class="settingtitle">Имя профиля</div>
                <div class="settingvalue"><input maxlength="32" type="text" v-model="username"></div>
                <div class="settingaction" @click="changename">Изменить</div>
            </div>
            <div class="item">
                <div class="settingtitle">Почта</div>
                <div class="settingvalue"><input readonly maxlength="128" type="email" :value="$store.getters.getemail"></div>
                <div class="settingaction">Изменить</div>
            </div>
            <div class="item">
                <div class="settingtitle">Статус</div>
                <div class="settingvalue"><input readonly  maxlength="256" type="email" :value="$store.getters.getstatus"></div>
                <div class="settingaction">Изменить</div>
            </div>
            <div class="item">
                <div class="settingtitle">Пароль</div>
                <div class="settingvalue"><input readonly maxlength="64" type="email" placeholder="**********"></div>
                <div class="settingaction">Изменить</div>
            </div>
            <div class="deletebtn">Удалить аккаунт</div>
        </div>

    </section>
</template>

<script>
import store from '@/store';
export default {
    name: "settings",
    store: store,
    data() {
        return {
            username: store.getters.getname,
        }
    },
    methods: {
        /**
         * Проверить, равно ли какое-либо значение (value)
         * Значению из vuex store
         */
        checkold(value, param){
            return value === store.getters[param];
        },
        async changename (){
            //Если новое имя равно старому то вернуть ошибку
            if (this.checkold(this.username, 'getname') ) {
                return showalert('Ошибка', 'Вы не изменили данные');
            }
            //Запрос на сервер об изменении имени
            let response = await fetch(store.getters.apiserver + 'users', {
                method: 'PATCH',
                headers: {
                    'Accept' : 'application/json','Content-Type': 'application/json;charset=utf-8','Access-Control-Allow-Origin': '<origin>',
                    'Authorization' : 'Bearer ' + store.getters.gettoken,
                },
                //Указываем что оправляем
                body: JSON.stringify({
                    'newname': this.username,
                })
            });
            //Ждем ответа
            let result = await response.json(); //ответ в json
            let code = await response.status; //код ответа
            switch (await code) {
                case 200: //Успешно
                    showalert('Успешно!', 'Имя профиля изменено');
                    store.commit('changename', this.username);
                    this.username = store.getters.getname;
                    break;
                case 422:
                    showalert('Ошибка!', 'Не корректные данные. Имя должно быть не менее 3 символов и не больше 32');
                    this.username = store.getters.getname;
                    break;
                default: //Другая ошибка
                    showalert('Ошибка!', 'Ошибка:' + code);
                    this.username = store.getters.getname;
                    break;
            }
        },

    }
}
</script>

<style scoped>
.settings {
    width: 100%;
    padding: 16px;
}
hr {
    margin: 8px 0;
}
.infocontainer {
    display: flex;
    flex-direction: column;
    width: 100%;
    font-family: "LightFont";
}
.item {
    display: flex;
    height: 50px;
    align-items: center;
}
.settingtitle {
    width: 30%;
}
.settingvalue {
    width: 50%;
}
.settingaction {
    width: 20%;
    cursor: pointer;
    color: var(--accent-color);
}
.settingvalue input {
    width: 80%;
    height: 35px;
}
.deletebtn {
    color: var(--accent2-color);
    padding: 8px;
    margin-left: auto;
    display: inline-block;
}
@media (max-width: 900px) {
    .settingtitle {
        width: 20%;
    }
    .settingvalue{
        width: 60%;
    }
    .item {
        text-align: center;
    }
}
@media (max-width: 570px) {
    .item {
        flex-direction: column;
        height: auto;
        margin-bottom: 16px;
    }
    .settingtitle, .settingvalue, .settingaction {
        width: 100%;
    }

}
</style>