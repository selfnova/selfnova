<template>
	<div class="container">
		<div class="notifications">
			<div class="notifications_item">

			</div>
		</div>
		<h3 class="main_h">Новые</h3>
		<div>
			{%notif%}
			<div class="gu_line group_line flex sb aic">
				<div class="flex aic">
					<div class="gu_line_avr gu_line_avr__notice" style="background-image: url('%avatar%');"></div>

					<div class="gu_line_block">
						<a href="/user/%id%" class="gu_line_h h4"><b>%name%</b></a> оставил комментарий на вашей стене
						<div class="gu_line_t gu_line_t__notice">%date%</div>
					</div>
				</div>
			</div>
			{%notif%}
		</div>
	</div>
</template>

<script>

import HTTP from '@/utils/http'

export default {
	components: {  },

	data() {
		return {
			user: 'Test'
		}
	},
	created()
	{

		HTTP.get('/board')
			.then(resp => {
				this.news = resp.news;
				this.following_posts = resp.following_posts;
				this.groupPosts = resp.group_posts;
				this.widgets = resp.widgets;
			});
	},
	methods: {

	}
};
</script>
