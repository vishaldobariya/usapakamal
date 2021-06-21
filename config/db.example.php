<?php
/**
 * @link      http://industrialax.com/
 * @email     xristmas365@gmail.com
 * @author    isteil
 * @copyright Copyright (c) 2020 INDUSTRIALAX SOLUTIONS LLC
 * @license   https://industrialax.com/license
 */

return [
	'class'             => 'yii\db\Connection',
	'dsn'               => 'pgsql:host=localhost;dbname=basic',
	'username'          => 'postgres',
	'password'          => '123456',
	'charset'           => 'utf8',
	'enableSchemaCache' => YII_ENV_PROD,
];
