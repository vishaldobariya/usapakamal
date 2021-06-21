<?php

namespace app\modules\user\models;

use Yii;
use yii\base\Model;

/**
 * Class Login
 *
 * @property string $email
 * @property string $password
 * @property string $remember
 *
 * @package app\modules\user
 */
class SignForm extends Model
{
	
	public $email;
	
	public $password;
	
	public $remember;
	
	/**
	 * @return array
	 */
	public function rules()
	{
		return [
			[['email', 'password'], 'required'],
			['email', 'email'],
			['remember', 'boolean'],
		];
	}
	
	/**
	 * Logs in a user using the provided email and password.
	 * @return bool whether the user is logged in successfully
	 */
	public function in()
	{
		if($this->validate()) {
			$user = User::find()->where(['ilike','email', $this->email])->one();
			if($user && Yii::$app->security->validatePassword($this->password, $user->password)) {
				$user->touch('last_login_at');
				return Yii::$app->user->login($user, $this->remember ? Yii::$app->getModule('user')->sessionDuration : 0);
			}
		}
		$this->addError('email', 'Incorrect email or password');
		
		return false;
	}
}
