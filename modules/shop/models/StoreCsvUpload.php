<?php
/**
 *
 * @author    Paul Stolyarov <teajeraker@gmail.com>
 * @copyright industrialax.com
 * @license   https://industrialax.com/crm-general-license
 */

namespace app\modules\shop\models;

class StoreCsvUpload extends CsvUpload
{
	public $store_name;
	
	public function rules()
	{
		$rules =  parent::rules();
		$rules[] = [['store_name'], 'required'];
		return $rules;
	}
}
