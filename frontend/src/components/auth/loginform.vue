<template>
    <form id="loginform" v-on:submit.prevent="auth">
        <label for="logininput">Ваша почта</label>
        <input name="login" type="email" maxlength="32" id="logininput" placeholder="Почта..." v-model="email" required>
        <label for="passinput">Ваш пароль</label>
        <input name="password" type="password" maxlength="32" id="passinput" placeholder="Пароль..." v-model="password" required>
        <label>&nbsp;<span class="redtext" v-if="errors">Ошибка: </span>{{errors}}</label>
        <input type="submit" value="ВОЙТИ">
    </form>
</template>

<script>
import store from "@/store";
export default {
    name: "loginform",
    components: {},
    store: store,
    data () {
        return {
            email: '',
            password: '',
            errors: '',
        }
    },
    methods: {
        async auth() {
            store.dispatch('LOGIN_REQUEST', {'password': this.password, 'email': this.email});
        }

    },
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