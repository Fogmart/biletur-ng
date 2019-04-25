<?php

use common\components\IpGeoBase;
use common\modules\api\ostrovok\components\OstrovokApi;
use common\modules\message\MMessage;
use common\modules\news\MNews;
use common\modules\pages\MPages;
use common\modules\seo\MSeo;

return [
	'language'   => 'ru-RU',
	'aliases'    => [
		'@bower' => '@vendor/bower-asset',
		'@npm'   => '@vendor/npm-asset',
	],
	'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',

	'modules'    => [
		'rbac'    => [
			'class'                  => 'johnitvn\rbacplus\Module',
			/** Определяем права доступа к модулю управления правами юзеров */
			'beforeCreateController' => function ($route) {
				return Yii::$app->user->can('admin');
			},
			'beforeAction'           => function ($action) {
				return true;
			}
		],
		'news'    => [
			'class' => MNews::class,
		],
		'seo'     => [
			'class' => MSeo::class,
		],
		'message' => [
			'class' => MMessage::class,
		],
		'pages'   => [
			'class' => MPages::class,
		]
	],
	'components' => [
		'user'        => [
			'identityClass' => 'mdm\admin\models\User',
			'loginUrl'      => ['site/login'],
		],
		'env'         => [
			'class'              => common\components\Environment::class,
			'defaultCityId'      => '_1CK0R7WDW',
			'defaultLanguage'    => 'ru',
			'defaultAirportCode' => 'VVO',
			'defaultArrCityId'   => '957979',
			'defaultTourZone'    => 3
		],
		'cache'       => [
			'class'        => \yii\caching\MemCache::class,
			'servers'      => [
				[
					'host' => 'localhost',
					'port' => 11211,
				],
			],
			'useMemcached' => true,
		],
		'ipgeobase'   => [
			'class'      => IpGeoBase::class,
			'useLocalDB' => false,
		],
		'authManager' => [
			'class'        => 'yii\rbac\DbManager',
			'defaultRoles' => ['guest', 'user'],
		],
	],
];
