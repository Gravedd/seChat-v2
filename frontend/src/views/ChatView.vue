<template>
<div class="container">
    <div class="dialogueswrapper">
        <div class="rightpanel module">
            <div class="chatwrapper">
                <div class="chatheader">
                    <h2 class="chatheaderh2"><router-link to="/dialogues/"><iconbutton image="/icons/interface/back.svg" title="Назад" nopadding="true"/></router-link>@nickname</h2>
                    <input type="text" maxlength="64" placeholder="Ключ... Сообщения не шифруются если поле пустое" title="Введите ключ. Если поле пустое то сообщения не шифруются и недешифрируются">
                    <div class="keywrapper">
                        <iconbutton image="/icons/interface/save.svg" title="Сохранить ключ" nopadding="true"></iconbutton>
                        <iconbutton image="/icons/interface/show.svg" title="Показать/скрыть ключ" nopadding="true"></iconbutton>
                    <!--<iconbutton image="/icons/interface/hidden.svg" title="Показать/скрыть ключ" nopadding="true"></iconbutton>-->
                        <iconbutton image="/icons/interface/remove.svg" title="Удалить ключ" nopadding="true"></iconbutton>
                    </div>
                </div>
                <div class="chatcontainer" id="msgwrapper" v-on:scroll="scrollmess">
                    <div class="messwrapper" v-for="message in messages" :class="{'sent': message.sender_id === $store.getters.getuid }" :title="message.id">
                        <div class="message">{{ message.message }}</div>
                        <div class="time">{{ message.created_at }}</div>
                    </div>
                    <div style="text-align: center;" v-if="!messages[0]"><b>Cообщений нет</b></div>
                </div>
                <div class="chatinputwrapper">
                    <input type="text" maxlength="512" name="message" placeholder="Сообщение..." class="inputmess" autocomplete="off">
                    <iconbutton image="/icons/interface/send.svg" title="Отправить сообщение" nopadding="true" class="sendbtn"></iconbutton>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
import Iconbutton from "@/components/iconbutton";
import IndexView from "@/views/IndexView";
import store from "@/store";
export default {
    name: "DialoguesView",
    components: {IndexView, Iconbutton},
    data() {
        return {
            currentpagenum: 0,
            lastpage: 2,
            messages: [],
        }
    },
    store: store,
    props: ['userid'],
    methods: {
        async getmessages() {
            if (this.currentpagenum <= this.lastpage) {
                this.currentpagenum ++;
                //Запрос на серврер получение сообщений с пользователем (по 30 шт) и номер страницы(пагинация)
                let response = await fetch(store.getters.apiserver + 'dialogues/' + this.userid + '?page=' + this.currentpagenum, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json;charset=utf-8',
                        'Access-Control-Allow-Origin': '<origin>',
                        'Authorization': 'Bearer ' + store.getters.gettoken,
                    },
                });
                let result = await response.json(); //ответ в json
                let code = await response.status; //код ответа
                switch (await code) {
                    //Успешно
                    case 200:
                        this.lastpage = result.last_page;//устанавливаем значение последней страницы
                        result.data.entries(result.data);//конвертация в массив

                        //Сохраняем сообщения в массив
                        if (this.messages.length > 0) {//проверка, были ли получены сообщения или нет
                            //запись в массив сообщений
                            for (let message in result.data) {
                                this.messages.unshift(result.data[message]);
                            }
                        } else {
                            //первичное добавление сообщений
                            this.messages = this.messages.concat(result.data.reverse());
                            //Изменяем положение scroll у блока
                            setTimeout(this.scrolldown, 200);
                            setTimeout(this.scrollevent, 200);
                        }
                        this.currentpagenum = result.current_page;//следующий запрос - следущая страница
                        break;
                    //Ошибка
                    default:
                        showalert('Ошибка!', 'Ошибка при загрузки сообщений: ' + code);
                        break;
                }
            } else {
                showalert('Успешно', 'Достигнут конец диалога');
            }
        },
        async scrolldown() {
            let msgwrapper = document.getElementById('msgwrapper');
            msgwrapper.scrollTop = msgwrapper.scrollHeight;
        },
        scrollmess(event) {
            if (event.srcElement.scrollTop === 0) {
                this.getmessages();
            }
        },
    },
    async created() {
        await this.getmessages();
    }
}
</script>

<style scoped>
.dialogueswrapper {
    display: flex;
    height: 841px;
    padding: 8px var(--padding);
    justify-content: space-between;
}
.chatheaderh2 {
    display: flex;

}
.rightpanel{
    width: 100%;
}
.chatheader {
    display: flex;
    height: 50px;
    align-items: center;
    padding: 8px 8px;
    justify-content: center;
}
.chatheader h2{
    display: inline-block;
}
.keywrapper {
    display: flex;
}
.chatheader input {
    margin: 0 8px;
    border-radius: 8px;
    border: 1px solid var(--black-color);
    background-color: var(--lighter-color);
    min-width: 50%;
    height: 41px;
    display: block;
}
.chatcontainer {
    width: 100%;
    height: 700px;
    padding: 16px;
    font-family: "LightFont";
    overflow-y: scroll;
    overflow-x: hidden;
    border-top: 2px solid var(--main-color);
    border-bottom: 2px solid var(--main-color);
}
/* полоса прокрутки (скроллбар) */
.chatcontainer::-webkit-scrollbar {
    width: 8px; /* ширина для вертикального скролла */
    height: 8px; /* высота для горизонтального скролла */
    background-color: var(--gray1);
}
.chatcontainer::-webkit-scrollbar-thumb {
    height: 40px;
    box-shadow: inset 1px 1px 10px var(--gray2);
}



.message {
    padding: 12px;
    background-color: var(--gray3);
    border-radius: 8px;
    box-shadow: 0 0 5px 0 rgba(0, 0, 0, 0.15);
}
.sent {
    margin-left: auto;

}
.sent .message {
    background-color: var(--lighter-color);
}
.sent .time {
    margin-left: auto;
    text-align: end;
}
.time {
    letter-spacing: 1px;
    font-size: 12px;
    padding-left: 12px;
}
.messwrapper {
    margin-bottom: 8px;
    transition: 0.15s;
    width: 70%;
}
.messwrapper:hover {
    transform: scale(1.01);
}

.chatinputwrapper {
    width: 100%;
    background-color: var(--gray4);
    height: 75px;
    display: flex;
    justify-content: center;
    align-items: center;
    border-radius: 0 0 20px 20px;
}
.inputmess {
    height: 50px;
    max-height: 75px;
    background-color: var(--gray3);
    border-radius: 20px;
    border: 2px solid var(--main-color);
    padding: 8px;
    width: 80%;
    transition: 0.3s;
    font-size: 14pt;
}
.sendbtn {
    height: 50px;
    margin: 0 8px;
    width: 15%;
    justify-content: center;
}
.inputmess:focus {
    outline: none;
    box-shadow: 0 0 5px 0 var(--main-color);
}
@media (max-width: 960px) {
    .rightpanel {
        width: 100%;
    }
}
@media (max-width: 720px) {
    .dialogueswrapper {
        height: auto;
    }
    .chatcontainer {
        max-height: 70vh;
    }
}
@media (max-width: 700px) {
    .chatheader {
        height: 100px;
        max-height: 100px;
        flex-wrap: wrap;
        justify-content: center;
    }
    .chatheader h2{
        width: 100%;
        text-align: center;
        margin-bottom: 4px;
    }
    .chatheader input {
        width: 50%;
    }
    .keywrapper {
        width: 39%;
    }
}
@media (max-width: 546px) {
    .chatheader {
        padding: 8px;
    }
    .chatheader input {
        width: 50%;
        align-self: flex-start;
    }
    .chatheader .keywrapper {
        width: 40%;
    }
    .chatheader {
        justify-content: flex-start;
    }
    .messwrapper {
        width: 90%;
    }
}
@media (max-width: 381px) {

    .chatheader input {
        margin: 0 4px 0 0;
    }
}
@media (max-width: 340px) {
    .chatheader input {
        width: 40%;
    }
}
</style>