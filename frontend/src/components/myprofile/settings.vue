<template>
    <section class="settings module">
        <h2>Настройки</h2>
        <hr>
        <div class="infocontainer">
            <div class="item">
                <div class="settingtitle">Имя профиля</div>
                <div class="settingvalue"><input maxlength="32" type="text" v-model="username" title="Не менее 3-х символов, не более 32 символов"></div>
                <div class="settingaction" @click="changesomething(username, 'getname')">Изменить</div>
            </div>
            <div class="item">
                <div class="settingtitle">Почта</div>
                <div class="settingvalue"><input maxlength="128" type="email" v-model="uemail" title="Не более 128 символов"></div>
                <div class="settingaction" @click="changesomething(uemail, 'getemail')">Изменить</div>
            </div>
            <div class="item">
                <div class="settingtitle">Статус</div>
                <div class="settingvalue"><input maxlength="256" type="text" v-model="ustatus" title="Не менее 3-х символов, не более 256 символов"></div>
                <div class="settingaction" @click="changesomething(ustatus, 'getstatus')">Изменить</div>
            </div>
            <div class="item">
                <div class="settingtitle">Пароль</div>
                <div class="settingvalue"><input disabled maxlength="64" type="email" placeholder="**********" title="Пока не доступно"></div>
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
            ustatus: store.getters.getstatus,
            uemail: store.getters.getemail,
        }
    },
    methods: {
        /**
         * Проверить, равно ли какое-либо значение (value) Значению из vuex store
         *
         * @param value string
         * @param type string - имя store.getters.[это]
         * @return boolean
         */
        checkold(value, type) {
            return value === store.getters[type];
        },

        /**
         * Изменить информацию пользователя значение имени, почты, стутуса(пароля)
         *
         * Метод может изменить имя, почту, статус, (пароль) пользователя.
         * Сначала проверка, изменены ли данные или отсались как были до.
         * Затем происходит отправка какого-либо значения на сервер
         * И в случае положительного ответа сервера, происходит изменение на клиенте
         *
         * @param send string - новое значение
         * @param type string - что будет изменено, не обходимо, чтобы это был getter из store
         *              Возможные значения: getname, getstatus, getemail
         * @returns {Promise<void>}
         */
        async changesomething(send, type) {
            if (this.checkold(send, type)) {
                return showalert('Ошибка', 'Вы не изменили данные');
            }
            let user = {}
            if (type === 'getname') {
                user.newname = this.username;
            }
            if (type === 'getstatus') {
                user.newstatus = this.ustatus;
            }
            if (type === 'getemail') {
                user.newemail = this.uemail;
            }
            console.log(user);
            //Запрос на сервер об изменении чего-либо
            let response = await fetch(store.getters.apiserver + 'users', {
                method: 'PATCH',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json;charset=utf-8',
                    'Authorization': 'Bearer ' + store.getters.gettoken,
                },
                //Указываем что оправляем
                body: JSON.stringify(user),
            });
            let result = await response.json(); //ответ в json
            let code = await response.status; //код ответа
            switch (await code) {
                case 200: //Успешно
                    showalert('Успешно!', 'Изменено');
                    if (type === 'getname') {
                        store.commit('changename', this.username);
                    }
                    if (type === 'getstatus') {
                        store.commit('changestatus', this.ustatus);
                    }
                    if (type === 'getemail') {
                        store.commit('changestatus', this.uemail);
                    }
                    break;
                case 422:
                    if (result.errors.newemail) {
                        showalert('Ошибка!', 'Введенная почта уже используется, или некорректна');
                    } else {
                        showalert('Ошибка!', 'Не корректные данные. Значение должно быть не менее 3 символов');
                    }
                    this.username = store.getters.getname;
                    this.ustatus = store.getters.getstatus;
                    this.uemail = store.getters.getemail;
                    break;
                default: //Другая ошибка
                    showalert('Ошибка!', 'Ошибка:' + code);
                    this.username = store.getters.getname;
                    this.ustatus = store.getters.getstatus;
                    this.uemail = store.getters.getemail;
                    break;
            }

        }
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