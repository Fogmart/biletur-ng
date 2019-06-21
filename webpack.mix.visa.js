/* jshint node: true */
const mix = require('laravel-mix');

mix.sass('visa/assets/src/sass/visa.scss', 'visa/web/css')
	.options({
		imgLoaderOptions: {enabled: false},
		postCss: [
			require('postcss-css-variables')()
		]
	})
	.version();

mix.setPublicPath('visa/web/');

