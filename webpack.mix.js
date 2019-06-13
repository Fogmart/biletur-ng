const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.ts('frontend/assets/src/ts/test.ts', 'frontend/web/js/biletur.js')
mix.js('frontend/assets/src/js/commonPlugin.js', 'frontend/web/js/biletur.js')
mix.js('frontend/assets/src/js/searchExcursionPlugin.js', 'frontend/web/js/biletur.js')
    .version();

mix.js('frontend/assets/src/js/widgetPlugin.js', 'frontend/web/js/widget.js')
	.version();

mix.sass('frontend/assets/src/sass/biletur.scss', 'frontend/web/css')
mix.sass('frontend/assets/src/sass/excursion.scss', 'frontend/web/css')
mix.sass('visa/assets/src/sass/visa.scss', 'visa/web/css')
	.options({
		imgLoaderOptions: {enabled: false},
		postCss: [
			require('postcss-css-variables')()
		]
	});

mix.styles([
	'frontend/web/css/biletur.css',
	'frontend/web/css/excursion.css',
	'frontend/assets/src/sass/fonts.css'
], 'frontend/web/css/biletur.css')
	.version();

mix.setPublicPath('frontend/web/');
