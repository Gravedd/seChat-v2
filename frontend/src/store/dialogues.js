import store from '@/store';
export default {
    state: {
        dialogues: JSON.parse(sessionStorage.getItem('dialoguesjson')) || {},
    },
    getters: {
        getDialogues: (state) => state.dialogues,
    },
    mutations: {
        changeDialogues: (state, value) => {
            sessionStorage.setItem('dialoguesjson', JSON.stringify(value.dialogues));
            state.dialogues = value.dialogues;
            for (let i = 0; i < value.dialogues.length; i++) {
                if (value.dialogues[i].user1) {
                    if (value.dialogues[i].user1.id !== store.getters.getuid) {
                        sessionStorage.setItem('user' + value.dialogues[i].user1.id, JSON.stringify(value.dialogues[i].user1));
                    }
                }
                if (value.dialogues[i].user2) {
                    if (value.dialogues[i].user2.id !== store.getters.getuid) {
                        sessionStorage.setItem('user' + value.dialogues[i].user2.id, JSON.stringify(value.dialogues[i].user2));
                    }
                }
            }
        },
    },
    actions: {
        async RequestForDialoguesList() {
            store.getters.websocket.send(JSON.stringify({
                'type': 'getdialogues'
            }));
        },
    }
}