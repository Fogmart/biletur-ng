<?php

use common\components\IpGeoBase;
use common\modules\news\MNews;
use common\modules\seo\MSeo;

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
		'env'       => [
			'class'              => common\components\Environment::class,
			'defaultCityId'      => '_1CK0R7WDW',
			'defaultLanguage'    => 'ru',
			'defaultAirportCode' => 'VVO',
			'defaultArrCityId'   => '957979',
			'defaultTourZone'    => 3
		],
		'cache'     => [
			'class' => 'yii\caching\FileCache',
		],
		'ipgeobase' => [
			'class'      => IpGeoBase::class,
			'useLocalDB' => false,
		],
	],
];
