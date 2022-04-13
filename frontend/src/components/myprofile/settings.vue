<template>
    <section class="settings module">
        <h2>Настройки</h2>
        <hr>
        <div class="infocontainer">
            <div class="item">
                <div class="settingtitle">Имя профиля</div>
                <div class="settingvalue">{{ $store.getters.getname }}</div>
                <div class="settingaction">Изменить</div>
            </div>
            <div class="item">
                <div class="settingtitle">Почта</div>
                <div class="settingvalue">{{ $store.getters.getemail }}</div>
                <div class="settingaction">Изменить</div>
            </div>
            <div class="item">
                <div class="settingtitle">Пароль</div>
                <div class="settingvalue">********</div>
                <div class="settingaction">Изменить</div>
            </div>
            <div class="item">
                <div class="settingtitle">Пароль</div>
                <div class="settingvalue"><input type="text"></div>
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