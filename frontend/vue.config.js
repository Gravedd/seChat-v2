const {defineConfig} = require('@vue/cli-service')
module.exports = defineConfig({
    transpileDependencies: true,
    pwa: {
        name: 'seChat',
        themeColor: '#0F88D0',
        msTileColor: '#ffffff',
        manifestOptions: {
            themeColor: '#0F88D0',
            background_color: '#ffffff',
            iconPaths: {
                faviconSVG: 'img/icons/favicon.svg',
                favicon32: null,
                favicon16: null,
                appleTouchIcon: null,
                maskIcon: null,
                msTileImage: null,
            }
        },

    }
})
