<?php

$config = [
	'id'                  => 'basic',
	'name'                => 'Royal Batch',
	'basePath'            => dirname(__DIR__),
	'bootstrap'           => ['log'],
	'controllerNamespace' => 'app\commands',
	'aliases'             => [
		'@bower' => '@vendor/bower-asset',
		'@npm'   => '@vendor/npm-asset',
	],
	'components'          => [
		'cache' => [
			'class' => 'yii\caching\FileCache',
		],
		'log'   => [
			'targets' => [
				[
					'class'  => 'yii\log\DbTarget',
					'levels' => ['error', 'warning'],
				],
			],
		],
		'db'    => require __DIR__ . '/db.php',
	],
	'params'              => $params = require __DIR__ . '/params.php',
	'controllerMap'       => [
		'migrate' => [
			'class'         => 'yii\console\controllers\MigrateController',
			'migrationPath' => [
				'@app/migrations',
				'@app/modules/user/migrations',
				'@app/modules/settings/migrations',
				'@app/modules/admin/migrations',
				'@yii/log/migrations/',
			],
		],
	],

];

if(YII_ENV_DEV) {
	$config['bootstrap'][] = 'gii';
	$config['modules']['gii'] = [
		'class' => 'yii\gii\Module',
	];
}

return $config;
