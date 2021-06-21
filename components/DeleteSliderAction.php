<?php
/**
 *
 * @author    Paul Stolyarov <teajeraker@gmail.com>
 * @copyright industrialax.com
 * @license   https://industrialax.com/crm-general-license
 */

namespace app\components;

use app\modules\storage\actions\DeleteAction;
use app\modules\storage\models\StorageItem;
use Yii;
use yii\helpers\FileHelper;
use yii\web\HttpException;

class DeleteSliderAction extends DeleteAction
{
	public function run()
	{
		$path = \Yii::$app->request->get($this->pathParam);
		$paths = \Yii::$app->session->get($this->sessionKey, []);
		if (in_array($path, $paths, true)) {
			//$success = $this->getFileStorage()->delete($path);
			$success = FileHelper::unlink(Yii::getAlias('@webroot/upload/').$path);
			unset($paths[array_search($path, $paths)]);
			Yii::$app->session->remove($this->sessionKey);
			if(!empty($paths)){
				Yii::$app->session->set($this->sessionKey, $paths);
			}
			if (!$success) {
				throw new HttpException(400);
			} else {
				$this->afterDelete($path);
			}
			return $success;
		} else {
			throw new HttpException(403);
		}
	}
}
