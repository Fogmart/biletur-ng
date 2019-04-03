const { mix } = require('laravel-mix');

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

mix.js('vendor/bower-asset/jquery/dist/jquery.js', 'frontend/web/js/vendor.js')
	//.js('app/app.js', 'web/js/app-biletur.js')
    .js('vendor/yiisoft/yii2/assets/yii.js', 'frontend/web/js/vendor.js')
    .version();

mix.sass('frontend/assets/src/sass/biletur.scss', 'frontend/web/css')
   /* .options({
        postCss: [
            require('postcss-css-variables')()
        ]
    })*/
    .version();

mix.setPublicPath('frontend/web/');
