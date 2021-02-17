<template>
	<div>
		<div class="comments"
			v-if="comments">
			<div class="comments_item"
				v-for="comment in comments"
				:key="comment.id">
				<div class="post_top post_top__comment flex sb">
					<router-link class="post_top_l flex"
						:to="{ name: 'user', params: {id: comment.u_id}}">

						<div class="post_top_avr post_top_avr__comment"
							:style="{ backgroundImage: `url(${ comment.user.avatar || '/img/user.png' })` }">
							</div>
						<div class="post_top_name post_top_name_comment">{{ comment.user.name }}</div>
					</router-link>

					<div class="flex aic post_top_r">
						<div class="post_top_time">{{ comment.updated_at }}</div>
						<div class="go_post_sett">
							<Context></Context>
						</div>
					</div>
				</div>
				<div class="comments_cont">
					<p class="comments_text">{{ comment.text }}</p>
					<div class="comments_reply">Ответить</div>
				</div>
			</div>
		</div>
		<div v-else>
			<br>
			Нет комментариев
		</div>

		<form class="form_comm_add"
			@submit.prevent="submit">
			<div class="message_write_input flex sb">
				<input type="text" class="mwi_real" placeholder="Написать комментарий"
					v-model="form.text">

				<div class="message_write_controls flex">
					<button class="message_write_btn">
						<img src="/img/message-icon-green.svg" alt="">
					</button>
				</div>
			</div>
		</form>
	</div>
</template>

<script>

import HTTP from '@/utils/http'
import Context from '@/components/@elements/Context'

export default {

	components: {
		Context
	},
	props: {
		comments: Array,
		post_id: Number,
	},
	data()
	{
		return {
			form: {
			},
		};
	},
	created() {
		this.form.type_id = this.post_id;

		this.$echo.channel('comment')
			.listen('CommentAdd', (e) => {
				this.$emit('commented', e);
			});
	},
	computed: {

	},
	methods: {
		submit(e)
		{
			HTTP.post('/comment', this.form)
				.then((data) => {
					this.$emit('commented', data.comment);
					this.form.text = '';
				})
				.catch((error) => {
					console.log('error:', error)
				});
		},
	}
};
</script>
