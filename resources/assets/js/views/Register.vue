<template>
	<div class="login_wrap reg_wrap">
		<form name="form" class="container reg_submit flex sb fww" action="/registration" method="post" enctype="multipart/form-data">

			<div class="login_form reg_form">
				<h3>Заполните данные</h3>

				<input type="text" class="input" placeholder="Имя" required
					v-model="form.name">
				<input type="text" class="input" placeholder="Фамилия" required
					v-model="form.last_name">

				<div class="flex aic sb">
					<select-list class="born_day"
						:items="day"
						@select="born.day = $event.name"></select-list>
					<select-list class="born_month"
						:items="month"
						@select="born.month = $event.id,current_month = $event"></select-list>
					<select-list class="born_year"
						:items="year"
						@select="born.year = $event.name"></select-list>
				</div>
				<select-list
					:items="gender"
					@select="form.gender = $event.name"></select-list>

				<input type="text" class="input" placeholder="E-mail" required
					v-model="form.email">
				<input type="text" class="input" placeholder="Серия и номер паспорта" required
					v-model="form.passport">

				<div class="form_policy">
					<label class="flex">
						<input  type="checkbox" checked required
							v-model="form.policy">

						<span class="check"></span>
						<span>Я соглашаюсь с
							<a class="link_a" href="/policy">Политикой конфиденциальности</a>
						</span>
					</label>
				</div>

				<div style="color: #ff0000;margin-top: 5px;"
					v-if="error">{{ error }}</div>

				<button class="btn">Отправить</button>
			</div>

			<div class="reg_photo">
				<div class="flex aic fww">
					<h3>Загрузите фото</h3>
					<div class="reg_photo_icon">
						<img src="/img/question.svg" alt="">
					</div>
					<div class="link_2 reg_why"
						@click="isOpenWhy = !isOpenWhy">Зачем это нужно?</div>
				</div>

				<p class="reg_why_block"
					v-show="isOpenWhy">
					Основная цель Selfnova — создание среды для общения и бизнеса, в которой каждый пользователь, комментарий и отзыв будет настоящим. Мы ежедневно отслеживаем поведение пользователей, чтобы сделать нашу социальную сеть самой комфортной для вас.</p>

				<p class="reg_photo_t">
					Загрузите фото, на котором вы держите свой паспорт в открытом виде. На фото должно быть отчетливо видно ваше лицо, имя, фамилию, номер паспорта и фотографию в нём. Все остальные данные вы можете закрыть рукой. Это фото будет являться подтверждением личности и не будет использоваться на сайте.
				</p>

				<div class="reg_photo_prev"></div>
				<label class="btn_mini input_avatar">
					<img src="/img/camera-white.svg" alt="">
					<span class="reg_photo_btn">Загрузите фото с паспортом</span>
					<input  class="reg_photo_file" type="file" name="photo">
				</label>
				<div style="overflow: hidden;width: 0;height: 0;">
					<canvas id="canvas_crop"></canvas>
				</div>
				<div class="reg_photo_err">Загрузите фото</div>
			</div>
		</form>
	</div>
</template>

<script>

import SelectList from '@/components/@elements/Select'

export default {

	components: {
		SelectList
	},
	data() {
		return {
			form: {policy: true},
			born: {},
			current_month: null,
			isOpenWhy: false,
			month: {
				data: [
					{ id: 1, name: 'Января', active: false, count_days: 32 },
					{ id: 2, name: 'Февраля', active: false, count_days: 30 },
					{ id: 3, name: 'Марта', active: false, count_days: 32 },
					{ id: 4, name: 'Апреля', active: false, count_days: 31 },
					{ id: 5, name: 'Мая', active: false, count_days: 32 },
					{ id: 6, name: 'Июня', active: false, count_days: 31 },
					{ id: 7, name: 'Июля', active: false, count_days: 32 },
					{ id: 8, name: 'Августа', active: false, count_days: 32 },
					{ id: 9, name: 'Сентября', active: false, count_days: 31 },
					{ id: 10, name: 'Октября', active: false, count_days: 32 },
					{ id: 11, name: 'Ноября', active: false, count_days: 31 },
					{ id: 12, name: 'Декабря', active: false, count_days: 32 },
				]
			},
			gender: {
				data: [
					{ id: 0, name: 'Мужской', active: false },
					{ id: 1, name: 'Женский', active: false },
				]
			},
			error: null
		}
	},
	computed: {
		born_day()
		{
			return this.born;
		},
		day()
		{
			let data = {
				data: []
			};
			let count_days = this.current_month ? this.current_month.count_days : 32;

			for ( let i = 1; i < count_days; i++ )
				data.data.push({ id: i, name: i, active: false });

			return data;
		},
		year()
		{
			let data = {
				data: []
			};

			let from_year = new Date().getFullYear() - 14;
			let max_age = from_year - 100;

			for ( let i = from_year; i > max_age; i-- )
				data.data.push({ id: i, name: i + ' г.', active: false });

			return data;
		}
	},
	created()
	{
		this.form.born = this.born_day;
	}
};
</script>
