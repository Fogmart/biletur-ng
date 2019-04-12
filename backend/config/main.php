<?php
$params = array_merge(
	require __DIR__ . '/../../common/config/params.php',
	require __DIR__ . '/../../common/config/params-local.php',
	require __DIR__ . '/params.php',
	require __DIR__ . '/params-local.php'
);

return [
	'id'                  => 'app-backend',
	'basePath'            => dirname(__DIR__),
	'controllerNamespace' => 'backend\controllers',
	'bootstrap'           => ['log'],
	'modules'             => [],
	'components'          => [
		'request'      => [
			'csrfParam' => '_csrf-backend',
			'baseUrl'   => '/internal',
		],
		'user'         => [
			'identityClass'   => \common\models\User::class,
			'enableAutoLogin' => true,
			'identityCookie'  => ['name' => '_identity-biletur', 'httpOnly' => true],
		],
		'session'      => [
			// this is the name of the session cookie used for login on the backend
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
			'rules'           => [
				''                               => 'site/index',
				'<controller:\w+>/<action:\w+>/' => '<controller>/<action>',
			],
		],
	],
	'params'              => $params,
];
