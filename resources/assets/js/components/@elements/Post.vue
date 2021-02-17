<template>
	<div class="post_wrap">
		<div class="post"
			v-for="post in posts"
			:key="post.id">
			<div class="post_top flex sb">
				<router-link class="post_top_l flex"
					:to="{ name: 'user', params: {id: post.u_id}}">

					<div class="post_top_avr"
						:style="{ backgroundImage: `url(${ post.user.avatar || '/img/user.png' })` }"></div>
					<div class="post_top_name">{{ post.user.name }}</div>
				</router-link>

				<div class="flex aic post_top_r">
					<div class="post_top_time">{{ post.updated_at }}</div>
					<Context></Context>
				</div>
			</div>

			<div class="post_top post_top__repost"
				v-if="post.repost">
				<Post :posts="[post.repost]"></Post>
			</div>

			<div class="post_info">
				<div class="post_subject">{{ post.subject }}</div>
				<p class="post_body">{{ post.text }}</p>
			</div>

			<form action="#post%id%" method="post" class="post_edit_form">
				<input type="text" name="subject" class="post_subject post_fields input" value="%subject%">
				<textarea name="text" class="post_fields post_text input">%text%</textarea>

				<div class="post_edit_btn">
					<button class="btn_mini">сохранить</button>
					<input type="hidden" name="id" value="%id%">
					<div class="btn_mini go_cancel_edit_post">отмена</div>
				</div>
			</form>

			<div class="post_attach">
				<iframe frameborder="0" allowfullscreen
					allow="accelerometer; autoplay; encrypted-media; gyroscope;picture-in-picture"
					v-if="post.video"
					:src="post.video"></iframe>

				<iframe style="height: auto;max-width: 100%;" frameborder="0" allowfullscreen
					allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
					v-if="post.music"
					:src="post.music"></iframe>

				<img alt=""
					v-if="post.photos"
					:src="post.photos[0]">
			</div>
			<div class="post_share flex aic">
				<div class="psh_comm">
					<img src="/img/comments.svg" alt=""> {{ post.count_comm }}
				</div>
				<div class="psh_repost"
					:class="{ disable: post.reposted }">
					<img alt=""
						:src="'/img/repost'+ (post.reposted ? 'ed' : '') + '.svg'" >
					<span class="repost_count">{{ post.count_repost }}</span>

					<div class="psh_repost_status">Добавлено на стену</div>
				</div>
			</div>

			<Comments
				:comments="post.comments"
				:post_id="post.id"
				@commented="post.comments.push($event)"
				></Comments>
		</div>
	</div>
</template>

<script>

import Context from '@/components/@elements/Context'
import Comments from '@/components/@elements/Comments'

export default {

	name: 'Post',
	components: {
		Context, Comments
	},
	props: {
		posts: Array
	},
	data()
	{
		return {

		};
	},
	created() {

	},
	computed: {

	},
	watch: {
	},
	methods: {

	}
};
</script>
