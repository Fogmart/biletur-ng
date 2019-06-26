<?php

use common\components\Environment;
use common\modules\pages\components\StaticPageUrlRule;

$params = array_merge(
	require __DIR__ . '/../../common/config/params.php',
	require __DIR__ . '/../../common/config/params-local.php',
	require __DIR__ . '/params.php',
	require __DIR__ . '/params-local.php'
);

return [
	'id'                  => 'app-frontend',
	'name'                => 'Всероссийская сеть Билетур',
	'basePath'            => dirname(__DIR__),
	'bootstrap'           => ['log'],
	'controllerNamespace' => 'frontend\controllers',
	'components'          => [
		'env' => [
			'class' => common\components\Environment::class,
			'defaultCityId' => '_1CK0R7WDW',
			'defaultLanguage' => 'ru',
			'defaultAirportCode' => 'VVO',
			'defaultArrCityId' => '957979',
			'defaultTourZone' => 3
		],
		'request'      => [
			'csrfParam' => '_csrf-frontend',
			'baseUrl'   => '',
		],
		'user'         => [
			'identityClass'   => 'common\models\User',
			'enableAutoLogin' => true,
			'identityCookie'  => ['name' => '_identity-biletur', 'httpOnly' => true],
		],
		'session'      => [
			'name' => 'biletur-session',
		],
		'errorHandler' => [
			'errorAction' => 'site/error',
		],
		'urlManager'   => [
			'enablePrettyUrl' => true,
			'showScriptName'  => false,
			'suffix'          => '/',
			'rules'           => [
				'' => 'site/index',
				[
					'pattern' => 'page',
					'route'   => 'page',
					'class'   => StaticPageUrlRule::class,
				],

				'excursion/find-by-name/<q>/<needType>' => 'excursion/find-by-name',
				'excursion/widget/<city>'               => 'excursion/widget',
				'excursion/widget/<city>/<needSearch>'  => 'excursion/widget',
				'excursion/city/<city>'                 => 'excursion/index',
				'login'                                 => 'site/login',
				'profile'                               => 'profile/profile/index',
				'api/remote-query/'                     => 'api/remote-query/index',
				'api/remote-query/invalidate-tag'       => 'api/remote-query/invalidate-tag',
				'api/remote-query/add-request-log'      => 'api/remote-query/add-request-log',

				'<module:[\w-]+>/<controller:[\w-]+>/<action:[\w-]+>/<id:\d+>]' => '<module>/<controller>/<action>',
				'<controller:\w+>/<action:\w+>/'                                => '<controller>/<action>',
				'message-widget/<object>/<objectId>/<userName>'                 => 'message/message/widget',
				'hotels/find-by-name/q/<q>'                                     => 'hotels/find-by-name',


				//Редиректы для старых ссылок------------------------------------------------------------
				'Agency'                                                        => 'old-links/agency',
				'TimeTbl'                                                       => 'old-links/avia',
				'TimeTbl/pkc/delays.asp'                                        => 'old-links/avia',
				'TimeTbl/vvo/delays.asp'                                        => 'old-links/avia',
				'Passenger/vvo/railway_schedule.asp'                            => 'old-links/rail-road',
				'Agency/Rekvizit.asp'                                           => 'old-links/accounts',
				'Agency/foradvertisers.asp'                                     => 'old-links/advertising',
				//---------------------------------------------------------------------------------------
			],
		],
	],
	'params'              => $params,
];
