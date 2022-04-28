import store from '@/store';
export default {
    state: {
        notifications: []
    },
    getters: {
        getNotifications: (state) => state.notifications
    },
    mutations: {
        removeNotificationById(state, id) {
            state.notifications.splice(id, 1);
        },
        addNotification(state, data) {
            state.notifications.push({
                'headertext': data.headertext,
                'contenttext': data.contenttext,
            });
        }

    }
}