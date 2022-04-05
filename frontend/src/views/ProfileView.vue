<template>
<div class="container">
    <div class="profileheaderwrapper">
        <div class="profileheader">
            <div class="avatarwrapper">
                <div class="avatarimg" style="background-image: url(/icons/user.svg);"></div>
            </div>
            <div class="userinfo">
                <div class="userinfowrapper">
                    <h3>UserName</h3>
                    <span class="status">user status here...</span>
                </div>
            </div>
        </div>
    </div>
    <div class="useractions">
        <iconbutton image="/icons/interface/addfriend.svg">Добавить в друзья</iconbutton>
        <iconbutton image="/icons/interface/new-message.svg">Отправить сообщение</iconbutton>
    </div>
    <div class="userstatistics">
        <div class="userstatisticswrapper">
            <h2>Статистика</h2>
            <div>Был онлайн: 30.03.2022 18:23</div>
            <div>Зарегистрирован: 30.03.2022 18:23</div>
            <div>Айди пользователя: {{ $route.params.id }}</div>
        </div>
    </div>
</div>
</template>

<script>
import thebutton from "@/components/thebutton";
import iconbutton from "@/components/iconbutton";
export default {
    name: "ProfileView",
    components: {
        thebutton, iconbutton
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
.userstatistics {
    padding: 8px var(--padding);
    display: flex;
    justify-content: center;
    text-align: center;
}
.userstatistics h2 {
    color: var(--main-color);
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