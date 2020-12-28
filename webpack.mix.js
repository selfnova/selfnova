const mix = require('laravel-mix');
require('laravel-mix-alias');

mix.disableNotifications();
mix.alias({
    '@': '/resources/assets/js'
});

mix.setPublicPath('public')
	.js('resources/assets/js/app.js', 'js')
	.sass('resources/assets/sass/app.sass', 'css')
	.copyDirectory('resources/assets/img', 'public/img');


if (mix.inProduction()) {
	mix.version()
} else {
	mix.webpackConfig({
		devtool: 'eval-source-map'
	})
	.sourceMaps();

	mix.browserSync({
		proxy: process.env.APP_URL,
		files: [
			'resources/views/**/*.php',
			'app/**/*.php',
			'routes/**/*.php',
			'public/**/css/*.css',
			'public/**/js/*.js',
			'public/**/img/*.*',
		]
	})
}
