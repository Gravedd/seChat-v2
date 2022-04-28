<template>
<div class="container">
    <div class="dialogueswrapper">
        <div class="leftpanel">
            <div class="userwrapper module">
                <router-link to="/dialogues/1">
                    <h3>@nickname</h3>
                    <div>Последнее сообщение</div>
                </router-link>
            </div>
            <div class="userwrapper module">
                <router-link to="/dialogues/2">
                    <h3>@nickname</h3>
                    <div>Последнее сообщение</div>
                </router-link>
            </div>
            <div class="userwrapper module">
                <router-link to="/dialogues/3">
                    <h3>@nickname</h3>
                    <div>Последнее сообщение</div>
                </router-link>
            </div>
        </div>
    </div>
</div>
</template>

<script>
import Iconbutton from "@/components/iconbutton";
import IndexView from "@/views/IndexView";
import store from "@/store";
export default {
    name: "DialoguesView",
    components: {IndexView, Iconbutton},
    methods: {
        async getDialogues(){
            let response = await fetch(store.getters.apiserver + 'dialogues', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json;charset=utf-8',
                    'Access-Control-Allow-Origin': '<origin>',
                    'Authorization': 'Bearer ' + store.getters.gettoken,
                },
            });
            let result = await response.json(); //ответ в json
            let code = await response.status; //код ответа
            switch (await code) {
                case 200: //Успешно
                    console.log(result);
                    break;
                default: //Другая ошибка
                    showalert('Ошибка!', 'Ошибка при загрузки списка диалогов: ' + code);
                    console.log(result);
                    break;
            }
        }
    },
    created() {
        this.getDialogues();

    }
}
</script>

<style scoped>
.container {
    min-height: auto;
}
.dialogueswrapper {
    display: flex;
    padding: 8px var(--padding);
    justify-content: space-between;
}
.leftpanel {
    width: 100%;
    height: auto;
}
.userwrapper {
    padding: 16px;
    transition: 0.5s;
    margin-bottom: 8px;
    width: 99%;
}
.userwrapper:hover {
    transform: scale(1.02);
    transition: 0.25s;
}
</style>