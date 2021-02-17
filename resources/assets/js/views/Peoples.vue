<template>
	<div class="container">
		<form class="cont_search"
			@submit.prevent="search">
			<input type="text" class="input" placeholder="Поиск по людям"
				v-model="form.search">
		</form>

		<div class="page_tab"
			v-show="!search_res">
			<div class="p_tab_item"
				:class="{active: tab_followings}"
				@click="showFollowings">Вы подписаны</div>
			<div class="p_tab_item"
				:class="{active: !tab_followings}"
				@click="showFollowers">На вас подписаны</div>
		</div>

		<h2 class="people_h"
			v-if="search_res">Поиск людей</h2>

		<div class="people_wrap"
			v-if="users && users.length">
			<UserLine
				v-for="(item, key) in users"
				:key="key"
				:item="item"
				@unfollow="followings = users.filter(item => (item.id != $event.id) && tab_followings)"></UserLine>
		</div>
		<div v-else>{{ notFound }} <br><br></div>
	</div>
</template>

<script>

import HTTP from '@/utils/http'
import UserLine from '@/components/@elements/UserLine'

export default {
	components: { UserLine },

	data() {
		return {
			tab_followings: true,
			form: {},
			search_res: null,
			followers: null,
			followings: [],
		}
	},
	computed: {
		users()
		{
			if ( this.search_res ) return this.search_res;

			return this.tab_followings ? this.followings : this.followers;
		},
		notFound()
		{
			if ( this.search_res && this.search_res.length === 0 )
				return 'Ничего не найдено';

			if ( !this.followings.length && this.tab_followings )
				return 'Вы не подписаны ни на одного человека';

			if ( !this.followers && !this.tab_followers )
				return 'На вас не подписан ни один человек';
		}
	},
	created()
	{
		this.getFollowings();
	},
	methods: {
		search()
		{
			HTTP.post('/peoples/search', this.form)
				.then(resp => {
					this.search_res = resp.peoples;
				});
		},
		getFollowings()
		{
			HTTP.get('/peoples/followings')
				.then(resp => {
					if ( resp.length )
						this.followings = resp;
				});
		},
		getFollowers()
		{
			HTTP.get('/peoples/followers')
				.then(resp => {
					if ( resp.length )
						this.followers = resp;
				});
		},
		showFollowings()
		{
			this.tab_followings = true;

			if ( !this.followings.length )
				this.getFollowings();
		},
		showFollowers()
		{
			this.tab_followings = false;

			if ( !this.followers )
				this.getFollowers();
		}
	}
};
</script>
