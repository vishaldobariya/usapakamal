<?php
/**
 * @link      http://industrialax.com/
 * @email     xristmas365@gmail.com
 * @author    isteil
 * @copyright Copyright (c) 2020 INDUSTRIALAX SOLUTIONS LLC
 * @license   https://industrialax.com/license
 */

namespace app\modules\api\controllers;

use yii\rest\Controller;

class DefaultController extends Controller
{
	
	
	/**
	 * {@inheritdoc}
	 */
	public function actions()
	{
		return [
			'index'   => [
				'class'       => 'yii\rest\IndexAction',
				'modelClass'  => $this->modelClass,
				'checkAccess' => [$this, 'checkAccess'],
			],
			'view'    => [
				'class'       => 'yii\rest\ViewAction',
				'modelClass'  => $this->modelClass,
				'checkAccess' => [$this, 'checkAccess'],
			],
			'create'  => [
				'class'       => 'yii\rest\CreateAction',
				'modelClass'  => $this->modelClass,
				'checkAccess' => [$this, 'checkAccess'],
				'scenario'    => $this->createScenario,
			],
			'update'  => [
				'class'       => 'yii\rest\UpdateAction',
				'modelClass'  => $this->modelClass,
				'checkAccess' => [$this, 'checkAccess'],
				'scenario'    => $this->updateScenario,
			],
			'delete'  => [
				'class'       => 'yii\rest\DeleteAction',
				'modelClass'  => $this->modelClass,
				'checkAccess' => [$this, 'checkAccess'],
			],
			'options' => [
				'class' => 'yii\rest\OptionsAction',
			],
		];
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function verbs()
	{
		return [
			'index'  => ['GET', 'OPTIONS'],
			'view'   => ['GET', 'HEAD'],
			'create' => ['POST'],
			'update' => ['PUT', 'PATCH'],
			'delete' => ['DELETE'],
		];
	}
	
}
