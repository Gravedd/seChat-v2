<template>
<div class="notificationContainer">
    <popup-notification
        v-for="(notification, index) in notifications"
            :headertext="notification.headertext"
            :contenttext="notification.contenttext"
            :id="index"
            :userid="notification.userid"
    />
</div>
</template>

<script>
import PopupNotification from "@/components/popupNotification";
import store from '@/store';
export default {
    name: "notificationContrainer",
    components: {PopupNotification},
    store: store,
    data() {
        return {
            notifications: store.getters.getNotifications,
        }
    }
}
</script>

<style scoped>
.notificationContainer {
    position: fixed;
    bottom: 10px;
    left: 83vw;
    width: 301px;
    height: 201px;
    overflow: hidden;

    display: flex;
    flex-direction: column-reverse;
}
@media (max-width: 1802px) {
    .notificationContainer {
        left: 80vw;
    }
}
@media (max-width: 1486px) {
    .notificationContainer {
        left: 75vw;
    }
}
@media (max-width: 1162px) {
    .notificationContainer {
        left: 70vw;
    }
}
@media (max-width: 1008px) {
    .notificationContainer {
        left: 60vw;
    }
}
@media (max-width: 758px) {
    .notificationContainer {
        left: 50vw;
    }
}
@media (max-width: 604px) {
    .notificationContainer {
        left: 5vw;
    }
}
@media (max-width: 500px) {
    .notificationContainer {
        left: 5vw;
        bottom: 538px;
    }
}
</style>