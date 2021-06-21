<?php
/**
 * @link      http://industrialax.com/
 * @email     xristmas365@gmail.com
 * @author    isteil
 * @copyright Copyright (c) 2020 INDUSTRIALAX SOLUTIONS LLC
 * @license   https://industrialax.com/license
 */

namespace app\modules\api\controllers;

use Yii;
use app\modules\user\models\User;

class UserController extends DefaultController
{
	
	public function actionCheckEmail()
	{
		$email = Yii::$app->request->post('email');
		
		$user = User::findOne(['email' => $email]);
		
		return $user ? $user->fullName : false;
		
	}
	
	public function actionAuth()
	{
		return true;
	}
	
	public function actionProfile()
	{
	}
	
}
