<?php
/**
 * @author      Anthony <xristmas365@gmail.com>
 * @copyright   industrialax.com
 * @license     https://industrialax.com/crm-general-license
 */

namespace app\modules\storage\behaviors;

class UploadBehavior extends \trntv\filekit\behaviors\UploadBehavior
{
	
	public $customKey;
	
	
	/**
	 * @param array $files
	 *
	 * @throws \yii\base\InvalidConfigException
	 */
	protected function saveFilesToRelation($files)
	{
		$modelClass = $this->getUploadModelClass();
		foreach($files as $file) {
			$model = new $modelClass;
			$model->setScenario($this->uploadModelScenario);
			$model = $this->loadModel($model, $file);
			$model->model_name = $this->customKey ?? $this->owner->formName();
			if($this->getUploadRelation()->via !== null) {
				$model->save(false);
			}
			$this->owner->link($this->uploadRelation, $model);
		}
	}
}
