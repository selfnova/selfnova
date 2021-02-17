<template>
	<div>
		<div class="tac">
			<div class="day_line">
				{{ new Date(messages[0].created_at ).toLocaleString('ru', {
					month: 'long',
					day: 'numeric'
					})
				}}</div>
		</div>

		<Message
			v-for="message in messages"
			:key="message.id"
			:message="message"></Message>
	</div>
</template>

<script>

import HTTP from '@/utils/http'

import Message from '@/components/messages/Message'

export default {

	components: {
		Message
	},
	props: {
		messages: Array,
		date: String,
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
		msgText()
		{
			return this.chat.massage ? this.chat.massage.text : 'Нет сообщений';
		},
		msgDate()
		{
			return this.chat.massage ? this.chat.massage.date : '';
		}
	},
	methods: {

	}
};
</script>
