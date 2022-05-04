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
                    <div v-show="!dialogue.typing">Был онлайн: {{ dialogue.user_id === $store.getters.getuid ? dialogue['user2'].updated_at : dialogue['user1'].updated_at }}</div>
                    <div v-show="dialogue.typing" class="animpulse">Набирает сообщение...</div>
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
            return store.getters.getDialogues;
        }
    },
    methods: {
        updateList(){
            store.dispatch('RequestForDialoguesList');
        },
        removeTypingStatus(key) {
            store.commit('setTypingStatus', {'key': key, status: false})
        }
    },
    async created() {
        this.updateList();
        store.getters.websocket.onmessage = e => {
            let req = JSON.parse(e.data);
            if (req.type === "typing") {
                for (let key in this.dialogues) {
                    if (this.dialogues[key].user_id == req.sender_id || this.dialogues[key].user2_id == req.sender_id) {
                        store.commit('setTypingStatus', {'key': key, "status": true})
                        setTimeout(this.removeTypingStatus, 1500, key);
                    }
                }

            }
        }
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
.animpulse {
    animation: ease-in-out 1s animationpulse infinite;
}
@keyframes animationpulse {
    50% {
        opacity: 0.1;
    }
    100% {
        opacity: 1;
    }
}
</style>