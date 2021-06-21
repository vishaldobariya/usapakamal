<?php
/**
 * @link      http://industrialax.com/
 * @email     xristmas365@gmail.com
 * @author    isteil
 * @copyright Copyright (c) 2020 INDUSTRIALAX SOLUTIONS LLC
 * @license   https://industrialax.com/license
 */

$config = [
	'id'         => 'basic',
	'name'       => 'Royal Batch',
	'basePath'   => dirname(__DIR__),
	'bootstrap'  => ['log'],
	'aliases'    => [
		'@bower' => '@vendor/bower-asset',
		'@npm'   => '@vendor/npm-asset',
	],
	'components' => require __DIR__ . '/components.php',
	'params'     => require __DIR__ . '/params.php',
	'modules'    => require __DIR__ . '/modules.php',
];

if(YII_ENV_DEV) {
	$config['bootstrap'][] = 'debug';
	$config['modules']['debug'] = [
		'class' => 'yii\debug\Module',
		//'allowedIPs' => ['127.0.0.1', '::1'],
	];
	
	$config['bootstrap'][] = 'gii';
	$config['modules']['gii'] = [
		'class' => 'yii\gii\Module',
		//'allowedIPs' => ['127.0.0.1', '::1'],
	];
}

return $config;
