/* jshint node: true */
const mix = require('laravel-mix');

mix.js('backend/assets/src/js/colResizable-1.6.min.js', 'backend/web/js/biletur-admin.js')
	.version();

mix.styles([
	'backend/assets/src/css/site.css'
], 'backend/web/css/site-admin.css')
	.version();

mix.setPublicPath('backend/web/');

