<template>
	<div class="container">
		<div class="sett_saved"
			v-show="saved">Изменения сохранены</div>

		<form class="sett_wrap"
			@submit.prevent="submit">

			<div class="sett_right_h">Общие настройки</div>

			<div class="sett_line">
				<div class="sett_line_h">Имя</div>
				<div class="sett_line_t">{{ user.full_name }}</div>
			</div>

			<div class="sett_line"
				v-if="user.alias">
				<div class="sett_line_h">Короткая ссылка</div>
				<a href="/" class="sett_line_t link_a">https://selfnova.com/{{ user.alias }}</a>
			</div>

			<div class="sett_line">
				<div class="sett_line_h">День рождения</div>
				<div class="sett_line_t">{{ user.born }}</div>
			</div>

			<div class="sett_line">
				<div class="sett_line_h">Страна</div>
				<input type="text" class="input"
					v-model="user.country">
			</div>

			<div class="sett_line">
				<div class="sett_line_h">Город</div>
				<input type="text" class="input"
					v-model="user.city">
			</div>

			<div class="sett_line">
				<div class="sett_line_h">О себе</div>
				<textarea class="sett_line_area input"
					v-model="user.about"></textarea>
			</div>
			<div class="sett_line">
				<div class="sett_line_h">Телефон</div>
				<input type="text" class="input"
					v-model="user.phone">
			</div>
			<div class="sett_line">
				<div class="sett_line_h">E-mail</div>
				<input type="text" class="input"
					v-model="user.email">
			</div>
			<div class="sett_line">
				<div class="sett_line_h">Сайт</div>
				<input type="text" class="input"
					v-model="user.site">
			</div>

			<div class="sett_right_h">Настройки приватности</div>

			<div class="sett_line">
				<div class="sett_line_h">Кто может писать вам</div>
				<select-list
					v-if="loaded"
					:items="write_me"
					:current="private_set.write_me"
					@select="private_set.write_me = $event.id"></select-list>
			</div>

			<div class="sett_line">
				<div class="sett_line_h">Кто может приглашать в группы</div>
				<select-list
					v-if="loaded"
					:items="invite_me"
					:current="private_set.invite_me"
					@select="private_set.invite_me = $event.id"></select-list>
			</div>

			<div class="sett_line">
				<div class="sett_line_h">Получать уведомления</div>
				<select-list
					v-if="loaded"
					:items="notice_me"
					:current="private_set.notice_me"
					@select="private_set.notice_me = $event.id"></select-list>
			</div>

			<div class="sett_right_h">Изменить пароль</div>

			<div class="sett_line">
				<div class="sett_line_h">Старый пароль</div>
				<input type="password" name="old_pass" class="input" value="">
			</div>
			<div class="sett_line">
				<div class="sett_line_h">Новый пароль</div>
				<input type="password" name="new_pass" class="input" value="">
			</div>
			<div class="sett_line">
				<button name="save" class="btn_mini" value="">Сохранить</button>
			</div>
		</form>

		<div class="acc_del grey tac">
			<h2>Удаление анкеты</h2>
			<p>
				Для того, чтобы удалить анкету, нажмите на ссылку ниже. Анкета будет удалена безвозвратно спустя 7 дней, в течение которых вы сможете отменить удаление. Все проставленные с анкеты голоса и другие действия на сторонних сайтах будут списаны.
			</p>

			<a href="?accdel=1" class="link_a">Удалить аккаунт</a>
		</div>
	</div>
</template>

<script>

import HTTP from '@/utils/http'
import SelectList from '@/components/@elements/Select'

export default {
	components: { SelectList },

	data() {
		return {
			saved: false,
			user: {},
			write_me:
			{
				data: [
					{ id: 1, name: 'Все', active: false },
					{ id: 2, name: 'Только друзья', active: false },
					{ id: 3, name: 'Никто', active: false }
				]
			},
			invite_me:
			{
				data: [
					{ id: 1, name: 'Все', active: false },
					{ id: 2, name: 'Только друзья', active: false },
					{ id: 3, name: 'Никто', active: false }
				]
			},
			notice_me:
			{
				data: [
					{ id: 1, name: 'Все', active: false },
					{ id: 2, name: 'Только друзья', active: false },
					{ id: 3, name: 'Никто', active: false }
				]
			},
			private_set: {},
			loaded: false
		}
	},
	created()
	{
		HTTP.get( '/settings' )
			.then(resp => {
				this.user = resp.user;
				if ( resp.user.private_set ) this.private_set = this.user.private_set;
				else this.user.private_set = this.private_set;

				this.loaded = true;
			});
	},
	methods: {
		submit()
		{
			HTTP.put( '/settings/0', this.user )
				.then(resp => {
					window.scrollTo(0, 0);
					this.saved = true;
					setTimeout(() => {this.saved = false;}, 5000);
				});
		}
	}
};
</script>
