<?php

use common\models\User;
use common\modules\rbac\Module as MRbac;

$params = array_merge(
	require __DIR__ . '/../../common/config/params.php',
	require __DIR__ . '/../../common/config/params-local.php',
	require __DIR__ . '/params.php',
	require __DIR__ . '/params-local.php'
);

return [
	'id'                  => 'app-backend',
	'name'                  => 'Билетур',
	'basePath'            => dirname(__DIR__),
	'controllerNamespace' => 'backend\controllers',
	'bootstrap'           => ['log'],
	'modules'             => [
		'gridview' => ['class' => 'kartik\grid\Module'],
		'rbac'     => [
			/**
			 * /rbac/rule
			 * /rbac/permission
			 * /rbac/role
			 * /rbac/assignment
			 */
			'class'                    => MRbac::class,
			'userModelClassName'       => User::class,
			'userModelIdField'         => User::ATTR_ID,
			'userModelLoginField'      => User::ATTR_USER_NAME,
			'userModelLoginFieldLabel' => null,
			'beforeCreateController'   => function ($route) {
				/**  @var string $route The route consisting of module, controller and action IDs. */
				return true;
			},
			'beforeAction'             => function ($action) {
				/** @var yii\base\Action $action the action to be executed. */
				return true;
			}
		]
	],
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
		'errorHandler' => [
			'errorAction' => 'site/error',
		],
		'urlManager'   => [
			'enablePrettyUrl' => true,
			'showScriptName'  => false,
			'suffix'          => '/',
			'rules'           => [
				''                                                             => 'site/index',
				'<controller:\w+>/<action:\w+>/'                               => '<controller>/<action>',
				'<module:[\w-]+>/<controller:[\w-]+>/<action:[\w-]+>/<id:\d+>' => '<module>/<controller>/<action>',

			],
		],
	],
	'params'              => $params,
];
