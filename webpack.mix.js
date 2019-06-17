/* jshint node: true */


const mixCommon = require('laravel-mix');
const mixVisa = require('laravel-mix');

//Конфиг для основного сайта ----------------------------------------------------------------------
mixCommon.ts('frontend/assets/src/ts/test.ts', 'frontend/web/js/biletur.js')
.js('frontend/assets/src/js/commonPlugin.js', 'frontend/web/js/biletur.js')
.js('frontend/assets/src/js/searchExcursionPlugin.js', 'frontend/web/js/biletur.js')
.version();

mixCommon.js('frontend/assets/src/js/widgetPlugin.js', 'frontend/web/js/widget.js')
	.version();

mixCommon.sass('frontend/assets/src/sass/biletur.scss', 'frontend/web/css')
.sass('frontend/assets/src/sass/excursion.scss', 'frontend/web/css')
	.options({
		imgLoaderOptions: {enabled: false},
		postCss: [
			require('postcss-css-variables')()
		]
	});

mixCommon.styles([
	'frontend/web/css/biletur.css',
	'frontend/web/css/excursion.css',
	'frontend/assets/src/sass/fonts.css'
], 'frontend/web/css/biletur.css')
	.version();

mixCommon.setPublicPath('frontend/web/');

//Тут собираем файлы для лэндинга visa -------------------------------------------------------------
mixVisa.sass('visa/assets/src/sass/visa.scss', 'visa/web/css')
	.options({
		imgLoaderOptions: {enabled: false},
		postCss: [
			require('postcss-css-variables')()
		]
	})
	.version();

mixVisa.setPublicPath('visa/web/');

