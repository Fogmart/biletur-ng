/* jshint node: true */
const mix = require('laravel-mix');

mix.styles([
	'backend/assets/src/css/site.css'
], 'backend/web/css/site-admin.css')
	.version();

mix.setPublicPath('backend/web/');

