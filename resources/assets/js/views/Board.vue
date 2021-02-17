<template>
	<div class="container">

		<form action="/search" class="mob_search">
			<input type="text" class="input" name="s" placeholder="Что будем искать?" value="">
			<button class="search_btn flex aic jcc">
				<img src="/img/search.svg" alt="">
			</button>
		</form>

		<div class="widgets_wrap flex fww">

			<div class="widget_item widget_item_news">
				<router-link class="widget_h flex"
					:to="{ name: 'news'}" >
					Новости
				</router-link>

				<div v-if="news">
					<div class="w_news_wrap"
						v-for="( news, key ) in news"
						:key="key">

						<div class="w_news_item flex">
							<div class="w_news_img">
								<img alt=""
									:src=" '/img/news/' + news.photo ">
							</div>

							<div class="w_news_info">
								<a href="/news/%alias%" class="w_news_h">{{ news.name }}</a>
								<div class="w_news_date">{{ news.date }}</div>
							</div>
						</div>
					</div>
				</div>
				<div v-else>
					Нет новостей
				</div>
			</div>

			<div class="widget_item widget_item_people">
				<router-link class="widget_h flex"
					:to="{ name: 'peoples'}" >
					Люди
				</router-link>

				<div class="widget_posts"
					v-if="following_posts.length">

					<PostWidget v-for="( post, key ) in following_posts"
						:post="post"
						:key="key" />
				</div>
				<div class="wip_nopost flex jcc aic"
						v-else>
					<div>
						Подпишитесь <br>
						на людей
						<br>
						<router-link class="btn_mini"
							:to="{ name: 'peoples'}" >
							Найти людей
						</router-link>
					</div>
				</div>
			</div>

			<div class="widget_item widget_item_groups">
				<router-link class="widget_h flex"
					:to="{ name: 'groups'}" >
					Группы
				</router-link>

				<div class="widget_posts"
					v-if="groupPosts.length">

					<PostWidget v-for="( post, key ) in groupPosts"
						:post="post"
						:key="key" />
				</div>
				<div class="wip_nopost flex jcc aic"
						v-else>
					<div>
						Подпишитесь <br>
						на группы
						<br>
						<router-link class="btn_mini"
							:to="{ name: 'groups'}" >
							Найти группы
						</router-link>
					</div>
				</div>
			</div>

			<Weather v-if="widgets.weather"
				:weather="widgets.weather"
				@updated="update" />

			<Currency v-if="widgets.currency"
				:curr="widgets.currency" />

			<Notepad v-if="widgets.notepad"
				:note="widgets.notepad" />
		</div>
	</div>
</template>

<script>

import HTTP from '@/utils/http'

import PostWidget from '@/components/board/PostWidget'
import Weather from '@/components/board/Weather'
import Currency from '@/components/board/Currency'
import Notepad from '@/components/board/Notepad'

export default {
	components: { PostWidget, Weather, Currency, Notepad },

	data() {
		return {
			news: {},
			following_posts: {},
			groupPosts: {},
			widgets: {},
		}
	},
	created()
	{
		this.update();
	},
	methods: {
		update()
		{
			HTTP.get('/board')
				.then(resp => {
					this.news = resp.news;
					this.following_posts = resp.following_posts;
					this.groupPosts = resp.group_posts;
					this.widgets = resp.widgets;
				});
		}
	}
};
</script>
