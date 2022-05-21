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
</style>