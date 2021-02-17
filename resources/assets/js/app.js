import Vue from 'vue'
import App from './components/App.vue'
import router from "./router/index.js";
import store from "./store";
import VueEcho from 'vue-echo-laravel';
import HTTP from '@/utils/http'

window.Pusher = require('pusher-js');

Vue.prototype.$http = HTTP;

Vue.use(VueEcho, {
    broadcaster: 'pusher',
	key: 'local',
	wsHost: window.location.hostname,
	wsPort: 6001,
	wssPort: 6001,
    forceTLS: false,
	disableStats: true,
	enabledTransports: ['ws', 'wss'],
	auth: {
        headers: {
            Authorization: 'Bearer ' + localStorage.getItem("user-token")
        },
    }
});

Vue.config.productionTip = false;

new Vue({
	router,
	store,
	render: h => h(App),
}).$mount('#app')
