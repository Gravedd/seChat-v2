import { createStore } from 'vuex'

export default createStore({
    //Состояния
    state: {
        apiserver: 'http://sechat.loc/api/',
        token: localStorage.getItem('token') || '',
            username: localStorage.getItem('username') || '',
            useremail: localStorage.getItem('useremail') || '',
        authstatus: false,
    },
    //Получение данных
    getters: {
        apiserver: (state) => {
            return state.apiserver;
        }
    },
    mutations: {
            //Чтобы вызвать мутацию нужно store.commit('имя_мутации', [дополнительные параметры])
        //Статус авторизации
        authstatus (state, value) {
            state.authstatus = value;
        },
        authsuccess (state, token, name, email) {
            state.authstatus = true;
            state.token = token;
                localStorage.setItem('token', token);
            state.username = name;
                localStorage.setItem('token', name);
            state.useremail = email;
                localStorage.setItem('token', email);
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
                        context.commit('authsuccess', result.token, result.name, result.email);
                            //Переход к профилю
                        this.$router.push('/myprofile/');
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
        }



    },
    modules: {
    }
})