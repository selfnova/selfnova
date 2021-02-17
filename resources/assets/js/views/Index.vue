<template>
	<div>
		<div class="login_wrap">
			<form class="container login" @submit.prevent="auth">
				<img src="/img/login_people.png" alt="">
				<div>
					<div class="login_h">БУДЬ<br>НАСТОЯЩИМ</div>

					<div class="login_form">
						<input type="text" v-model="login" class="input" placeholder="Логин" required>

						<input type="password" v-model="password" class="input" placeholder="Пароль" required>

						<div class="error"
							v-if="error">
							{{ error }}
						</div>
						<button name="auth" class="btn">Войти<img src="/img/arr_l_w.png" alt=""></button>

						<div class="login_pass link">Забыли пароль?</div><br>
						<router-link class="link"
							:to="{ name: 'register'}" >
							Регистрация
						</router-link>
					</div>
				</div>
			</form>
		</div>

		<div class="container login_item_wrap flex sb aic fww">
			<div class="login_item">
				<img src="/img/index/icon1.svg" alt="">
				<div class="login_item_t">Никакого спама</div>
			</div>

			<div class="login_item">
				<img src="/img/index/icon2.svg" alt="">
				<div class="login_item_t">Только настоящие люди</div>
			</div>

			<div class="login_item">
				<img src="/img/index/icon3.svg" alt="">
				<div class="login_item_t">Общение, как раньше</div>
			</div>

			<div class="login_item">
				<img src="/img/index/icon4.svg" alt="">
				<div class="login_item_t">Виджеты для каждого</div>
			</div>
		</div>
	</div>
</template>

<script>

import HTTP from '@/utils/http'
import { AUTH_REQUEST } from "@/store/actions/auth";

export default {
	data() {
		return {
			login: '',
			password: '',
			error: null
		}
	},
	methods: {
		auth() {
			const { login, password } = this;

			this.$store.dispatch(AUTH_REQUEST, { login, password })
				.then(() => {
					this.$router.push({name: 'board'});
				})
				.catch(err => {
					this.error = err.message;
				});
		}
	}
};
</script>
