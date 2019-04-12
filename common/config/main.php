<?php

use common\components\IpGeoBase;
use common\modules\news\MNews;
use common\modules\seo\MSeo;
use common\modules\message\MMessage;

return [
	'language' => 'ru-RU',
	'aliases'    => [
		'@bower'   => '@vendor/bower-asset',
		'@npm'     => '@vendor/npm-asset',
	],
	'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',

	'modules'    => [
		'rbac' => [
			'class' => 'yii2mod\rbac\Module',
		],
		'news' => [
			'class' => MNews::class,
		],
		'seo'  => [
			'class' => MSeo::class,
		],
		'message' => [
			'class' => MMessage::class,
		]
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
		'authManager' => [
			'class' => 'yii\rbac\DbManager',
			'defaultRoles' => ['guest', 'user'],
		],
	],
	'as access' => [
		'class' => yii2mod\rbac\filters\AccessControl::class,
		'allowActions' => [
			'site/*',
			'avia/*',
			'rail-road/*',
		]
	],
];
