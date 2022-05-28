<template>
    <div class="container">
        <h1>Поиск пользователей</h1>
        <div class="searchcontainer">
            <div class="module">
                <form class="searchform">
                    <input type="text" maxlength="64" placeholder="Введите имя искомого пользователя..." v-model="searchinput" @input="restart">
                    <iconbutton title="Найти" nopadding="true" image="/icons/interface/search.svg" @click="getUsers"></iconbutton>
                </form>

            </div>
            <span v-show="searchnoresult" style="margin-left: 32px; font-family: LightFont">Ничего не найдено</span>
            <div class="searchresults">
                <router-link
                    class="module"
                    v-for="user in searchusers"
                    :to="{ name: 'profile', params: {id: user.id}}"
                >
                    <h3>{{ user.name }}</h3>
                    <div class="text">Статус: {{ user.status }}</div>
                </router-link>
                <iconbutton title="Найти" nopadding="true" image="/icons/interface/search.svg" v-if="more" @click="getmore">Загрузить еще</iconbutton>
            </div>
        </div>
    </div>
</template>

<script>
import Iconbutton from "@/components/iconbutton";
import store from "@/store";
export default {
    name: "SearchView",
    components: {Iconbutton},
    store: store,
    data() {
        return {
            searchusers: {},
            searchinput: '',
            currentpagenum: 1,
            lastpage: 1,
            more: false,
            searchnoresult: false,
        }
    },
    methods: {
        async getUsers (loadMore) {
            let response = await fetch(store.getters.apiserver + 'users?page=' + this.currentpagenum + '&' + 'q=' + this.searchinput,{
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json;charset=utf-8',
                    'Authorization': 'Bearer ' + store.getters.gettoken,
                }
            });
            let result = await response.json(); //ответ в json
            let code = await response.status; //код ответа
            switch (await code) {
                //Удачно
                case 200:
                    if (loadMore === true) {
                        console.log(result.data)
                        for (let user in result.data) {
                            this.searchusers.push(result.data[user]);
                        }
                    } else {
                        this.searchusers = result.data;
                    }
                    if (result.data.length === 0) {
                        this.searchnoresult = true;
                    }  else {
                        this.searchnoresult = false;
                    }
                    this.currentpagenum = result.current_page;
                    this.lastpage = result.last_page;
                    this.more = this.checkpages();
                    break;
                default:
                    console.log('Ошибка ' + code);
                    break;
            }
        },
        //Кнопка заргузить еще
        async getmore () {
            this.currentpagenum += 1;
            await this.getUsers(true);
        },
        checkpages() {
            if (this.currentpagenum === this.lastpage) {
                return false

            } else {
                return true;
            }
        },
        restart() {
            this.currentpagenum = 1;
            this.more = false;
            this.searchusers = {};
        }
    },
    created() {

    }
}
</script>

<style scoped>
.container {
}
a.module {
    display: block;
}
h1 {
    background-color: var(--main-color);
    text-align: center;
    padding: 16px;
    color: var(--white-color);
    margin-bottom: 8px;
}
.searchcontainer {
    padding: 8px var(--padding);
}
.searchcontainer .module {
    padding: 16px;
    margin-bottom: 24px;
}
.searchform {
    display: flex;
}
.searchform input {
    width: 90%;
    height: 50px;
    border-radius: 16px 0 0 16px;
    border: 2px solid var(--main-color);
    background-color: var(--gray4);
    padding: 16px;
    font-size: 12pt;
}
.searchform input:focus {
    outline: none;
    box-shadow: 0 0 3px 0 var(--main-color);
}
.searchform .btn {
    border-radius: 0 16px 16px 0;
    width: 100px;
    justify-content: center;
}
.searchform .btn:hover {
    transform: none;
}
.searchresults .module h3 {

}
.searchresults .module .text {
    font-family: LightFont;
}
@media (max-width: 540px) {
    h1 {
        font-size: 150%;
    }
}
@media (max-width: 410px) {
    h1 {
        font-size: 100%;
    }
}

</style>