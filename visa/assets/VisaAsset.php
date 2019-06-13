<?php
namespace visa\assets;

use frontend\assets\AppAsset;

/**
 *
 * Visa Assets
 *
 */
class VisaAsset extends AppAsset {
	public $basePath = '@webroot';

	public $baseUrl = '@web';

	public $css = [
		'css/visa.css',
	];

	public $js = [
		//'/js/visa-biletur.js',
	];

	public $depends = [
		'yii\bootstrap\BootstrapAsset',
	];
}
