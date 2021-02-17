<template>
	<div class="message_item_wrap" :class="{my: isMy}">
		<div class="message_item">
			<div class="message_item_text">{{ message.text }}</div>

			<div class="message_item_attach"
				v-if="message.video || message.photo">
				<iframe src="" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
				<img src="/img/messages/%url%" alt="">
			</div>

			<div class="message_item_date">
				{{ message.time }}

				<div class="message_item_status"
					:class="[ message.is_read ? 'read' : 'sent' ]"
					v-if="isMy"></div>
			</div>
		</div>
	</div>
</template>

<script>

import HTTP from '@/utils/http'

export default {

	props: {
		message: Object
	},
	data()
	{
		return {

		};
	},
	created() {

	},
	mounted()
	{
		window.scrollTo(0, document.body.scrollHeight);
	},
	computed: {
		isMy()
		{
			return this.message.u_id == this.$store.state.main.user.id;
		}
	},
	methods: {

	}
};
</script>
