<?php
/**
 *
 * @author    Paul Stolyarov <teajeraker@gmail.com>
 * @copyright industrialax.com
 * @license   https://industrialax.com/crm-general-license
 */

namespace app\modules\shop\models;

use yii\base\Model;

class AddProductForm extends Model
{
	
	public $product_name;
	
	public $cap;
	
	public $abv;
	
	public $vol;
	
	public $price;
	
	public function rules()
	{
		return [
			[['product_name'], 'string'],
			[['price', 'abv'], 'number'],
			[['cap', 'vol'], 'integer'],
			[['product_name', 'cap', 'vol', 'abv', 'price'], 'required'],
		];
	}
}
