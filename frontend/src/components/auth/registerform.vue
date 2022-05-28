<template>
    <form id="registerform" v-on:submit.prevent="register">
        <label for="logininput">Ваш логин</label>
        <input name="name" type="text" maxlength="32" id="logininput" placeholder="Логин..." v-model="name">
        <label for="emailinput">Ваша почта</label>
        <input name="email" type="email" id="emailinput" placeholder="Почта..." v-model="email">
        <label for="passinput">Ваш пароль</label>
        <input name="password" type="password" maxlength="32" id="passinput" placeholder="Пароль..." v-model="password">
        <label for="passconfirminput">Подтверждение пароля</label>
        <input name="passwordconfirm" type="password" maxlength="32" id="passconfirminput" placeholder="Пароль..." v-model="password_confirmation">
        <label>&nbsp;</label>
        <input type="submit" value="ЗАРЕГИСТИРОВАТСЯ">
    </form>
</template>

<script>
import store from "@/store"; //импорт глоб.переменных
export default {
    name: "registerform",
    store: store,
    data () {
        return {
            name: '',
            email: '',
            password: '',
            password_confirmation: '',
        }
    },
    methods: {
        async register() {
            store.dispatch('REGISTER', {'name': this.name, 'email': this.email, 'password': this.password, 'password_confirmation': this.password_confirmation });
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