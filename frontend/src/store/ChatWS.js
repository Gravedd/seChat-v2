import store from '@/store';
export default {
    state: {
        messages: {},
    },
    getters: {
        getMessagess: (state) => state.messages,

    },
    mutations: {
        storeMessages: (state, value) => {
            state.messages['dialog' + value.user_id] = value.messages;
        },
        addMessage: (state, data) => {
            let key = 'dialog' + data.receiver_id;
            state.messages[key].push(data);
        },
        addReceivedMessage: (state, data) => {
            let key = 'dialog' + data.sender_id;
            state.messages[key].push(data);
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
            request.messages.reverse();
            this.commit('storeMessages', request);
        },
        async sendMessage(context, data) {
            let send = JSON.stringify({
                'type': 'message',
                'to': data.user_id,
                'messagetext': data.messagetext,
            });
            store.state.ws.websocket.send(send);
        },
        async newMessage(context, data) {
            context.commit('addReceivedMessage', data);
            setTimeout(scrolldown, 100);
            function scrolldown() {
                let msgwrapper = document.getElementById('msgwrapper');
                msgwrapper.scrollTop = msgwrapper.scrollHeight;
            }
        },
    }
}