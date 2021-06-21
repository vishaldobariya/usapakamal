<?php
/**
 *
 * @author    Paul Stolyarov <teajeraker@gmail.com>
 * @copyright industrialax.com
 * @license   https://industrialax.com/crm-general-license
 */

namespace app\modules\settings\components;

use yii\base\Component;
use yii\helpers\ArrayHelper;
use yii\base\InvalidConfigException;
use app\modules\settings\models\Setting;

/**
 * Class Settings
 *
 *
 * @example
 *
 *  components
 *  [
 *  ...
 *    'settings'     => [
 *      'class' => Setting::class,
 *     ],
 *  ...
 *
 *  Yii::$app->settings->email
 *
 * @package app\modules\components
 */
class Settings extends Component
{
	
	public $params = [];
	
	public function init()
	{
		parent::init();
		$this->params = ArrayHelper::map(Setting::find()->select(['system_key', 'value'])->asArray()->all(), 'system_key', 'value');
	}
	
	public function __get($name)
	{
		if(!key_exists($name, $this->params)) {
			throw new InvalidConfigException("$name does not exist. Insert value into {{%setting}} table with $name key");
		}
		
		return $this->params[$name];
		
	}
}
