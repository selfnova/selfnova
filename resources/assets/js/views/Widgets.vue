<template>
	<div class="container">
		<h3 class="main_h">Виджеты</h3>

		<div class="widget_sett_item flex aic"
			v-for="w in widgets"
			:key="w.id">
			<div class="wsi_name">{{ w.name }}</div>

			<div class="wsi_toggle"
				:class="{active: w.active}"
				@click="toggleActive( w )">
				<span><img src="/img/eye_green.svg" alt=""></span>
			</div>
		</div>
	</div>
</template>

<script>

import HTTP from '@/utils/http'

export default {
	components: {  },

	data() {
		return {
			widgets: null
		}
	},
	created()
	{
		HTTP.get('/widgets')
			.then(resp => {
				this.widgets = resp.widgets;
			});
	},
	methods: {
		toggleActive( w )
		{
			w.active = w.active ? 0 : 1;

			HTTP.post('/widgets', w)
				.then(resp => {
					console.log('resp:', resp)
				});
		}
	}
};
</script>
