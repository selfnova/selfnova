<template>
	<div id="app">
		<header class="main_header">
			<div class="container flex aic sb fww">
				<div class="flex aic fww">
					<a href="/" class="logo">
						<img src="/img/logo.svg" alt="">
					</a>
					<form action="/search" class="search_header">
						<input name="s" type="text" class="input" placeholder="">
						<div class="search_header_btn flex aic jcc">
							<img src="/img/search.svg" alt="">
						</div>
					</form>
				</div>

				<div class="flex sb aic"
                    v-if="$store.getters.isAuthenticated">
					<ul class="menu flex aic">
						<li><a href="/user/%user_id%">Моя страница</a></li>
						<li><a href="/widgets">Виджеты</a></li>
						<li><a href="/groups">Группы</a></li>
					</ul>

					<div class="user_notice flex aic">
						<a href="/peoples" class="user_notice_item uni_peoples">
							<img src="/img/people.svg" alt="">
						</a>

						<a href="/messages" class="user_notice_item uni_message">
							<img src="/img/message.svg" alt="">
							<span class="count">%mess_count%</span>
						</a>

						<a href="/notifications" class="user_notice_item uni_notice">
							<img src="/img/notification.svg" alt="">
						</a>

						<a href="/settings" class="user_notice_item uni_setting">
							<img src="/img/setting.svg" alt="">
						</a>
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

		<div class="header_block">
			<div class="container">
				<h1>{{ $router.currentRoute.meta.title }}</h1>
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

import { MAIN_LOAD } from "@/store/actions/main";

export default {
	components: {

	},
	data() {
		return {
		}
	},
	computed: {
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
};
</script>
