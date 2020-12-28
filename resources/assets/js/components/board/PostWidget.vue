<template>
	<div class="widget_post_item">
		<div class="wpi_header flex aic">
			<router-link class="wpi_avr"
				:style="userAvatar"
				:to="{ name: userType, params: { id: userId } }" ></router-link>

			<router-link class="wpi_name"
				:to="{ name: userType, params: { id: userId } }" >
				{{ userName }}
			</router-link>
		</div>

		<div class="post_top post_top__repost"
			v-if="post.repost">

			<div class="flex">
				<router-link class="wpi_avr"
					:style="userRepost.avatar"
					:to="{ name: userType, params: { id: userRepost.id } }" >
				</router-link>
				<div>
					<router-link class="post_top_name"
						:to="{ name: userType, params: { id: userRepost.id } }" >
						{{ userRepost.name }}
					</router-link>

					<div class="post_top_time">{{ post.repost.date }}</div>
				</div>
			</div>
		</div>

		<div>
			<div class="wpi_h">{{ post.repost ? post.repost.subject : post.subject}}</div>
			<div class="wpi_t">
				<div class="wpi_t_cont"
					v-html="post.repost ? post.repost.text : post.text">
				</div>
			</div>
			<div class="link_a wpi_show"
				v-show="post.text > 300">Показать полностью</div>
		</div>

		<div class="wpi_info flex aic sb">
			<div class="wpi_date">{{ post.date }}</div>
			<div class="wpi_comments flex sb">
				<span>{{ +post.count_comm }}</span>
				<img src="/img/comments.svg" alt="">
			</div>
		</div>
	</div>
</template>

<script>

import URL from '@/utils/url'

export default {

	props: {
		post: Object
	},
	data()
	{
		return {

		};
	},
	created() {

	},
	computed: {
		userId()
		{
			return this.post.user ? this.post.user.id : this.post.group.id;
		},
		userName()
		{
			return this.post.user ? this.post.user.name : this.post.group.name;
		},
		userAvatar()
		{
			let img = '';

			if ( this.post.user )
				img = URL.getUserAvatar( this.post.user.id, this.post.user.avatar );

			if ( this.post.group )
				img = URL.getGroupAvatar( this.post.group.id, this.post.group.avatar );

			return { backgroundImage: `url(${ img })` };
		},
		userType()
		{
			return this.post.user ? 'users' : 'groups';
		},
		userRepost()
		{
			let userRepost = {};
			let img = '';

			if ( this.post.repost.user )
			{
				userRepost = this.post.repost.user;
				img = URL.getUserAvatar( userRepost.id, userRepost.avatar );
			}

			if ( this.post.repost.group )
			{
				userRepost = this.post.repost.group;
				img = URL.getGroupAvatar( userRepost.id, userRepost.avatar );
			}

			userRepost.avatar = {
				backgroundImage: `url(${ img })`
			};

			return userRepost;
		}
	},
	watch: {
	},
	methods: {

	}
};
</script>
