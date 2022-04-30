<template>
<div class="container">
    <div class="dialogueswrapper">
        <div class="leftpanel">
            <h3 title="Нажатие обновляет этот список" @click="updateList()">Список диалогов</h3>
            <div class="userwrapper module"
                 v-for="dialogue in dialogues"
            >
                <router-link :to="{ name: 'chat', params: { userid: dialogue.user_id === $store.getters.getuid ? dialogue['user2'].id : dialogue['user1'].id } }">
                    <h3>{{ dialogue.user_id === $store.getters.getuid ? dialogue['user2'].name : dialogue['user1'].name }}</h3>
                    <div>Был онлайн: {{ dialogue.user_id === $store.getters.getuid ? dialogue['user2'].updated_at : dialogue['user1'].updated_at }}</div>
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
    store: store,
    computed: {
        dialogues() {
            if (sessionStorage.getItem('dialoguesjson')) {
                return JSON.parse(sessionStorage.getItem('dialoguesjson'))
            }
            return store.getters.getDialogues;
        }
    },
    methods: {
        updateList(){
            store.dispatch('RequestForDialoguesList');
        }
    },
    created() {
        this.updateList();
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
h3 {
    margin-bottom: 8px;
}
</style>