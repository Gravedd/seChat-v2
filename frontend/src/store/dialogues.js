import store from '@/store';
export default {
    state: {
        dialogues: {},
        timingid: 0,
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
        setTypingStatus: (state, value) => {
            state.dialogues[value.key]['typing'] = value.status;
        }
    },
    actions: {
        async RequestForDialoguesList() {
            store.dispatch('sendWs', JSON.stringify({
                'type': 'getdialogues'
            }));
        },
        async typingstatus(context, value) {
            for (let key in context.state.dialogues) {
                if (context.state.dialogues[key].user_id === value.sender_id || context.state.dialogues[key].user2_id === value.sender_id) {
                    store.commit('setTypingStatus', {'key': key, "status": true})
                    clearTimeout(context.state.timingid);
                    context.state.timingid = setTimeout(store.dispatch, 1500, 'removeTypingStatus', key);
                }
            }
        },
        async removeTypingStatus(context, key) {
            store.commit('setTypingStatus', {'key': key, status: false})
        }
    }
}