<template>
	<div class="select">
		<div class="select-header input"
			@click="isOpen = !isOpen">
			<span class="select_name">{{ selected.name }}</span>
			<i class="fal fa-chevron-down select-arrow_down"
					v-show="!isOpen"></i>
			<i class="fal fa-chevron-up"
				v-show="isOpen"></i>
		</div>

		<div class="select_drop"
			v-show="isOpen">
			<div class="select_search"
				v-if="search">
				<input type="text" class="input" placeholder="Найти">
			</div>

			<div class="select_list">
				<div class="select_item"
					v-for="(item, key) in items.data"
					:key="key"
					:data-id="item.id"
					:class="{active: item.active}"
					@click="
						items.data.forEach(elem => {if (item.id != elem.id) elem.active = false}),
						selected = item,
						item.active = true,
						isOpen = false,
						$emit('select', item)">
					{{ item.name }}
				</div>
			</div>
		</div>
	</div>
</template>

<script>

export default {
	props: {
		items: Object,
		current: Number,
	},
	data() {
		return {
			isOpen: false,
			search: null,
			selected: null
		}
	},
	created()
	{
		this.search = this.items.search;
		this.selected = this.current ? this.items.data[ this.current - 1 ] : this.items.data[0];

		this.items.data[0].active = true;
		this.$emit('select', this.selected);
	},
	methods:
	{

	},
};
</script>
