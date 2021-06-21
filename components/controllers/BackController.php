<?php
/**
 * @link      http://industrialax.com/
 * @email     xristmas365@gmail.com
 * @author    isteil
 * @copyright Copyright (c) 2020 INDUSTRIALAX SOLUTIONS LLC
 * @license   https://industrialax.com/license
 */

namespace app\components\controllers;

use yii\web\Controller;

/**
 * Class BackController
 *
 * @property array $definitions
 *
 * @package app\components\controllers
 */
class BackController extends Controller
{
	
	public $layout = '@app/modules/admin/views/layouts/admin';
	
	public function behaviors()
	{
		return [
			'access' => [
				'class' => 'yii\filters\AccessControl',
				'rules' => [
					[
						'allow' => true,
						'roles' => ['@'],
					],
				
				],
			],
		];
	}
}
