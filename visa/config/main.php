<?php

use common\modules\pages\components\StaticPageUrlRule;

$params = array_merge(
	require __DIR__ . '/../../common/config/params.php',
	require __DIR__ . '/../../common/config/params-local.php',
	require __DIR__ . '/params.php',
	require __DIR__ . '/params-local.php'
);

return [
	'id'                  => 'app-frontend-visa',
	'name'                => 'Всероссийская сеть Билетур',
	'basePath'            => dirname(__DIR__),
	'bootstrap'           => ['log'],
	'controllerNamespace' => 'visa\controllers',
	'components'          => [
		'request'      => [
			'csrfParam' => '_csrf-frontend-visa',
			'baseUrl'   => '',
		],
		'user'         => [
			'identityClass'   => 'common\models\User',
			'enableAutoLogin' => true,
			'identityCookie'  => ['name' => '_identity-visa-biletur', 'httpOnly' => true],
		],
		'session'      => [
			// this is the name of the session cookie used for login on the frontend
			'name' => 'biletur-visa-session',
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
				'<module:[\w-]+>/<controller:[\w-]+>/<action:[\w-]+>/<id:\d+>]' => '<module>/<controller>/<action>',
				'<controller:\w+>/<action:\w+>/'                                => '<controller>/<action>',
			],
		],
	],
	'params'              => $params,
];
