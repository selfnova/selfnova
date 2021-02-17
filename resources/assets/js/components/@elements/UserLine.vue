<template>
	<div class="gu_line peoples_line flex aic">
		<router-link class="gu_line_avr"
			:to="{ name: 'user', params: {id: item.id}}"
			:style="{ backgroundImage: `url(${ item.avatar || '/img/user.png' })` }">
		</router-link>

		<div class="gu_line_right flex sb aic">
			<div>
				<router-link class="gu_line_h h4"
					:to="{ name: 'user', params: {id: item.id}}">
					{{ item.full_name }}
				</router-link>
				<div class="gu_line_t">{{ item.city }}</div>
				<div class="gu_line_t">{{ item.followers }} подписчиков</div>
			</div>

			<div class="btn_mini btn_follow"
				:class="{active: item.isFollow}"
				@click="follow">
				{{ item.isFollow ? 'Отписаться' : 'Подписаться' }}
			</div>
		</div>
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
		item: Object
	},
	data()
	{
		return {
			form: {
			},
		};
	},
	created() {

	},
	computed: {

	},
	methods: {
		follow(e)
		{
			HTTP.post('/peoples/follow', { id: this.item.id })
				.then(resp => {
					if ( this.item.isFollow )
					{
						this.item.followers--;
						this.$emit('unfollow', this.item);

					} else this.item.followers++;

					this.item.isFollow = !this.item.isFollow;
				});
		},
	}
};
</script>
