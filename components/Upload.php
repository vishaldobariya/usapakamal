<?php
/**
 *
 * @author    Paul Stolyarov <teajeraker@gmail.com>
 * @copyright industrialax.com
 * @license   https://industrialax.com/crm-general-license
 */

namespace app\components;


use trntv\filekit\widget\UploadAsset;
use yii\helpers\Json;
use yii\jui\JuiAsset;

class Upload extends \trntv\filekit\widget\Upload
{
	public $model_name;
	
	public function registerClientScript()
	{
		UploadAsset::register($this->getView());
		$options = Json::encode($this->clientOptions);
		if ($this->sortable) {
			JuiAsset::register($this->getView());
		}
		$this->getView()->registerJs("jQuery('#{$this->getId()}').yiiUploadKit({$options});");
	}
}
