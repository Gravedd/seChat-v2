<template>
    <theheader></theheader>
    <router-view/>
    <notification-contrainer></notification-contrainer>
</template>
<script>
import theheader from "@/components/theheader";
import '/public/alertwindow/alertwindow.css';
import store from '@/store';
import PopupNotification from "@/components/popupNotification";
import NotificationContrainer from "@/components/notificationContrainer";

export default {
    store: store,
    components: {
        NotificationContrainer,
        PopupNotification,
        theheader
    },
    data() {
        return {
            test: false,
        }
    },
    created() {
        store.dispatch('deviceStart');
        store.dispatch( 'CHECKLOGIN');
        // store.dispatch('CHECKLOGIN');
    },
}
</script>
<style>
#app {
    font-size: 16pt;
    font-family: 'Roboto Light';
    user-select: none;
}

body {
    background-color: var(--lighter-color);
}

* {
    margin: 0;
    box-sizing: border-box;
}

a {
    text-decoration: none;
    color: var(--main-color);
}

.container {
    /*min-height: 875px;*/
}

@font-face {
    font-family: "Roboto";
    src: url('@/assets/fonts/Roboto.ttf');
}

@font-face {
    font-family: "LightFont";
    src: url('@/assets/fonts/Montserrat-Light.ttf');
}

.module {
    border-radius: 20px;
    background-color: var(--white-color);
    box-shadow: 0 0 5px 0 var(--shadow-color);
}


:root {
    /* ЦВЕТА */
    --main-color: #0F88D0;
    --dark-color: #055787;
    --accent-color: #FFBC04;
    --accent2-color: #f1521a;
    --white-color: #fff;
    --black-color: #000;
    --lighter-color: #ebf0f6;
    --light-color: #e3e2e2;
    --shadow-color: rgba(0, 0, 0, 0.21);
    --gray1: #a0a0a0;
    --gray2: #6f6f6f;
    --gray3: #e8e8e8;
    --gray4: #f1f1f1;
    /* Другое */
    --padding: 400px;

}

h1, h2, h3, h4 {
    font-family: "LightFont";
    letter-spacing: 1px;
}

.red {
    background-color: var(--accent2-color) !important;
}
.redtext {
    color: var(--accent2-color) !important;
}

body::-webkit-scrollbar {
    width: 8px; /* ширина для вертикального скролла */
    height: 8px; /* высота для горизонтального скролла */
    background-color: var(--gray1);
}

body::-webkit-scrollbar-thumb {
    box-shadow: inset 1px 1px 10px var(--gray2);
}
.accenttext {
    color: var(--accent-color);
}
@media (max-width: 1761px) {
    :root {
        --padding: 200px;
    }
}

@media (max-width: 1200px) {
    :root {
        --padding: 32px;
    }
}

@media (max-width: 710px) {
    .container {
        min-height: 0px;
        height: auto;
    }
}

@media (max-width: 700px) {
    .workinginfo .itemcontrainer {
        flex-direction: column;
        align-items: center;
    }

    .workinginfo .itemcontrainer .item {
        width: 100% !important;
    }

    .item .icon {
        background-size: 20%;
    }
}

@media (max-width: 450px) {
    :root {
        --padding: 16px;
    }
}

@media (max-width: 380px) {
    :root {
        --padding: 8px;
    }
}
</style>
