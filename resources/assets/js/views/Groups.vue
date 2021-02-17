<template>
	<div class="container">
		<div class="">
			<form class="cont_search">
				<input type="text" class="input" name="search" placeholder="Найти группу">
			</form>

			<a href="/groups/add" class="btn_mini btn_follow active btn_group_mob">Создать группу</a>
		</div>

		<h3>%header%</h3><br>
		{%group%}
		<div class="gu_line peoples_line flex aic">
			<a href="/groups/%id%" class="gu_line_avr" style="background-image: url('%avatar%');"></a>

			<div class="gu_line_right flex sb aic">
				<div>
					<a href="/groups/%id%" class="gu_line_h h4">%name%</a>
					<div class="gu_line_t">%city%</div>
					<div class="gu_line_t">%follow%</div>
				</div>

				<a href="" class="btn_mini btn_follow go_follow_group active" data-id="%id%">Отписаться</a>
			</div>
		</div>
		{%group%}
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
