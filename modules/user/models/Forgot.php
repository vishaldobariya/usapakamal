<?php
/**
 *
 * @author    Paul Stolyarov <teajeraker@gmail.com>
 * @copyright industrialax.com
 * @license   https://industrialax.com/crm-general-license
 */

namespace app\modules\user\models;

use yii\base\Model;

class Forgot extends Model
{
	
	public $code;
	
	public $confirm_code;
	
	public $new_password;
	
	public $confirm_password;
	
	public $email;
	
	public function rules()
	{
		return [
			[['new_password', 'code', 'confirm_password', 'confirm_code', 'email'], 'required'],
			[['new_password', 'confirm_password'], 'string'],
			[['confirm_password'], 'compare', 'compareAttribute' => 'new_password'],
			[['email'], 'email'],
			[['confirm_code'], 'compare', 'compareAttribute' => 'code', 'message' => 'Code is invalid'],
			[['email'], 'exist', 'targetClass' => User::class, 'targetAttribute' => ['email'], 'message' => 'This email don\'t exist'],
		];
	}
}
