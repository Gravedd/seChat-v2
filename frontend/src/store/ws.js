import ChatWS from "@/store/ChatWS";
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
            context.state.websocket.onerror = function (error) {
                showalert('Ошибка!', 'Не удачное подключение к websockets');
            }
            context.state.websocket.onmessage = event => {
                let response = JSON.parse(event.data);
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
        ChatWS
    }
}