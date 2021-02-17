<template>
	<div class="widget_item widget_item_wether">
		<div class="widget_h flex aic">
			<div class="widget_h_text">Погода в</div>
			<div class="link_a"
				@click="changeCity = true"
				v-show="!changeCity">{{ weather.settings.city }}</div>
			<input type="text" class="input"
				v-model="weather.settings.city"
				v-show="changeCity"
				@blur="changeCity = false"
				@change="update">
		</div>

		<div class="wiw_block">
			<div class="flex">
				<div class="wiw_block_item">
					<div class="wiw_block_name">Сегодня</div>
					<div class="flex aic">
						<div class="wiw_block_num">
							{{ weather.data.today.temperature }}°
						</div>
						<img alt=""
							:src="'/img/gismetio/' + weather.data.today.icon + '.svg'">
					</div>
				</div>
				<div class="wiw_block_item">
					<div class="wiw_block_name">Завтра</div>
					<div class="flex aic">
						<div class="wiw_block_num">
							{{weather.data.tomorrow.temperature }}°
						</div>
						<img alt=""
							:src="'/img/gismetio/' + weather.data.tomorrow.icon + '.svg'">
					</div>
				</div>
			</div>
			<div class="tac">
				<a target="_blank" href="https://www.gismeteo.ru/" style="position: relative;top: 15px;display: inline-block;font-size: 12px;color: #868686;">Погода от Gismeteo</a>
			</div>
		</div>
		<div class="popup wether_city">
			<form id="" method="post" action="/wether.city" class="popup_cont search_city_form">
				<div class="search_city_form_h">Выбрать город</div>

				<div class="select">
					<input name="abs_ci" value="" type="text" class="input search_city_inp" placeholder="Найти">

					<div class="select_drop">
						<div class="select_list">

						</div>
					</div>
				</div>

				<button class="btn_mini">Сохранить</button>
				<div class="close"></div>
			</form>

			<div class="mask"></div>
		</div>
	</div>
</template>

<script>

import HTTP from '@/utils/http'


export default {

	props: {
		weather: Object
	},
	data()
	{
		return {
			changeCity: false
		};
	},
	created() {

	},
	computed: {

	},
	watch: {
	},
	methods: {
		update()
		{
			HTTP.put('/widgets/' + this.weather.id, {settings: this.weather.settings})
				.then(resp => {
					this.$emit('updated');
				});
		}
	}
};
</script>
