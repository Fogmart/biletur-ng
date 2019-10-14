/* jshint node: true */
const mix = require('laravel-mix');

//Конфиг для основного сайта ----------------------------------------------------------------------
mix.ts('frontend/assets/src/ts/test.ts', 'frontend/web/js/biletur.js')
	.js('frontend/assets/src/js/layoutPlugin.js', 'frontend/web/js/biletur.js')
.js('frontend/assets/src/js/commonPlugin.js', 'frontend/web/js/biletur.js')
.js('frontend/assets/src/js/searchTourPlugin.js', 'frontend/web/js/biletur.js')
.js('frontend/assets/src/js/searchExcursionPlugin.js', 'frontend/web/js/biletur.js')
.version();

mix.js('frontend/assets/src/js/widgetPlugin.js', 'frontend/web/js/widget.js')
	.version();

mix.sass('frontend/assets/src/sass/biletur.scss', 'frontend/web/css')
.sass('frontend/assets/src/sass/select2.scss', 'frontend/web/css')
.sass('frontend/assets/src/sass/excursion.scss', 'frontend/web/css')
	.options({
		imgLoaderOptions: {enabled: false},
		postCss: [
			require('postcss-css-variables')()
		]
	});

mix.styles([
	'frontend/web/css/biletur.css',
	'frontend/web/css/excursion.css',
	'frontend/web/css/select2.css',
	'frontend/assets/src/sass/fonts.css'
], 'frontend/web/css/biletur.css')
	.version();

mix.setPublicPath('frontend/web/');

