<template>
	<form class="message_write"
		@submit.prevent="write">
		<div class="message_write_input flex sb">
			<input type="text" class="mwi_real" placeholder="Написать сообщение" required
				v-model="form.text">

			<div class="mess_attach">
				<div class="mess_attach_cont"></div>
				<div class="close"></div>
			</div>

			<div class="post_media flex aic">
				<label class="pm_item">
					<img src="/img/photo.svg" alt="">
					<input type="file" style="display: none;">
				</label>
				<div class="pm_item pm_video">
					<img src="/img/video.svg" alt="">
				</div>
			</div>

			<div class="popup popup_youtube">
				<div class="popup_cont popup_youtube_cont">
					<div class="popup_youtube_h">Добавьте ссылку с Youtube</div>
					<input type="text" class="input popup_youtube_inp">

					<div class="btn_mini go_add_mess_video">Добавить</div>
					<div class="close"></div>
				</div>

				<div class="mask"></div>
			</div>

			<div class="message_write_controls flex">
				<button class="message_write_btn">
					<img src="/img/message-icon-green.svg" alt="">
				</button>
			</div>
		</div>
	</form>
</template>

<script>

import HTTP from '@/utils/http'

export default {

	props: {

	},
	data() {
		return {
			form: {
				chat_id: this.$router.currentRoute.params.id
			}
		}
	},
	created()
	{

	},
	methods:
	{
		write()
		{
			this.$http.postFile( '/messages', this.form )
				.then(resp => {
					if ( resp.success )
					{
						this.$emit('sended', resp)
						this.form.text = ''
					}
				});
		}
	},
};
</script>
