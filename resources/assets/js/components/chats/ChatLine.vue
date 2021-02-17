<template>
	<router-link class="dialog_item active flex aic sb"
		:to="{ name: 'chatOne', params: {id: chat.id}}">

		<div class="flex aic">
			<div class="dialog_item_img"
				:style="{ backgroundImage: `url(${ chatAvatar || '/img/user.png' })`}"></div>
			<div>
				<div class="dialog_item_h">{{ chatName }}</div>
				<div class="dialog_item_t">{{ msgText }}</div>
			</div>
		</div>
		<div class="dialog_item_date">{{ msgDate }}</div>
		<span class="close"
			@click.prevent=""></span>
	</router-link>
</template>

<script>

import HTTP from '@/utils/http'

export default {

	props: {
		chat: Object
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
		chatName()
		{
			return this.chat.user.full_name;
		},
		chatAvatar()
		{
			return this.chat.user.avatar;
		},
		msgText()
		{
			return this.chat.massage ? this.chat.massage.text : 'Нет сообщений';
		},
		msgDate()
		{
			return this.chat.massage ? this.chat.massage.date : '';
		}
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
