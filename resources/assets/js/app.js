import Vue from 'vue'
import App from './components/App.vue'
import router from "./router/index.js";
import store from "./store";

Vue.config.productionTip = false;

new Vue({
	router,
	store,
	render: h => h(App),
}).$mount('#app')
