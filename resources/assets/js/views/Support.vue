<template>
	<form action="/ajax/support" method="post" class="container sup_form"
		@submit.prevent="send">
		<h2>Напишите в поддержку</h2>

		<div class="sup_time">
			<img class="mid_icon" src="/img/clock.svg" alt="">
			Среднее время ответа 12 часов
		</div>

		<div class="form_cont">
			<div class="sup_line flex sb aic fww">
				<input type="text" class="input" placeholder="Имя"
					v-model="data.name">
				<input type="text" class="input" placeholder="Фамилия"
					v-model="data.last_name">
			</div>
			<div class="sup_line flex sb aic fww">
				<input type="text" class="input" placeholder="Email"
					v-model="data.email">
				<input type="text" class="input" placeholder="Телефон"
					v-model="data.phone">
			</div>

			<textarea class="input sup_t" placeholder="Постарайтесь подробно описать ваш вопрос"
				v-model="data.message"></textarea>

			<button class="btn">Отправить</button>
		</div>

		<div class="success flex jcc aic">
			<div>
				<img src="/img/success.svg" alt=""><br>
				Сообщение отправлено
			</div>
		</div>
	</form>
</template>

<script>

import HTTP from '@/utils/http'

export default {
	data() {
		return {
			data: {
				name: '',
				last_name: '',
				email: '',
				phone: '',
				message: '',
			}
		}
	},
	methods: {
		send()
		{
			HTTP.post('support', this.data)
				.then(res => {
					console.log(res);
				});
		}
	}
};
</script>
