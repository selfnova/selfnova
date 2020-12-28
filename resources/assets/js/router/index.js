import Vue from 'vue'
import Router from 'vue-router'
import store from '@/store'

import Index from '@/views/Index.vue'
import Help from '@/views/Help.vue'
import Support from '@/views/Support.vue'

import Board from '@/views/Board.vue'

Vue.use(Router)

const ifNotAuth = (to, from, next) => {
	if (!store.getters.isAuthenticated) {
		next()
		return
	}
	next({name: 'board'})
}

const ifAuth = (to, from, next) => {
    if (store.getters.isAuthenticated) {
      next()
      return
    }
    next('/')
  }

const routes =
[
	{
		path: '/',
		name: 'login',
		component: Index,
		meta: {title: 'Главная', guest: true},
		beforeEnter: ifNotAuth
	},
	{
		path: '/help',
		name: 'help',
		component: Help,
		meta: {title: 'Помощь по сайту', guest: true}
	},
	{
		path: '/support',
		name: 'support',
		component: Support,
		meta: {title: 'Написать в поддержку', guest: true}
	},
    {
		path: '/board',
		name: 'board',
		component: Board,
		meta: {title: 'Основная страница', auth: true},
		beforeEnter: ifAuth
	},

];

const router = new Router({
	mode: 'history',
	routes,
	scrollBehavior (to, from, savedPosition) {
		return { x: 0, y: 0 }
	}
});

router.afterEach((to, from) => {
	Vue.nextTick( () => {
		document.title = to.meta.title ? to.meta.title : 'Главная';
	});
});

export default router;
