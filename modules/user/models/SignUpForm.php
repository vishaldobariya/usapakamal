<?php
/**
 *
 * @author    Paul Stolyarov <teajeraker@gmail.com>
 * @copyright industrialax.com
 * @license   https://industrialax.com/crm-general-license
 */

namespace app\modules\user\models;

use yii\base\Model;

class SignUpForm extends Model
{
	
	public $first_name;
	
	public $last_name;
	
	public $password;
	
	public $phone;
	
	public $confirm_password;
	
	public $email;
	
	public $store_name;
	
	public $zip;
	
	/**
	 * @return array
	 */
	public function rules()
	{
		return [
			[['email', 'password', 'first_name', 'confirm_password', 'store_name', 'phone', 'zip'], 'required'],
			['email', 'email'],
			[['confirm_password'], 'compare', 'compareAttribute' => 'password'],
			[['phone'], 'match', 'pattern' => '/^(\([0-9]{3}\) |[0-9]{3}-)[0-9]{3}-[0-9]{4}$/'],
			['email', 'unique', 'targetClass' => User::class, 'targetAttribute' => ['email']],
		
		];
	}
	
}
