const mix = require('laravel-mix');
require('laravel-mix-alias');

mix.disableNotifications();
mix.alias({
    '@': '/resources/assets/js'
});

mix.setPublicPath('public')
	.js('resources/assets/js/app.js', 'js')
	.sass('resources/assets/sass/app.sass', 'css');


if (mix.inProduction()) {
	mix.version()
} else {
	mix.webpackConfig({
		devtool: 'eval'
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
