<?php

use common\modules\site\news\MNews;
use common\modules\site\news\MSeo;

return [
	'aliases'    => [
		'@bower' => '@vendor/bower-asset',
		'@npm'   => '@vendor/npm-asset',
	],
	'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
	'modules'    => [
		'news' => [
			'class' => MNews::class,
		],
		'seo'  => [
			'class' => MSeo::class,
		],
	],
	'components' => [
		'cache' => [
			'class' => 'yii\caching\FileCache',
		],
	],
];
