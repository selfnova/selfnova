<template>
	<div id="app">
		<header class="main_header">
			<div class="container flex aic sb fww">
				<div class="flex aic fww">
					<router-link class="logo"
						:to="{ name: 'board'}" >
						<img src="/img/logo.svg" alt="">
					</router-link>
					<div class="logo_slog"
						v-if="!$store.getters.isAuthenticated">Будь настоящим</div>

					<form class="search_header"
						:class="{active: s_active}"
						@submit.prevent="search"
						v-else>
						<input type="text" class="input"
							v-model="s_query"

							@focus="s_active = true"
							@blur="s_active = false">

						<div class="search_header_btn flex aic jcc">
							<img src="/img/search.svg" alt="">
						</div>
					</form>
				</div>

				<div class="flex sb aic"
                    v-if="$store.getters.isAuthenticated">
					<ul class="menu flex aic">
						<li>
							<router-link class=""
								:to="{ name: 'user', params: {id: user.id}}" >
								Моя страница
							</router-link>
						</li>

						<li>
							<router-link class=""
								:to="{ name: 'widgets'}" >
								Виджеты
							</router-link>
						</li>
						<li>
							<router-link :to="{ name: 'groups'}" >
								Группы
							</router-link>
						</li>
					</ul>

					<div class="user_notice flex aic">
						<router-link class="user_notice_item uni_peoples"
							:to="{ name: 'peoples'}" >
							<img src="/img/people.svg" alt="Люди">
						</router-link>

						<router-link class="user_notice_item uni_message"
							:to="{ name: 'chats'}" >
							<img src="/img/message.svg" alt="Сообщения">
							<span class="count">{{ new_mess }}</span>
						</router-link>

						<router-link class="user_notice_item uni_notice"
							:to="{ name: 'noty'}" >
							<img src="/img/notification.svg" alt="Уведомления">
						</router-link>

						<router-link class="user_notice_item uni_setting"
							:to="{ name: 'settings'}" >
							<img src="/img/setting.svg" alt="Настройки">
						</router-link>
					</div>
				</div>

				<div class="mob_menu">
					<div class="mob_menu_btn">
						<span></span>
					</div>

					<div class="mob_menu_cont">
						<ul class="mmc_menu">
							<li><a class="mmc_item" href="/user/%user_id%">Моя страница</a></li>
							<li><a class="mmc_item" href="/peoples">Люди</a></li>
							<li><a class="mmc_item" href="/groups">Группы</a></li>
							<li><a class="mmc_item" href="/widgets">Виджеты</a></li>
							<li><a class="mmc_item" href="/settings">Настройки</a></li>
						</ul>
					</div>
				</div>
			</div>
		</header>
		<!-- <header class="main_header" style="bottom: 0;top: auto;">
			<div class="container flex aic sb fww">
				{{ websocket }}
			</div>
		</header> -->
		<div class="header_block">
			<div class="container flex sb">
				<h1>{{ $router.currentRoute.meta.title }}</h1>

				<a href="/groups/add" class="btn_mini"
					v-if="$router.currentRoute.name == 'groups'">Создать группу</a>

				<div class="link_img"
					v-if="$router.currentRoute.name == 'settings'"
					@click.prevent="logout">
					<img src="/img/exit.svg" alt="">
					<span>Выйти из профиля</span>
				</div>
			</div>
		</div>

		<div class="wrap">
			<router-view></router-view>
		</div>

		<footer class="container flex fww">
			<router-link class="help flex aic"
				:to="{ name: 'help'}" >
				<img src="/img/support.svg" alt="">
				<div class="link">Помощь по сайту</div>
			</router-link>
			<a href="https://money.yandex.ru/to/410011651707176" target="_blank" class="help flex aic">
				<img src="/img/card.svg" alt="">
				<div class="link">Поддержать</div>
			</a>
		</footer>
	</div>
</template>

<script>

import HTTP from '@/utils/http'

import { MAIN_LOAD } from "@/store/actions/main";
import { AUTH_LOGOUT } from "@/store/actions/auth";

export default {
	components: {

	},
	data() {
		return {
			new_mess: 3,
			noty: null,
			s_query: null,
			s_active: false
		}
	},
	computed: {
		websocket()
		{
			return 'Ответ сервера: ' + this.noty;
		},
		user()
		{
			return this.$store.state.main.user;
		}
	},
	created()
	{
		if ( this.$store.getters.isAuthenticated )
		{
			this.$store.dispatch(MAIN_LOAD)
				.then(() => {
				});
		}

	},
	mounted()
	{
		this.$echo.channel('post').listen('Posted', (e) => {
            this.noty = 'Добавлен пост - ' + e.post.subject;
		});
	},
	methods: {
		logout()
		{
			if ( this.$store.getters.isAuthenticated )
			{
				this.$store.dispatch(AUTH_LOGOUT)
					.then(() => {
						this.$router.push({name: 'login'});
					});
			}
		},
		search()
		{

		}
	}
};
</script>
