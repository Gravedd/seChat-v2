<template>
    <form id="loginform" v-on:submit.prevent="auth">
        <label for="logininput">Ваша почта</label>
        <input name="login" type="email" maxlength="32" id="logininput" placeholder="Почта..." v-model="email" required>
        <label for="passinput">Ваш пароль</label>
        <input name="password" type="password" maxlength="32" id="passinput" placeholder="Пароль..." v-model="password" required>
        <label>&nbsp;</label>
        <input type="submit" value="ВОЙТИ">
    </form>
</template>

<script>
import store from "@/store"; //импорт глоб.переменных
export default {
    name: "loginform",
    data () {
        return {
            email: '',
            password: '',
        }
    },
    methods: {
        async auth() {
            let response = await fetch(store.server + 'login', {
                method: 'POST',
                headers: {
                    'Accept' : 'application/json',
                    'Content-Type': 'application/json;charset=utf-8',
                },
                body: JSON.stringify({
                    'email': this.email,
                    'password': this.password,
                })
            });
            let result = await response.json();
            let code = await response.status;
            switch (await code) {
                case 201:
                    console.log('Удачно');
                    break;
                case 422:
                    console.log('Ошибка 422. Не корректные данные');
                    break;
                case 401:
                    console.log('Ошибка 401. Не авторизованны');
                    break;
                default:
                    console.log('Ошибка ' + code);
            }
        }

    }
}
</script>

<style scoped>
.authFormsWrapper form input{
    width: 100%;
    height: 40px;
    border-radius: 20px;
    border: none;
    background-color: var(--lighter-color);
    padding: 0 16px;
    transition: 0.5s filter;
}
.authFormsWrapper form input[type="submit"]{
    background-color: var(--accent-color);
    font-weight: bold;
    font-size: 12pt;
    color: var(--white-color);
}
.authFormsWrapper form input:focus {
    outline: 2px solid var(--accent-color);
}
.authFormsWrapper form input:hover {
    filter: brightness(95%);
}

</style>