<template>
	<div class="">
		<div class="header_block message_top">
			<div class="container flex aic sb">
				<div class="flex aic">
					<router-link class="message_avr"
						:to="chatRouter"
						:style="chatAvatar"/>

					<router-link class="flex"
						:to="chatRouter">
						{{ chatName }}
					</router-link>
				</div>

				<Context></Context>
			</div>
		</div>


		<div class="container message_wrap">
			<MessageList
				v-for="(messages, key) in messageList"
				:key="key"
				:messages="messages" />
		</div>

		<div class="message_write_wrap">
			<div class="container">
				<WriteMessage
					@sended="addMessage($event)" />
			</div>
		</div>
	</div>
</template>

<script>

import HTTP from '@/utils/http'
import Context from '@/components/@elements/Context'
import MessageList from '@/components/messages/MessageList'
import WriteMessage from '@/components/@elements/WriteMessage'

export default {
	components: { Context, WriteMessage, MessageList },

	data() {
		return {
			chat: {},
			messageList: null,
		}
	},
	computed:
	{
		chatName()
		{
			return this.chat.user ? this.chat.user.full_name : this.chat.name;
		},
		chatRouter()
		{
			let id, router = 'user';

			if ( this.chat.user )
				id = this.chat.user.id;

			return { name: router, params: { id }};
		},
		chatAvatar()
		{
			let avatar;

			if ( this.chat.user )
				avatar = this.chat.user.avatar;

			return { backgroundImage: `url(${ avatar || '/img/user.png' })` }
		}

	},
	created()
	{
		this.getChat();
		this.getMessages();

		this.$echo.private( 'chat.' + this.$router.currentRoute.params.id )
			.listen('.message.add', (e) => {
				console.log('e:', e)
				this.addMessage( e )
			});
			setTimeout(() => {
				this.$echo.private( 'chat.' + this.$router.currentRoute.params.id ).whisper('typing', {
				name: 'test'
			});
			}, 2000);
			this.$echo.private( 'chat.' + this.$router.currentRoute.params.id ).listenForWhisper('typing', (e) => {
				console.log(e.name);
			});
	},
	methods: {
		addMessage( message )
		{
			if ( this.messageList[ message.day ] )
				this.messageList[ message.day ].push( message.message )
			else this.$set(this.messageList, message.day, [message.message])
		},
		getChat()
		{
			HTTP.get( this.$router.currentRoute.path )
				.then(resp => {
					this.chat = resp.chat;
				});
		},
		getMessages()
		{
			HTTP.get( '/messages/' + this.$router.currentRoute.params.id )
				.then(resp => {
					this.messageList = resp;
				});
		}
	}
};
</script>
