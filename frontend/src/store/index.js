import { createStore } from 'vuex'
import router from "@/router";
export default createStore({
    //Состояния
    state: {
        apiserver: 'http://sechat.loc/api/',
        token: localStorage.getItem('token') || '',
            username: localStorage.getItem('username') || '',
            useremail: localStorage.getItem('useremail') || '',
            uid: localStorage.getItem('uid') || '',
        authstatus: false,
    },
    //Получение данных
    getters: {
        apiserver: (state) => {
            return state.apiserver;
        },
        gettoken: (state) => {
            return state.token;
        },
        authstatus: (state) => {
            return state.authstatus;
        }
    },
    mutations: {
            //Чтобы вызвать мутацию нужно store.commit('имя_мутации', [дополнительные параметры])
        //Статус авторизации
        authstatus (state, value) {
            state.authstatus = value;
        },
        authsuccess (state, user) {
            console.log(user.uid, user.name, user.email, user.token);
            state.authstatus = true;
            state.token = user.token;
                localStorage.setItem('token', user.token);
            state.username = user.name;
                localStorage.setItem('username', user.name);
            state.useremail = user.email;
                localStorage.setItem('email', user.email);
            state.uid = user.uid;
                localStorage.setItem('uid', user.uid);
        },
        authchecknotsuccess(state) {
            state.authstatus = false;
            state.token = null;
                localStorage.removeItem('token');
            state.username = null;
                localStorage.removeItem('email');
            state.useremail = null;
                localStorage.removeItem('username');
            state.uid = null;
                localStorage.removeItem('uid');
        }
    },
    actions: {
    //запрос на авторизацию(логин)
        async LOGIN_REQUEST (context, formdata) {
            //Отправляем запрос на сервер
            let response = await fetch(context.getters.apiserver + 'login', {
                method: 'POST',
                headers: {
                    'Accept' : 'application/json','Content-Type': 'application/json;charset=utf-8','Access-Control-Allow-Origin': '<origin>',
                },
                //Указываем что оправляем
                body: JSON.stringify({
                    'email': formdata.email,
                    'password': formdata.password,
                })
            });
            //Ждем ответа
            let result = await response.json(); //ответ в json
            let code = await response.status; //код ответа
            //Проверяем, была ли успешна авторизация
                switch (await code) {
                    //Удачная авторизация
                    case 201:
                        context.commit('authsuccess', {'uid': result.uid, 'name': result.name, 'email': result.email, 'token': result.token});
                            //Переход к профилю
                        router.push('/myprofile/');
                        break;
                    //Не корректные данные
                    case 422:
                        context.commit('authstatus', false);
                        showalert('Ошибка', 'Не корректные данные');
                        break;
                    //Неверные учетные данные
                    case 401:
                        context.commit('authstatus', false);
                        showalert('Ошибка', 'Неверные учетные данные');
                        break;
                    //Другие ошибки
                    default:
                        showalert('Ошибка', 'Ошибка ' + code);
                            console.log('Ошибка ' + code);
                            console.log(result);
                        break;
                }
        },
        async CHECKLOGIN (context) {
            if (!context.getters.gettoken) {
                console.log('не авторизован');
                return context.commit('authstatus', false);
            }
            //Отправляем запрос на сервер
            let response = await fetch(context.getters.apiserver + 'checkauth', {
                method: 'GET',
                headers: {
                    'Accept' : 'application/json','Content-Type': 'application/json;charset=utf-8','Access-Control-Allow-Origin': '<origin>',
                    'Authorization' : 'Bearer ' + context.getters.gettoken,
                },
            });
            let result = await response.json(); //ответ в json
            let code = await response.status; //код ответа
            console.log(result);
            switch (await code) {
                //Удачная авторизация
                case 200:
                    context.commit('authsuccess', {'uid': result.id, 'name': result.name, 'email': result.email, 'token': context.getters.gettoken});
                    break;
                //Не удачная проверка токена
                default:
                    context.commit('authchecknotsuccess', false);
                    router.push('/login/');
                    console.log('Не удачная проверка токена. Код ошибки: ' + code);
                    break;
            }
        }

    },
    modules: {
    }
})