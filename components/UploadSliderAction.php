<?php
/**
 *
 * @author    Paul Stolyarov <teajeraker@gmail.com>
 * @copyright industrialax.com
 * @license   https://industrialax.com/crm-general-license
 */

namespace app\components;

use yii\helpers\Url;
use yii\helpers\Html;
use yii\web\UploadedFile;
use yii\base\DynamicModel;
use trntv\filekit\actions\UploadAction;
use app\modules\storage\models\StorageItem;

class UploadSliderAction extends UploadAction
{
	
	public $model_name;
	
	public function run()
	{
		$result = [];
		$uploadedFiles = UploadedFile::getInstancesByName($this->fileparam);
		
		foreach($uploadedFiles as $uploadedFile) {
			/* @var \yii\web\UploadedFile $uploadedFile */
			$output = [
				$this->responseNameParam     => Html::encode($uploadedFile->name),
				$this->responseMimeTypeParam => $uploadedFile->type,
				$this->responseSizeParam     => $uploadedFile->size,
				$this->responseBaseUrlParam  => $this->getFileStorage()->baseUrl,
			];
			if($uploadedFile->error === UPLOAD_ERR_OK) {
				$validationModel = DynamicModel::validateData(['file' => $uploadedFile], $this->validationRules);
				if(!$validationModel->hasErrors()) {
					$path = $this->getFileStorage()->save($uploadedFile, false, false, $this->saveConfig, $this->uploadPath);
					
					if($path) {
						$output[$this->responsePathParam] = $path;
						$output[$this->responseUrlParam] = $this->getFileStorage()->baseUrl . '/' . $path;
						
						$model = new StorageItem;
						$model->attributes = $output;
						$model->model_name = $this->model_name;
						
						$storage = StorageItem::find()->where(['model_name' => $this->model_name])->orderBy(['position' => SORT_DESC])->one();
						$model->position = $storage ? $storage->position + 1 : 1;
						
						$model->save();
						
						$output[$this->responseDeleteUrlParam] = Url::to([$this->deleteRoute, 'path' => $path]);
						$paths = \Yii::$app->session->get($this->sessionKey, []);
						$paths[] = $path;
						
						\Yii::$app->session->set($this->sessionKey, $paths);
						$this->afterSave($path);
						
					} else {
						$output['error'] = true;
						$output['errors'] = [];
					}
					
				} else {
					$output['error'] = true;
					$output['errors'] = $validationModel->getFirstError('file');
				}
			} else {
				$output['error'] = true;
				$output['errors'] = $this->resolveErrorMessage($uploadedFile->error);
			}
			
			$result['files'][] = $output;
		}
		
		return $this->multiple ? $result : array_shift($result);
	}
}
