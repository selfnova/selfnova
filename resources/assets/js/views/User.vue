<template>
	<div>
		<div class="container">
			<div class="user_top flex sb">
				<div class="user_top_l">
					<div class="user_top_h flex aic">
						<span>{{ userPage.full_name }}</span>

						<Context></Context>
					</div>

					<div class="last_visit">
						Последнее посещение {{ userPage.last_visit }}
					</div>
					<div class="user_top_btns flex aic">
						<div class="btn_mini"
							v-if="!isMyPage"
							@click="write">
							<img width="20" src="/img/write.svg" alt="">
							Написать
						</div>

						<div class="btn_mini btn_follow"
							v-if="!isMyPage">%btn_t%</div>

						<router-link class="btn_mini"
							:to="{ name: 'settings'}"
							v-if="isMyPage">
							<img src="/img/setting-white.svg" alt="">
							Настройки
						</router-link>
					</div>

					<hr>

					<div class="user_top_info flex sb">
						<div class="user_top_info_item">
							<div class="user_top_info_item_h">О себе</div>
							<div class="user_top_info_item_t">
								<p class="user_top_info_item_t"
									v-if="userPage.about">
									{{ userPage.about }}
								</p>
								<p class="user_top_info_item_t"
									v-else>
									Не заполнено
								</p>
							</div>
						</div>
						<div class="user_top_info_item">
							<div class="user_top_info_item_h">Информация</div>
							<p class="user_top_info_item_t">
								<b>День рождения:</b> {{ userPage.born }}
							</p>
							<p class="user_top_info_item_t">
								<b>Город:</b> {{ userPage.city }}
							</p>
							<p class="user_top_info_item_t"
								v-if="userPage.site">
								<b>Сайт:</b> <a target="_blank" class="link_a"
									:href="userPage.site">
									{{ userPage.site }}
									</a>
							</p>
						</div>
					</div>
				</div>
				<div class="user_top_r">
					<div class="user_top_avr"
						:style="{ backgroundImage: `url(${ userPage.avatar || '/img/user.png' })` }">

						<div class="user_top_choose_avr"
							v-if="isMyPage">
							<div class="user_top_choose_avr_cam">
								<img src="/img/cam.svg" alt="">
							</div>
						</div>
					</div>

					<div class="user_top_counts flex sb">
						<div class="utc_item"><img src="/img/ic1.svg" alt="">{{ userPage.followers }}</div>
						<div class="utc_item"><img src="/img/ic2.svg" alt="">{{ userPage.followings }}</div>
						<div class="utc_item"><img src="/img/ic3.svg" alt="">{{ userPage.groups }}</div>
					</div>
				</div>
			</div>
		</div>

		<div class="grey">
			<div class="container article" style="display: block;">
				<div class="article_h_wrap flex sb aic">
					<div class="flex aic">
						<div class="article_h">Записи</div>

						<div class="article_link"
							v-if="isMyPage"
							@click="isOpenAddForm = !isOpenAddForm">Добавить запись</div>
					</div>

					<div class="article_count">{{ posts.length }}</div>
				</div>

				<AddPost
					v-if="isMyPage"
					:isOpen="isOpenAddForm"
					@posted="posts.unshift($event), isOpenAddForm = false"
				></AddPost>

				<Post v-if="posts.length"
					:posts="posts"></Post>

				<div class="tac"
					v-else>
					<br>
					<br>
					<img src="/img/nopost.svg" alt=""><br><br>
					Нет записей
					<br>
					<br>
					<br>
				</div>

			</div>
		</div>

		<div class="popup popup_complaint">
			<form id="form_complaint" method="post" action="/complaint" class="popup_cont popup_complaint_cont">
				<div class="popup_complaint_h">Причина жалобы</div>
				<input type="text" class="input popup_complaint_inp" name="text">
				<input type="hidden" name="place" id="complaint_place">

				<button class="btn_mini">Отправить</button>
				<div class="close"></div>
			</form>

			<div class="mask"></div>
		</div>
	</div>

</template>

<script>

import HTTP from '@/utils/http'

import Context from '@/components/@elements/Context'
import AddPost from '@/components/@elements/AddPost'
import Post from '@/components/@elements/Post'

export default {
	components: {
		Context, AddPost, Post
	},

	data() {
		return {
			userPage: {},
			posts: [],
			isOpenAddForm: false
		}
	},
	computed: {
		isMyPage()
		{
			return this.userPage.id == this.$store.state.main.user.id;
		}
	},
	created()
	{
		HTTP.get( this.$router.currentRoute.path )
			.then(resp => {
				this.userPage = resp.user;
				this.posts = resp.user.posts;

				document.title = this.userPage.full_name;
			});
	},
	methods: {
		write()
		{
			HTTP.post( '/chats', {u_id: this.userPage.id, type: 1} )
				.then(resp => {
					this.$router.push({name: 'chatOne', params: {id: resp.chat_id}})
				});
		}
	}
};
</script>
