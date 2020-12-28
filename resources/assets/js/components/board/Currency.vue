<template>
	<div class="widget_item widget_item_wether">
		<div class="widget_h">Курсы валют</div>

		<div class="wiw_block flex">
			<div class="wiw_block_item">
				<div class="wiw_block_name">Доллар</div>
				<div class="wiw_block_num">${{ currency.usd.value }}</div>
				<div class="wiw_block_change"
					:class="{ plus: currency.usd.value > 0 }">
					{{ currency.usd.value > 0 ? '+' : '-' }}{{ currency.usd.change }}
				</div>
			</div>
			<div class="wiw_block_item">
				<div class="wiw_block_name">Евро</div>
				<div class="wiw_block_num">€{{ currency.eur.value }}</div>
				<div class="wiw_block_change"
					:class="{ plus: currency.eur.value > 0 }">
					{{ currency.eur.value > 0 ? '+' : '-' }}{{  currency.eur.change }}
				</div>
			</div>
		</div>
	</div>
</template>

<script>

import HTTP from '@/utils/http'

export default {

	props: {
		curr: Object
	},
	data()
	{
		return {
			currency: {
				usd: {
					value: 0,
					change: 0
				},
				eur: {
					value: 0,
					change: 0
				}
			}
		};
	},
	created() {
		let url  = 'https://iss.moex.com/iss/engines/currency/markets/selt/securities.json';

		HTTP.getUrl( url )
			.then( res => {
				let data = res.marketdata.data;
				let round = ( num ) => (Math.round( num * 100 ) / 100).toFixed(2);

				this.currency.usd.value = round( data[87][8] );
				this.currency.usd.change = round( data[87][28] );

				this.currency.eur.value = round( data[36][8] );
				this.currency.eur.change = round( data[36][28] );
			});
	},
	computed: {
		userId()
		{
			return this.post.user ? this.post.user.id : this.post.group.id;
		}
	},
	watch: {
	},
	methods: {

	}
};
</script>
