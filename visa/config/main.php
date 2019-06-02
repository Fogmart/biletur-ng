<?php

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
			// this is the name of the session cookie used for login on the frontend
			'name' => 'biletur-session',
		],
		'log'          => [
			'traceLevel' => YII_DEBUG ? 3 : 0,
			'targets'    => [
				[
					'class'  => 'yii\log\FileTarget',
					'levels' => ['error', 'warning'],
				],
			],
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