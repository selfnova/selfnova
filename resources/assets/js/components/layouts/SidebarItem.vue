<template>
	<li class="sb-munu_main"
		:class="{opened: isOpen, 'sb-munu_toggle': item.children}"
		>

		<div v-if="item.children">
			<span class="sb-li flex sb aic"
				@click="isOpen = isOpen ? false : true">
				<span class="sb-munu_toggle_h">{{ item.name }}</span>
				<span>
					<i class="fal fa-chevron-down select-arrow_down"></i>
					<i class="select-arrow_up fal fa-chevron-up"></i>
				</span>
			</span>
			<ul class="sb-menu_more">
				<SidebarLink
					v-for="(link, key) in item.children"
					:key="key"
					:link="link" />
			</ul>
		</div>
		
		<router-link class="flex aic"
			v-else
			:to="{ name: item.route}" >
			<i class="fal" :class="item.icon"></i> {{ item.name }}
		</router-link>
	</li>
</template>

<script>
import SidebarLink from './SidebarLink.vue'

export default {
	components: {SidebarLink},
	props: {
    	item: Object
    },
	data()
	{
		return {
			isOpen: false
		};
	},
	created() {
		if ( !this.item.children ) return false

		if ( this.item.children.find(obj => obj.route == this.$route.name) ) this.isOpen = true
	},
	computed: {
		
	},
	watch: {
	},
	methods: {
		
	}
};
</script>