<?php

use common\components\IpGeoBase;
use common\components\LogDbTarget;
use common\modules\api\MApi;
use common\modules\banner\MBanner;
use common\modules\message\MMessage;
use common\modules\news\MNews;
use common\modules\pages\MPages;
use common\modules\profile\MProfile;
use common\modules\seo\MSeo;

return [
	'bootstrap'  => ['log'],
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
		],
		'profile' => [
			'class' => MProfile::class,
		],
		'api'     => [
			'class' => MApi::class,
		],
		'banner'  => [
			'class' => MBanner::class,
		]
	],
	'components' => [
		'imageCache'   => [
			'class'      => 'iutbay\yii2imagecache\ImageCache',
			'sourcePath' => '@app/web/images/uploads',
			'sourceUrl'  => '@web/images/uploads',
			'thumbsPath' => '@app/web/images/thumb',
			//'thumbsUrl'  => '@web/site/thumbs?path=',
			'sizes'      => [
				'thumb'          => [150, 150],
				'medium'         => [300, 300],
				'large'          => [600, 600],
				'280'            => [280, 280],
				'250'            => [250, 250],
				'240'            => [240, 240],
				'200'            => [200, 200],
				'35'             => [35, 35],
				'330'            => [330, 330],
				'mobile-preview' => [310, 310],
			],
		],
		'assetManager' => [
			'linkAssets'      => true,
			'appendTimestamp' => true,
		],
		'user'         => [
			'identityClass' => \common\models\User::class,
			'loginUrl'      => ['site/login'],
		],
		'env'          => [
			'class'              => common\components\Environment::class,
			'defaultCityId'      => '_1CK0R7WDW',
			'defaultLanguage'    => 'ru',
			'defaultAirportCode' => 'VVO',
			'defaultArrCityId'   => '957979',
			'defaultTourZone'    => 3
		],
		'cache'        => [
			'class'        => \yii\caching\MemCache::class,
			'servers'      => [
				[
					'host' => 'localhost',
					'port' => 11211,
				],
			],
			'useMemcached' => true,
		],
		'ipgeobase'    => [
			'class'      => IpGeoBase::class,
			'useLocalDB' => false,
		],
		'authManager'  => [
			'class'        => 'yii\rbac\DbManager',
			'defaultRoles' => ['guest', 'user'],
		],
		'view'         => [
			'class'     => 'yii\web\View',
			'renderers' => [
				'twig' => [
					'class'     => 'yii\twig\ViewRenderer',
					'cachePath' => '@runtime/Twig/cache',
					// Array of twig options:
					'options'   => [
						'auto_reload' => true,
					],
					'globals'   => [
						'html' => ['class' => '\yii\helpers\Html'],
						/*'StarRating' => ['class' => '\kartik\rating\StarRating'],*/
					],
					'uses'      => ['yii\bootstrap'],
				],
			],
		],
		'log'          => [
			'traceLevel' => 3,
			'targets'    => [
				'db' => [
					'class'  => LogDbTarget::class,
					'levels' => ['error'],
				],
			],
		],
	],
];
