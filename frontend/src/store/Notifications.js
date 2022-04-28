import store from '@/store';
export default {
    state: {
        notifications: [
            {headertext: 'Уведомление 1', contenttext: 'текст уведомления'},
            {headertext: 'Уведомление 2', contenttext: 'текст уведомления'},
            {headertext: 'Уведомление 3', contenttext: 'текст уведомления'},
            {headertext: 'Уведомление 4', contenttext: 'текст уведомления'},
            {headertext: 'Уведомление 5', contenttext: 'текст уведомления'},
        ]
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