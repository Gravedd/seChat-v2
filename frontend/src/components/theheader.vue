<template>
    <header>
        <div class="logo">
            <router-link to="/">seChat</router-link>
        </div>
        <nav>
            <router-link to="/login">Авторизация</router-link>
            <router-link to="/dialogues">Диалоги</router-link>
            <router-link to="/myprofile">Профиль</router-link>
            <router-link to="/search">Поиск</router-link>
            <router-link to="/logout">Выйти</router-link>
        </nav>
        <iconbutton class="menubtn" image="/icons/interface/menu.svg" nopadding="true" @click="openmenu"></iconbutton>
    </header>
    <div class="menucontainer" v-show="showmenu">
        <div class="menuheader">
            <h2><router-link to="/">seChat</router-link></h2>
            <iconbutton image="/icons/interface/remove.svg" nopadding="true" title="закрыть" @click="closemenu"></iconbutton>
        </div>
        <div class="menulist" @click="closemenu">
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
    name: "theheader",
    components: {Iconbutton},
    data() {
        return {
            showmenu: false,
        }
    },
    methods: {
        openmenu() {
            this.showmenu = true;
        },
        closemenu() {
            this.showmenu = false;
        }
    }
}
</script>

<style scoped>
.menucontainer {
    position: fixed;
    background-color: var(--lighter-color);
    width: 80%;
    height: 100vh;
    top: 0;
    border-bottom: 2px solid var(--main-color);
    margin-left: auto;
    left: 0;
    animation: 0.5s animcontainer;
    animation-timing-function: ease-in-out;
    box-shadow: 0 0 5px 1px var(--shadow-color);
}
@keyframes animcontainer {
    from {
        left: -950px;
    }
    to {
        left: 0;
    }
}
.menucontainer a {
    color: var(--main-color);
}
.menucontainer .router-link-active, .menulist .router-link-active {
    color: var(--accent-color) !important;
    border: none;
}
.menuheader {
    width: 100%;
    height: 50px;
    padding: 8px 32px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-top: 2px solid var(--main-color);
    margin-bottom: 8px;
}
.menulist {
    font-family: LightFont;
    padding: 0 32px;
}
.menucontainer .menulist * {
    display: block;
    padding: 8px 0;
    font-size: 30px;
    color: var(--black-color);
    border-bottom: 1px solid var(--black-color);
    text-align: end;
}
a {
    color: var(--white-color);
}


header {
    width: 100%;
    height: 50px;
    background-color: var(--main-color);
    display: flex;
    padding: 0 32px;
    align-items: center;
}

.logo {
    font-size: 24pt;
    margin-right: 100px;
}
.logo a {
    font-family: "LightFont";
    font-weight: bold;
    letter-spacing: 1px;
}

nav a {
    font-family: LightFont;
    letter-spacing: -1px;
    margin-right: 100px;
}
nav a:hover {
    border-bottom: 1px solid var(--accent-color);
}
.router-link-active {
    border-bottom: 2px solid var(--accent-color);
}
.logo .router-link-active {
    border: none;
}
.menubtn {
    display: none;
}

@media (max-width: 1170px) {
    nav a:last-child {
        margin-right: 0;
    }
}
@media (max-width: 1085px) {
    nav {
        display: none;
    }
    .menubtn {
        display: inline-flex;
    }
    header {
        justify-content: space-between;
        padding-right: 8px;
    }
}
@media (max-width: 500px) {
    @keyframes animcontainer {
        from {
            left: -400px;
        }
        to {
            left: 0;
        }
    }
}
@media (max-width: 400px) {
    @keyframes animcontainer {
        from {
            left: -300px;
        }
        to {
            left: 0;
        }
    }
}
</style>