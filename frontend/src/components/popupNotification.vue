<template>
<div class="popupwrapper" :title="id">
    <div class="btncontainer">
        <h4>{{ headertext }}</h4>
        <div class="closebtn" tabindex="true" @click="remove(id)">&times;</div>
    </div>
    <div class="nottext">
        {{ contenttext }}
    </div>
</div>
</template>

<script>
import store from '@/store'
export default {
    name: "popupNotification",
    store: store,
    props: {
        headertext: String,
        contenttext: String,
        id: Number,
    },
    methods: {
        remove(id){
            store.commit('removeNotificationById', id);
        }
    }
}
</script>

<style scoped>
.popupwrapper{
    background-color: rgba(0, 0, 0, 0.45);
    width: 300px;
    border-radius: 8px;
    padding: 8px;
    margin-bottom: 8px;
    color: var(--white-color);
    display: block;
}
.btncontainer {
    display: flex;
    justify-content: space-between;
}
.closebtn {
    background-color: rgba(0, 0, 0, 0.3);
    text-align: center;
    border-radius: 4px;
    width: 25px;
    height: 25px;
}
.popupwrapper h4 {
    letter-spacing: 0px;
}
.nottext {

}
</style>