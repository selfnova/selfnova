import Vue from 'vue'
import Router from 'vue-router'
import store from '@/store'

import Index from '@/views/Index.vue'
import Register from '@/views/Register.vue'
import Help from '@/views/Help.vue'
import Support from '@/views/Support.vue'

import Board from '@/views/Board.vue'

import User from '@/views/User.vue'
import Widgets from '@/views/Widgets.vue'
import Groups from '@/views/Groups.vue'

import Peoples from '@/views/Peoples.vue'
import Messages from '@/views/Messages.vue'
import MessageOne from '@/views/MessageOne.vue'
import Noty from '@/views/Noty.vue'
import Settings from '@/views/Settings.vue'
import Search from '@/views/Search.vue'

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
		path: '/register',
		name: 'register',
		component: Register,
		meta: {title: 'Заявка на регистрацию', guest: true},
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
	{
		path: '/user/:id',
		name: 'user',
		component: User,
		meta: {title: 'Пользователь'}
	},
	{
		path: '/widgets',
		name: 'widgets',
		component: Widgets,
		meta: {title: 'Моя страница', auth: true},
		beforeEnter: ifAuth
	},
	{
		path: '/groups',
		name: 'groups',
		component: Groups,
		meta: {title: 'Группы', auth: true},
		beforeEnter: ifAuth
	},
	{
		path: '/peoples',
		name: 'peoples',
		component: Peoples,
		meta: {title: 'Люди', auth: true},
		beforeEnter: ifAuth
	},
	{
		path: '/chats',
		name: 'chats',
		component: Messages,
		meta: {title: 'Диалоги', auth: true},
		beforeEnter: ifAuth
	},
	{
		path: '/chats/:id',
		name: 'chatOne',
		component: MessageOne,
		meta: {title: 'Сообщения', auth: true},
		beforeEnter: ifAuth
	},
	{
		path: '/settings',
		name: 'settings',
		component: Settings,
		meta: {title: 'Настройки профиля', auth: true},
		beforeEnter: ifAuth
	},
	{
		path: '/notifications',
		name: 'noty',
		component: Noty,
		meta: {title: 'Уведомления', auth: true},
		beforeEnter: ifAuth
	},
	{
		path: '/search',
		name: 'search',
		component: Search,
		meta: {title: 'Результаты поиска', auth: true},
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
