<template>
<div class="menucontainer">
    <div class="menuheader">
        <h2><router-link to="/">seChat</router-link></h2>
        <iconbutton image="/icons/interface/remove.svg" nopadding="true" title="закрыть"></iconbutton>
    </div>
    <div class="menulist">
        <router-link to="/login">Авторизация</router-link>
        <router-link to="/dialogues">Диалоги</router-link>
        <router-link to="/myprofile">Профиль</router-link>
        <router-link to="/search">Поиск</router-link>
        <router-link to="/logout">Выйти</router-link>
    </div>
</div>
</template>

<script>
import Iconbutton from "@/components/iconbutton";
export default {
    name: "themenu",
    components: {Iconbutton}
}
</script>

<style scoped>



</style>