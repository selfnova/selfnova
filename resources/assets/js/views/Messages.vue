<template>
	<div class="container">
		<div class="chats">
			<ChatLine v-for="chat in chats"
				:key="chat.id"
				:chat="chat"></ChatLine>
		</div>

	</div>
</template>

<script>

import HTTP from '@/utils/http'

import ChatLine from '@/components/chats/ChatLine'

export default {
	components: { ChatLine },

	data() {
		return {
			chats: null
		}
	},
	created()
	{
		HTTP.get('/chats')
			.then(resp => {
				this.chats = resp.chats;
			});
	},
	methods: {

	}
};
</script>
