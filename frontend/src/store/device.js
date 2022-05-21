import store from '@/store';
export default {
    state: {
        clientWidth: 0,
        clientHeight: 0,
    },
    getters: {
        getClientWidth: (state) => state.clientWidth,
        getClientHeight: (state) => state.clientHeight,
    },
    actions: {
        deviceStart(context) {
            context.state.clientWidth = window.innerWidth;
            context.state.clientHeight = window.innerHeight;
            window.addEventListener('resize', function () {
                store.dispatch('updateScreenSizes');
            })
        },
        updateScreenSizes(context) {
            context.state.clientWidth = window.innerWidth;
            context.state.clientHeight = window.innerHeight;
        }

    }
}