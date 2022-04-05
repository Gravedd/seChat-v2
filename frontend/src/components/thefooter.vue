<template>
<footer>
    <router-link to="/">Главная</router-link>
    <router-link to="">Профиль</router-link>
    <router-link to="">Поиск</router-link>
    <router-link to="">Приложение</router-link>
</footer>
</template>

<script>
export default {
    name: "thefooter"
}
</script>

<style scoped>
footer {
    width: 100%;
    height: 75px;
    display: flex;
    padding: 0 var(--padding);
    background-color: var(--dark-color);
    color: var(--white-color);
    justify-content: center;
    align-items: center;
}
footer a {
    color: var(--white-color);
    margin-right: 100px;
}
@media (max-width: 551px) {
    footer {
        flex-wrap: wrap;
        text-align: center;
    }
    footer a {
        margin-right: 16px;
    }
}
</style>