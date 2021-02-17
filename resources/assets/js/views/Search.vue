<template>
	<div>
		<div class="container wrap">
	<form action="/search" class="cont_search mob_search">
		<input type="text" class="input" name="s" placeholder="Что будем искать?" value="">
		<button class="search_btn flex aic jcc">
			<img src="/assets/images/search.svg" alt="">
		</button>
	</form>

	<h3>Люди</h3>

	{%people_res%}
	<div class="gu_line peoples_line flex aic">
		<a href="/user/%id%" class="gu_line_avr" style="%avatar%"></a>

		<div class="gu_line_right flex sb aic">
			<div>
				<a href="/user/%id%" class="gu_line_h h4">%name%</a>
				<div class="gu_line_t">%city%</div>
				<div class="gu_line_t">%follow%</div>
			</div>

			<div class="btn_mini btn_follow go_follow_user %btn_cl%" data-id="%id%">%btn_t%</div>
		</div>
	</div>
	{%people_res%}

	<br><br>
	<h3>Группы</h3>

	{%group_res%}
	<div class="gu_line search_people_line flex sb aic">
		<div class="flex aic">
			<div class="gu_line_avr" style="background-image: url('%avatar%');"></div>

			<div>
				<a href="/groups/%id%" class="gu_line_h h4">%name%</a>
				<div class="gu_line_t">%city%</div>
				<div class="gu_line_t">%follow%</div>
			</div>
		</div>

		<div class="btn_mini btn_follow go_follow_group %btn_cl%" data-id="%id%">%btn_t%</div>
	</div>
	{%group_res%}

	<br><br>
	<h3>Новости</h3>

	{%news_res%}
	<div>
		<a href="/news/%alias%" class="serach_news_link">%name%</a>
	</div>
	{%news_res%}
	<br><br><br>
</div>

<div class="grey">
	{%_none%}
	<div class="tac">
		<br>
		<br>
		<img src="/assets/images/nopost.svg" alt=""><br><br>
		Записи не найдены
		<br>
		<br>
		<br>
	</div>
	{%_none%}

	<div class="container article">
		<h3>Записи</h3>
		<br>
		{%posts_res%}
		<div class="post">
			<div class="post_top flex sb">
				<div class="flex">
					<a href="%url%" class="post_top_avr" style="background-image: url('%avatar%');"></a>
					<a href="%url%" class="post_top_name">%name%</a>
				</div>

				<div class="flex aic">
					<div class="post_top_time">%time%</div>
				</div>
			</div>
			<div class="post_subject">%subject%</div>
			<p class="post_body">%text%</p>
			<div class="post_share flex aic">
				<div class="psh_comm">
					<img src="/assets/images/comments.svg" alt=""> 2
				</div>
				<div class="psh_repost">
					<img src="/assets/images/repost.svg" alt=""> 6
				</div>
			</div>
		</div>
		{%posts_res%}
	</div>
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
