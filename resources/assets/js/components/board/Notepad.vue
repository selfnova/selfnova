<template>
	<div class="widget_item widget_item_wether">
		<div class="widget_h">Блокнот</div>

		<div class="wiw_block flex">
			<textarea class="wiw_note"
				placeholder="Здесь можно что-то написать, и текст сохранится, когда вы кликните вне поля"
				v-model="note.settings.text"></textarea>
		</div>
	</div>
</template>

<script>

export default {

	props: {
		note: Object
	},
	data()
	{
		return {

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
