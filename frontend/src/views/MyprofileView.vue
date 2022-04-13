<template>
    <div class="container">
        <div class="profileheaderwrapper">
            <div class="profileheader">
                <div class="avatarwrapper">
                    <div class="avatarimg" style="background-image: url(/icons/user.svg);"></div>
                </div>
                <div class="userinfo">
                    <div class="userinfowrapper">
                        <h3>{{ $store.getters.getname }}</h3>
                        <span class="status">user status here...</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="useractions">
            <iconbutton @click="openfriends" image="/icons/interface/friends.svg">Мои друзья</iconbutton>
            <router-link to="/dialogues">
                <iconbutton image="/icons/interface/chats.svg">Диалоги</iconbutton>
            </router-link>
            <iconbutton @click="opensettings" image="/icons/interface/settings.svg">Настройки</iconbutton>
            <a @click="$store.dispatch('LOGOUT')">
                <iconbutton image="/icons/interface/chats.svg" class="red">Выйти</iconbutton>
            </a>
        </div>
        <div class="myprofile">
            <settings v-if="showsettings"></settings>
            <friends v-if="showfriends"></friends>
        </div>
    </div>
</template>

<script>
import thebutton from "@/components/thebutton";
import iconbutton from "@/components/iconbutton";
import settings from "@/components/myprofile/settings";
import Friends from "@/components/myprofile/friends";
import store from "@/store";
export default {
    name: "MyprofileView",
    components: {
        Friends,
        thebutton, iconbutton, settings
    },
    data() {
        return {
            showsettings: false,
            showfriends: false,
        }
    },
    store: store,
    methods: {
        opensettings() {
            this.showfriends = false;
            this.showsettings = true;
        },
        openfriends() {
            this.showsettings = false;
            this.showfriends = true;
        }

    }
}
</script>

<style scoped>
.profileheaderwrapper {
    width: 100%;
    height: 300px;
    background-color: var(--main-color);
    display: flex;
    justify-content: center;
    color: var(--white-color);
}
.profileheader {
    width: 640px;
    height: 100%;

    display: flex;
}
.avatarwrapper {
    height: 100%;
    padding: 32px;
    display: flex;
    align-items: center;
    width: 50%;
}
.userinfo {
    width: 50%;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
}
.avatarimg {
    width: 200px;
    height: 200px;
    background-size: 50%;
    background-position: center;
    background-repeat: no-repeat;
    background-color: white;
    border-radius: 1000px;
}
.useractions {
    width: 100%;
    padding: 8px var(--padding);
    display: flex;
    justify-content: center;
}
.myprofile {
    padding: 8px var(--padding);
}
@media (max-width: 656px) {
    .useractions {
        flex-wrap: wrap;
    }
    .btn {
        margin-bottom: 8px;
    }
}
@media (max-width: 550px) {
    .avatarwrapper {
        width: auto;
    }
    .userinfo {
        width: auto;
    }
}
@media (max-width: 440px) {
    .avatarimg {
        width: 125px;
        height: 125px;
    }
}
@media (max-width: 384px) {
    .profileheader {
        flex-direction: column;
        align-items: center;
    }
    .userinfo {
        height: auto;
    }
    .avatarwrapper {
        height: auto;
    }
    .profileheaderwrapper {
        height: auto;
    }
}

</style>