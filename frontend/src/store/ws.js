import ChatWS from "@/store/ChatWS";
import dialogues from "@/store/dialogues";
import router from "@/router";
export default {
    state: {
        websocketsurl: 'ws://sechat.loc:6001',
        websocket: null,

    },
    getters: {
        websocketsurl: (state) => {
            return state.websocketsurl;
        },
        websocket: (state) => {
            return state.websocket;
        }
    },
    mutations: {


    },
    actions:{
        async connectws(context) {
            context.state.websocket = new WebSocket(context.getters.websocketsurl);
            let connectioncount = 0;
            context.state.websocket.onerror = function (error) {
                    location.reload()
            }
            context.state.websocket.onmessage = event => {
                let response = JSON.parse(event.data);
                console.log(response)
                switch (response.type) {

                    case 'sendauthtoken':
                        context.dispatch('sendtoken');
                        break;

                    case 'getmessages':
                        context.dispatch('reciveMessages', {'user_id': response.user_id, 'messages': response.messages})
                        break;

                    case 'newmessage':
                        if (router.currentRoute._value.name !== 'chat') {
                            let from = 'От пользователя с айди: ' + response.sender_id;
                            context.commit('addNotification', {'headertext': 'Новое сообщение', 'contenttext': from})
                        } else {
                            context.dispatch('newMessage', response);
                        }
                        break;
                    case 'dialogueslist':
                        console.log(response)
                        context.commit('changeDialogues', response);
                        break;
                    case 'typing':
                        if (router.currentRoute._value.name === 'chat') {
                            context.dispatch('addTyping', response);
                        }
                        if (router.currentRoute._value.name === 'dialogues'){
                            console.log('d')
                            context.dispatch('typingstatus', response);
                        }
                        break;
                    default:
                        console.log('полученно сообщение');
                        console.log(response);
                        break;
                }

            }
        },
        async sendtoken(context) {
            let jsontoken = JSON.stringify({
                'token': context.getters.gettoken,
                'type': 'auth'
            });
            context.state.websocket.send(jsontoken);
        }
    },
    modules: {
        ChatWS, dialogues
    }
}