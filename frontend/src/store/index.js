import { createStore } from 'vuex'
import router from "@/router";
import ws from "@/store/ws";
import Notifications from "@/store/Notifications";
import device from "@/store/device";

export default createStore({
    //Состояния
    state: {
        apiserver: 'http://sechat.loc/api/',
        token: localStorage.getItem('token') || '',
            username: localStorage.getItem('username') || '',
            useremail: localStorage.getItem('useremail') || '',
            uid: localStorage.getItem('uid') || '',
            userstatus: localStorage.getItem('userstatus') || '',
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
        },
        getname: (state) => {
            return state.username;
        },
        getemail: (state) => {
            return state.useremail;
        },
        getuid: (state) => {
            return state.uid;
        },
        getstatus: state => {
            return state.userstatus;
        }

    },
    mutations: {
            //Чтобы вызвать мутацию нужно store.commit('имя_мутации', [дополнительные параметры])
        //Статус авторизации
        authstatus (state, value) {
            state.authstatus = value;
        },
        authsuccess (state, user) {
            state.authstatus = true;

            state.token = user.token;
                localStorage.setItem('token', user.token);

            state.username = user.name;
                localStorage.setItem('username', user.name);

            state.useremail = user.email;
                localStorage.setItem('email', user.email);

            state.uid = user.uid;
                localStorage.setItem('uid', user.uid);

            state.userstatus = user['status'];
                localStorage.setItem('status', user.status);
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
            state.userstatus = null;
                localStorage.removeItem('status');
        },
        changename(state, newname) {
            state.username = newname;
            localStorage.setItem('username', newname);
        },
        changestatus(state, newstatus) {
            state.userstatus = newstatus;
            localStorage.setItem('status', newstatus);
        },
        changeemail(state, newemail) {
            state.useremail = newemail;
            localStorage.setItem('email', newemail);
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
                        context.commit('authsuccess', {'uid': result.uid, 'name': result.name, 'email': result.email, 'token': result.token, 'status': result['status']});
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
                console.log('Не авторизован. Нет сохранненого токена');
                return context.commit('authstatus', false);
            }
            context.commit('authstatus', 'waiting');//до ответа сервера
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
            switch (await code) {
                //Удачная авторизация
                case 200:
                    context.commit('authsuccess', {'uid': result.id, 'name': result.name, 'email': result.email, 'token': context.getters.gettoken, 'status': result['status']});
                    break;
                //Не удачная проверка токена
                default:
                    context.commit('authchecknotsuccess');
                    router.push('/login/');
                    console.log('Не удачная проверка токена. Код ошибки: ' + code);
                    break;
            }
        },
        async REGISTER(context, formdata) {
            //Отправляем запрос на сервер
            let response = await fetch(context.getters.apiserver + 'register', {
                method: 'POST',
                headers: {
                    'Accept' : 'application/json','Content-Type': 'application/json;charset=utf-8','Access-Control-Allow-Origin': '<origin>',
                },
                //Указываем что оправляем
                body: JSON.stringify({
                    'name' : formdata.name,
                    'email' : formdata.email,
                    'password' : formdata.password,
                    'password_confirmation' : formdata.password_confirmation,
                })
            });
            let result = await response.json(); //ответ в json
            let code = await response.status; //код ответа
            switch (await code) {
                case 201:
                    context.commit('authsuccess', {'uid': result.uid, 'name': result.name, 'email': result.email, 'token': result.token, 'status': result.status});
                    router.push('/myprofile/');
                    break;
                case 422:
                    context.commit('authstatus', false);
                    for (let key in result.errors) {
                        showalert('Поле '+key, result.errors[key][0]);
                    }
                    break;
                default:
                    showalert('Ошибка', 'Ошибка:' + code);
                    break;
            }

        },
        //Выход из аккаунта
        async LOGOUT(context) {
            //Запрос на сервер на удаление всех токенов
            let response = await fetch(context.getters.apiserver + 'logout', {
                method: 'POST',
                headers: {
                    'Accept' : 'application/json','Content-Type': 'application/json;charset=utf-8','Access-Control-Allow-Origin': '<origin>',
                    'Authorization' : 'Bearer ' + context.getters.gettoken,
                },
            });
            let result = await response.json(); //ответ в json
            let code = await response.status; //код ответа
            //Если статус 201 - все ок, иначе ошибка
            switch (await code) {
                //Удачная авторизация
                case 201:
                    context.commit('authchecknotsuccess');
                    showalert('Успешно', 'Вы вышли из своего аккаунта');
                    router.push('/');
                    break;
                //Не удачная проверка токена
                default:
                    context.commit('authchecknotsuccess');
                    showalert('Ошибка' + code, 'Не известная ошибка');
                    router.push('/');
                    break;
            }
        }

    },
    modules: {
        ws, Notifications, device
    }
})