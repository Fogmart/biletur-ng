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
	'name'                  => 'Всероссийская сеть Билетур',
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
			'suffix' => '/',
			'rules'           => [

				''                                                              => 'site/index',
				[
					'pattern'   => 'page',
					'route'     => 'page',
					'class'     => StaticPageUrlRule::class,
				],
				'<module:[\w-]+>/<controller:[\w-]+>/<action:[\w-]+>/<id:\d+>]' => '<module>/<controller>/<action>',
				'<controller:\w+>/<action:\w+>/'                                => '<controller>/<action>',
				'message-widget/<object>/<objectId>/<userName>'                 => 'message/message/widget',

			],
		],
	],
	'params'              => $params,
];
