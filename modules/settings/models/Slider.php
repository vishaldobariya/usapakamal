<?php
/**
 *
 * @author    Paul Stolyarov <teajeraker@gmail.com>
 * @copyright industrialax.com
 * @license   https://industrialax.com/crm-general-license
 */

namespace app\modules\settings\models;

use app\modules\storage\behaviors\UploadBehavior;
use yii\base\Model;
use yii\behaviors\SluggableBehavior;

class Slider extends Model
{
	public $image;
	
	public function rules()
	{
		return [
			['image','safe']
		];
	}
	
}
