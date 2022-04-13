<template>
    <div class="container">
        <div class="authwrapper module">
            <div class="btnwrapper">
                <span v-bind:class="{ active: showlogin }" class="signinbtn" @click="showloginform">Войти</span>
                <span v-bind:class="{ active: showregister }" class="signinbtn" @click="showregisterform">Регистрация</span>
            </div>
            <div class="authFormsWrapper">
                <loginform v-show="showlogin"></loginform>
                <registerform v-show="showregister"></registerform>
            </div>
        </div>
    </div>
</template>

<script>
import loginform from "@/components/auth/loginform";
import registerform from "@/components/auth/registerform";
export default {
    name: "LoginView",
    data()  {
        return {
            showlogin: 'active',
            showregister: false,
        }
    },
    components: {
        loginform, registerform
    },
    methods: {
        showloginform() {
            this.showregister = false;
            this.showlogin = 'active';
        },
        showregisterform() {
            this.showlogin = false;
            this.showregister = 'active';
        }
    }
}
</script>

<style scoped>
.container {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding-top: 32px;
}

.authwrapper {
    width: 640px;
    padding: 40px;
}

.signinbtn {
    font-weight: bold;
    letter-spacing: 1px;
    margin-right: 16px;
    transition: 0.33s color;
}

.signinbtn:hover {
    color: var(--accent-color);
}

.active {
    border-bottom: 3px solid var(--accent-color);
}

.btnwrapper {
    margin-bottom: 40px;
    font-family: LightFont;
}
@media (max-width: 660px) {
    .authwrapper {
        width: 98%;
    }
}
@media (max-width: 350px) {
    .authwrapper {
        padding: 16px;
    }
}


</style>