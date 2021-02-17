<template>
	<div id="change_avatar" class="popup">
		<div class="change_avatar_cont">
			<div class="change_avatar_h">Изменить аватар</div>

			<label class="firefile_dropzone change_avatar_dropzone">
				<div>
					Перетащите файл сюда <br> или <br>
					<div class="btn_mini"><img src="/assets/images/camera-white.svg" alt="">Выбрать фото</div>
					<input type="file" class="firefile" multiple>
				</div>
			</label>

			<div class="firefile_editzone">
				<img id="firefile_back_img" src="" alt="">

				<div id="firefile_crop_select" style="top: 20px;left: 20px;width: 100px;height: 100px;">
					<div class="firefile_point point_tl"></div>
					<div class="firefile_point point_tr"></div>
					<div class="firefile_point point_br"></div>
					<div class="firefile_point point_bl"></div>

					<div class="firefile_portal">
						<img id="firefile_portal_img" draggable="false" src="" alt="">
					</div>
				</div>

				<div class="firefile_editzone_mask"></div>
			</div>

			<div id="firefile_upload_status">
				<div class="fus_wrap">
					<div class="fus_text"></div>
					<div class="fus_progress_bar">
						<div class="fus_fill"></div>
					</div>
				</div>
			</div>

			<div class="change_avatar_btns">
				<div id="firefile_save" class="btn_mini">Сохранить</div>
				<div id="firefile_close" class="btn_mini cancle close_btn">Отмена</div>
			</div>

			<div class="close"></div>
		</div>
		<div style="overflow: hidden;width: 0;height: 0;">
			<canvas id="canvas_crop"></canvas>
		</div>

		<div class="mask"></div>
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
