<template>
<div class="container">
    <div class="dialogueswrapper">
        <div class="rightpanel module">
            <div class="chatwrapper">


                <div class="chatheader">
                    <h2 class="chatheaderh2">
                        <div class="chatheadername">
                            <router-link to="/dialogues/">
                                <iconbutton image="/icons/interface/back.svg" title="Назад" nopadding="true"
                                            nobackground="true"/>
                            </router-link>
                            {{ userWidth > 1390 ? name : (userWidth < 698 ? name : name.substr(0, 8) + '...') }}
                            <iconbutton
                                title="Дополнительные настройки"
                                image="/icons/interface/extra.svg"
                                nopadding="true" nobackground="true"
                                @click="showExtraMenu"
                            />
                        </div>
                    </h2>
                    <div class="keywrapper" v-show="showExtras">
                        <input v-show="!showkey" type="password" v-model="skey" maxlength="64" placeholder="Ключ... Сообщения не шифруются если поле пустое" title="Введите ключ. Если поле пустое то сообщения не шифруются и недешифрируются">
                        <input v-show="showkey" type="text" v-model="skey" maxlength="64" placeholder="Ключ... Сообщения не шифруются если поле пустое" title="Введите ключ. Если поле пустое то сообщения не шифруются и недешифрируются">
                        <div class="keys">
                            <iconbutton image="/icons/interface/save.svg" title="Сохранить ключ" nopadding="true" @click="saveKey"></iconbutton>
                            <iconbutton v-show="!showkey" image="/icons/interface/show.svg" title="Показать/скрыть ключ" nopadding="true" @click="keyVisibilityToggle"></iconbutton>
                            <iconbutton v-show="showkey" image="/icons/interface/hidden.svg" title="Показать/скрыть ключ" nopadding="true" @click="keyVisibilityToggle"></iconbutton>
                            <iconbutton image="/icons/interface/remove.svg" title="Удалить ключ" nopadding="true" @click="deleteKey"></iconbutton>
                        </div>
                    </div>

                </div>


                <div class="chatcontainer" id="msgwrapper" v-on:scroll="scrollmess" :style="{'height': userHeight - (50+50+60 + (showExtras ? 140 : 10)) + 'px'}">
                    <div v-if="messages" class="messwrapper" v-for="message in messages['dialog' + userid]" :class="{'sent': message.sender_id === $store.getters.getuid }" :title="message.id">
                        <div class="message">{{ encryptMessage(skey, message.message) }} </div>
                        <div class="time">{{ message.created_at }}</div>
                    </div>
                    <div style="text-align: center;" v-if="!messages['dialog' + userid]"><b>Cообщений нет</b></div>
                    <div class="typing" v-show="typing">Набирает сообщение...</div>
                </div>
                <div class="chatinputwrapper">
                    <input type="text" maxlength="512" name="message" placeholder="Сообщение..." class="inputmess" autocomplete="off" v-model="inputmessage" @keypress.enter="sendMessage">
                    <iconbutton image="/icons/interface/send.svg" title="Отправить сообщение" nopadding="true" nobackground="true" class="sendbtn" @click="sendMessage"></iconbutton>
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
    components: {IndexView, Iconbutton},
    data() {
        return {
            inputmessage: '',
            dialogkey: 'dialog' + this.userid,
            delay: 0,
            showExtras: false,
            name: null,
            skey: localStorage.getItem('keyid' + this.userid) || '',
            showkey: false,
        }
    },
    computed: {
        messages() {
            return store.getters.getMessagess;
        },
        typing() {
            return store.getters.getTyping[this.userid];
        },
        userWidth() {
            return store.getters.getClientWidth;
        },
        userHeight() {
            return store.getters.getClientHeight;
        },
    },
    store: store,
    props: ['userid'],
    watch: {
        inputmessage(){
            if (this.delay === 0) {
                this.setDelay();
                store.dispatch('typing', {receiver_id: this.userid});
            }
        }
    },
    methods: {
        async getProfile() {
            let savedinfo = JSON.parse(sessionStorage.getItem('user' + this.userid));
            if (savedinfo) {
                return this.name = savedinfo.name;
            } else {
                let response = await fetch(store.getters.apiserver + 'users/' + this.userid,{
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json;charset=utf-8',
                        'Authorization': 'Bearer ' + store.getters.gettoken,
                    }
                });
                let result = await response.json();
                let code = await response.status;
                switch (await code) {
                    case 200:
                        console.log(JSON.stringify(result[0]));
                        sessionStorage.setItem('user' + result[0].id, JSON.stringify(result[0]));
                        this.name = result[0].name;
                        break;
                    default:
                        showalert('Ошибка', 'Не удачное получение данных');
                        break;
                }

            }

        },
        showExtraMenu() {
            this.showExtras = !this.showExtras;
        },
        async getmessages() {
            store.dispatch('requestForMessages', this.userid);
            setTimeout(this.scrolldown, 200);
        },
        scrolldown() {
            let msgwrapper = document.getElementById('msgwrapper');
            msgwrapper.scrollTop = msgwrapper.scrollHeight;
        },
        async sendMessage() {
            let msg = this.encryptMessage(this.skey, this.inputmessage)
            msg = msg.trim();
            if (msg.length > 0) {
                store.dispatch('sendMessage', {'user_id': this.userid, 'messagetext': msg});
                let date = new Date();
                let datenow = date.getFullYear() + '-' + date.getMonth() + '-' + date.getDate()  + ' ' + date.getHours()  + ':' + date.getMinutes() + ':' + date.getSeconds();
                let data = {
                    id: 1,
                    receiver_id: this.userid,
                    sender_id: store.getters.getuid,
                    message: msg,
                    readed: 0,
                    created_at: datenow,
                    updated_at: datenow,
                }
                store.commit('addMessage', data);
                this.inputmessage = '';
                setTimeout(this.scrolldown, 80);
            }
        },
        async setDelay() {
            this.delay = 1;
            setTimeout(this.dropDelay, 1500);
        },
        async dropDelay() {
            this.delay = 0;
        },
        encryptMessage(skey, message) {
            let output = '';
            let letter;
            let key;
            for (let i = 0; i < message.length; i++) {
                // берём цифровое значение очередного символа в сообщении и ключе
                letter = message.charCodeAt(i);
                key = skey.charCodeAt(i);
                // и применяем к ним исключающее или — XOR
                output += String.fromCharCode(letter ^ key);
            }
            return output;
        },
        saveKey() {
            localStorage.setItem('keyid' + this.userid, this.skey);
            showalert('Успешно!', 'Ключ сохранен на вашем устройстве');
        },
        deleteKey() {
            localStorage.removeItem('keyid' + this.userid);
            showalert('Успешнo', 'Ключ удален');
        },
        keyVisibilityToggle() {
            return this.showkey = !this.showkey;
        }

    },
    async created() {
        await this.getmessages();
        await this.getProfile();

    },
}
</script>

<style scoped>
.dialogueswrapper {
    display: flex;
    padding: 8px var(--padding);
    justify-content: space-between;
    overflow: hidden;

}
.chatheaderh2 {
    display: flex;
    flex-direction: row;
    justify-content: center;
}
.rightpanel{
    width: 100%;
}
.chatheader {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 8px 8px;
    justify-content: center;
}
.keywrapper {
    display: flex;
    flex-wrap: wrap;
    width: 100%;
    justify-content: center;
    align-items: center;
    height: 1px;
    overflow: hidden;
    animation: ease-out 0.5s animExtramenu forwards;
}
.keywrapper input {
    width: 70%;
    height: 41px;
    display: block;
    border-radius: 8px;
    background-color: var(--gray3);
    border: none;
    margin-right: 8px;
    padding-left: 16px;
}
.chatheadername {
    display: flex;
    align-items: center;
    align-self: center;
    justify-self: center;
}
.chatcontainer {
    width: 100%;
    padding: 16px;
    font-family: "LightFont";
    overflow-y: scroll;
    overflow-x: hidden;
    border-top: 2px solid var(--main-color);
    border-bottom: 2px solid var(--main-color);
    background-color: #f4f4f4;
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
    box-shadow: 0 0 5px 0 rgba(0, 0, 0, 0.05);
    word-wrap: break-word;
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
    padding: 4px;
}
.inputmess {
    height: 50px;
    max-height: 75px;
    background-color: var(--gray3);
    border-radius: 20px;
    border: none;
    padding: 16px;
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
    box-shadow: 0 0 3px 0 var(--shadow-color);
}
.typing {
    animation: ease-in-out 1s animationpulse infinite;
}
@keyframes animationpulse {
    50% {
        opacity: 0.1;
    }
    100% {
        opacity: 1;
    }
}
@keyframes animExtramenu {
    0% {
        height: 0;
    }
    100% {
        height: 43px;
    }
}
@media (max-width: 1500px) {
    .dialogueswrapper{
        padding: 8px 150px;
    }
}
@media (max-width: 960px) {
    .rightpanel {
        width: 100%;
    }
    .dialogueswrapper{
        padding: 8px 32px;
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
        flex-wrap: wrap;
        justify-content: center;
    }
    .chatheader h2{
        width: 100%;
        text-align: center;
        margin-bottom: 4px;
    }
}
@media (max-width: 596px) {
    .keywrapper input {
        width: 100%;
        margin-bottom: 4px;
    }
    .chatheader {
        padding: 4px;
    }
    .dialogueswrapper {
        padding: 8px 8px;
    }
    @keyframes animExtramenu {
        0% {
            height: 0;
        }
        100% {
            height: 86px;
        }
    }
}
@media (max-width: 546px) {
    .chatheader {
        padding: 8px;
    }
    .chatheader {
        justify-content: flex-start;
    }
    .messwrapper {
        width: 90%;
    }
}
@media (max-width: 442px) {
    .chatheaderh2 {
        overflow: hidden;
    }
}
</style>