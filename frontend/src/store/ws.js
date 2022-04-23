
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
                if (response.message === "sendauthtoken") {
                    let token = context.getters.gettoken;
                    context.state.websocket.send(token);
                }

            }
        },
        async sendtoken(context) {
            let jsontoken = JSON.stringify({
                'token': context.getters.gettoken,
            });
            context.state.websocket.send(jsontoken);
        }

    }
}