<?php

/**
 * @link      http://industrialax.com/
 * @email     xristmas365@gmail.com
 * @author    isteil
 * @copyright Copyright (c) 2020 INDUSTRIALAX SOLUTIONS LLC
 * @license   https://industrialax.com/license
 */

use app\modules\user\models\User;
use app\modules\settings\components\Settings;

return [
	'request'      => [
		'cookieValidationKey' => 'yL1FydCIbAwf71v9wqUJb7ZSFkn6iIFB',
		'baseUrl'             => '',
	],
	'user'         => [
		'identityClass'   => User::class,
		'enableAutoLogin' => true,
		'loginUrl'        => '/sign/in',
	],
	'errorHandler' => [
		'errorAction' => 'site/error',
	],
	'log'          => [
		'traceLevel' => YII_DEBUG ? 3 : 0,
		'targets'    => [
			[
				'class'  => 'yii\log\DbTarget',
				'levels' => ['error'],
			],
		],
	],
	'db'           => require __DIR__ . '/db.php',
	'urlManager'   => [
		'enablePrettyUrl' => true,
		'showScriptName'  => false,
		'rules'           => require __DIR__ . '/urls.php',
	],
	'formatter'    => [
		'nullDisplay'    => '',
		'datetimeFormat' => 'MM/dd/yyyy HH:mm a',
		'dateFormat'     => 'MM/dd/yyyy',
		'timeZone'       => 'America/Los_Angeles',
		'currencyCode'   => 'usd',
	],
	'settings'     => ['class' => Settings::class],
	'assetManager' => [
		'forceCopy' => YII_DEBUG,
		'class'     => 'yii\web\AssetManager',
		'bundles'    => [
			'yii\bootstrap\BootstrapAsset' => [
				'css' => [],
			],

			'yii\web\JqueryAsset'                 => ['js' => ['jquery.min.js']],
			// 'yii\bootstrap4\BootstrapAsset'       => ['css' => ['css/bootstrap.min.css']],
			'yii\bootstrap4\BootstrapPluginAsset' => ['js' => ['js/bootstrap.bundle.min.js']],
			'extead\autonumeric\AutoNumericAsset' => [
				'depends' => [
					'yii\web\JqueryAsset',
					'yii\web\YiiAsset',
					'yii\bootstrap4\BootstrapAsset',
				],
			],
			'kartik\form\ActiveFormAsset' => [
				'bsDependencyEnabled' => false // do not load bootstrap assets for a specific asset bundle
			],
		],

	],
	'cart'         => [
		'class'  => 'yz\shoppingcart\ShoppingCart',
		'cartId' => 'cart',
	],
	'cache'                => 'yii\caching\FileCache',
	'fsLocal'      => [
		'class' => 'creocoder\flysystem\LocalFilesystem',
		'path'  => '@webroot/upload',
	],
	'fileStorage'  => [
		'class'               => 'trntv\filekit\Storage',
		'filesystemComponent' => 'fsLocal',
		'baseUrl'             => '@web/upload/',
	],
	'mailer'       => [
		'class'            => 'yii\swiftmailer\Mailer',
		'viewPath'         => '@app/web/mail',
		// send all mails to a file by default. You have to set
		// 'useFileTransport' to false and configure a transport
		// for the mailer to send real emails.
		'useFileTransport' => false,
		'transport'        => [
			'class'      => 'Swift_SmtpTransport',
			'host'       => 'mi3-ts5.a2hosting.com',
			'username'   => MAIL_USER,
			'password'   => MAIL_PASS,
			'port'       => '465',
			'encryption' => 'ssl',
		],
	],
	'opengraph' => [
		'class' => 'fgh151\opengraph\OpenGraph',
	],
];
