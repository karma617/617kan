import Vue from 'vue'
import App from './App'
import requestApi from '@/common/vmeitime-http/http.js'
import helper from '@/common/helper'
// 全局分享
import mixin from '@/utils/base.js'
Vue.mixin(mixin)

Vue.config.productionTip = false
Vue.prototype.$http = requestApi
Vue.prototype.$helper = helper
// #ifdef APP-VUE
if (uni.getSystemInfoSync().platform == "android") {
	Vue.prototype.$downloader = uni.requireNativePlugin('Karma617-DownloaderManager')
}
// #endif
Vue.prototype.$os = uni.getSystemInfoSync().platform;

App.mpType = 'app'

const app = new Vue({
    ...App
})
app.$mount()
