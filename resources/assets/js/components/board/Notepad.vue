<template>
	<div class="widget_item widget_item_wether">
		<div class="widget_h">Блокнот</div>

		<div class="wiw_block flex">
			<textarea class="wiw_note"
				placeholder="Здесь можно что-то написать, и текст сохранится, когда вы кликните вне поля"
				v-model="form.settings.text"
				@change="saveText"></textarea>
		</div>
	</div>
</template>

<script>

import HTTP from '@/utils/http'

export default {

	props: {
		note: Object
	},
	data()
	{
		return {
			form: {
				settings: {
					text: ''
				}
			}
		};
	},
	created() {
		this.form.settings.text = this.note.settings ? this.note.settings.text : '';
		this.form.id = this.note.id;
	},
	computed: {

	},
	methods: {
		saveText()
		{
			HTTP.post('/widgets', this.form)
				.then(resp => {
					console.log('resp:', resp)
				});
		}
	}
};
</script>
