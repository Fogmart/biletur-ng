const {mix} = require('laravel-mix');

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

mix.js('frontend/assets/src/js/commonPlugin.js', 'frontend/web/js/biletur.js')
    .version();

mix.sass('frontend/assets/src/sass/biletur.scss', 'frontend/web/css')
	.options({
		imgLoaderOptions: {enabled: false},
		postCss: [
			require('postcss-css-variables')()
		]
	})
	.version();

mix.setPublicPath('frontend/web/');
