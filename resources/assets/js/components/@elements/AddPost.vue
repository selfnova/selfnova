<template>
	<div v-show="isOpen">
		<form class="form_post_add"
			@submit.prevent="submit">

			<input type="text" class="input" placeholder="Заголовок" required
				v-model="form.subject">
			<textarea class="input fpa_text" placeholder="Напишите что-нибудь" required
				v-model="form.text"
				:rows="textHeight"></textarea>

			<div class="fpa_attach">
				<div class="fpa_attach_cont">

				</div>

				<div class="close"></div>
			</div>

			<div class="fpa_control flex sb aic">
				<div class="post_media flex aic">
					<label class="pm_item pm_photo">
						<img src="/img/photo.svg" alt="">
						<input type="file"
							@change="addFile">
					</label>
					<div class="pm_item pm_video">
						<img src="/img/video.svg" alt="">
					</div>
					<div class="pm_item pm_video">
						<img src="/img/melody.svg" alt="">
					</div>
				</div>
				<button class="btn_mini">Опубликовать</button>
			</div>
		</form>
		<div class="popup popup_youtube">
			<div class="popup_cont popup_youtube_cont">
				<div class="popup_youtube_h">Добавьте ссылку с Youtube</div>
				<input type="text" class="input popup_youtube_inp">

				<div class="btn_mini go_add_video">Добавить</div>
				<div class="close"></div>
			</div>

			<div class="mask"></div>
		</div>
		<div class="popup popup_music">
			<div class="popup_cont popup_youtube_cont">
				<div class="popup_youtube_h">Добавьте ссылку на трек из Яндекс.Музыка</div>
				<input type="text" class="input popup_music_inp">

				<div class="btn_mini go_add_music">Добавить</div>
				<div class="close"></div>
			</div>

			<div class="mask"></div>
		</div>
	</div>
</template>

<script>

import HTTP from '@/utils/http'

export default {

	props: {
		isOpen: Boolean
	},
	data()
	{
		return {
			form: {
				image: null,
				u_id: this.$store.state.main.user.id
			},
			img: null
		};
	},
	created() {

	},
	computed: {
		textHeight()
		{
			return this.form.text ? this.form.text.split("\n").length : 1;
		}
	},
	watch: {
	},
	methods: {
		reset()
		{
			this.form = {
				image: null,
				u_id: this.$store.state.main.user.id
			};
			this.img = null;
		},
		submit(e)
		{
			HTTP.post('/post', this.form)
				.then((data) => {
					this.$emit('posted', data.post);
					this.reset();
				})
				.catch((error) => {
					console.log('error:', error)
				});
		},
		addFile(e)
		{
			let files = e.target.files || e.dataTransfer.files;

			if (!files.length)
				return false;

			this.form.image = files[0];
			this.createImage(files[0]);
		},
		createImage(file)
		{
			let reader = new FileReader();

			reader.onload = (e) => {
				this.img = e.target.result;
			};
			reader.readAsDataURL(file);
		},
	}
};
</script>
