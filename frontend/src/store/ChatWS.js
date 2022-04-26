import store from '@/store';
export default {
    state: {
        messages: {},
    },
    getters: {
        getMessagess: (state) => {
            return state.messages;
        }
    },
    mutations: {
        storeMessages: (state, value) => {
            state.messages['dialog' + value.user_id] = value.messages;
        }
    },
    actions: {
        async requestForMessages(context, id) {
            let jsonSend = {
                'type' : 'getmessages',
                'userid' : id,
            }
            store.state.ws.websocket.send(JSON.stringify(jsonSend));
        },
        async reciveMessages(context, request) {
            console.log(request.messages.reverse());
            this.commit('storeMessages', request);
        },
        getMessagessInDialog(context, id){
            console.log(context)
            return context.state.messages['dialog' + id];
        }
    }
}