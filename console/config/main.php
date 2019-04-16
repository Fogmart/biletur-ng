<?php
$params = array_merge(
	require __DIR__ . '/../../common/config/params.php',
	require __DIR__ . '/../../common/config/params-local.php',
	require __DIR__ . '/params.php',
	require __DIR__ . '/params-local.php'
);

return [
	'id'                  => 'app-console',
	'basePath'            => dirname(__DIR__),
	'bootstrap'           => ['log'],
	'controllerNamespace' => 'console\controllers',
	'aliases'             => [
		'@bower'   => '@vendor/bower-asset',
		'@npm'     => '@vendor/npm-asset',
		'@modules' => '@common/modules'
	],
	'controllerMap'       => [
		'migrate' => [
			'class'               => 'yii\console\controllers\MigrateController',
			'migrationTable'      => 'migrations',
			'migrationPath'       => null,
			'migrationNamespaces' => [
				'app\migrations',
				'modules\message\migrations',
				'modules\pages\migrations',
				'modules\seo\migrations',
			],
		],
	],
	'components'          => [
		'log'  => [
			'targets' => [
				[
					'class'  => 'yii\log\FileTarget',
					'levels' => ['error', 'warning'],
				],
			],
		],
		'user' => [
			'class' => \common\models\User::class,
		],
		'cache'       => [
			'class'        => \yii\caching\MemCache::class,
			'servers' => [
				[
					'host' => 'localhost',
					'port' => 11211,
				],
			],
			'useMemcached' => true,
		],
	],
	'params'              => $params,
];
