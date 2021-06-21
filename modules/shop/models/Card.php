<?php
/**
 *
 * @author    Paul Stolyarov <teajeraker@gmail.com>
 * @copyright industrialax.com
 * @license   https://industrialax.com/crm-general-license
 */

namespace app\modules\shop\models;

use yii\base\Model;

class Card extends Model
{
	
	public $number;
	
	public $card_name;
	
	public $exp;
	
	public $cvv;
	
	public function rules()
	{
		return [
			[['number', 'card_name', 'exp', 'cvv'], 'required'],
		];
	}
	
	public function attributeLabels()
	{
		return [
			'number'    => 'Card Number',
			'card_name' => 'Name on Card',
			'exp'       => 'Expiration Date',
			'cvv'       => 'Security Code',
		];
	}
}
