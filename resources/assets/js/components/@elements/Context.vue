<template>
	<div class="context_wrap">
		<div class="context_btn"
			@click="isOpen = !isOpen">
			<img src="/img/setting-grey.svg" alt="">
		</div>

		<ul class="context_menu"
			v-show="isOpen">
			<li class="cm_white"></li>
			<li class="context_menu_item copy_link"
				:data-url="$router.currentRoute.path">
				Ссылка</li>

			<li class="context_menu_item">
				<a href="/settings">Настройки</a>
			</li>

			{%context_other%}
			<li class="context_menu_item context_block" data-type="user.unlock" data-id="%id%">%lock%</li>
			<li class="context_menu_item context_complaint" data-place="users/%id%">Жалоба</li>
			{%context_other%}
		</ul>
	</div>
</template>

<script>

export default {

	props: {

	},
	data()
	{
		return {
			isOpen: false
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
