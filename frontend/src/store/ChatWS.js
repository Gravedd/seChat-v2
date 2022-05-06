import store from '@/store';
import router from '@/router';
export default {
    state: {
        messages: {},
        typing: {},
        timeid: 0,
    },
    getters: {
        getMessagess: (state) => state.messages,
        getTyping (state) {
            return state.typing;
        }
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
        },
        setTyping: (state, data) => {
            console.log(data)
            state.typing[data.sender_id] = data.status;
            console.log(state.typing[data.sender_id])
        },
        createTyping: (state, user_id) => {
            state.typing[user_id] = false;
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
        typing(context, data) {
            let send = JSON.stringify({
                'type': 'typing',
                'receiver_id': data.receiver_id
            })
            store.state.ws.websocket.send(send);
        },
        addTyping(context, data) {
            clearTimeout(context.state.timeid);
            context.commit('setTyping', {'sender_id': data.sender_id, 'status': true});
            context.state.timeid = setTimeout(store.dispatch, 1500, 'removeTypingStatusCHAT', data.sender_id);
        },
        removeTypingStatusCHAT(context, sender_id) {
            context.commit('setTyping', {'sender_id': sender_id, 'status': false });
        }

    }
}