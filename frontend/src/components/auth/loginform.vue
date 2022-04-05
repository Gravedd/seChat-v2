<template>
    <form id="loginform">
        <label for="logininput">Ваш логин</label>
        <input name="login" type="text" maxlength="32" id="logininput" placeholder="Логин...">
        <label for="passinput">Ваш пароль</label>
        <input name="password" type="password" maxlength="32" id="passinput" placeholder="Пароль...">
        <label>&nbsp;</label>
        <input type="submit" value="ВОЙТИ">
    </form>
</template>

<script>
export default {
    name: "loginform"
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