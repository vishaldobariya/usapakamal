<?php
/**
 * @author      Anthony <xristmas365@gmail.com>
 * @copyright   industrialax.com
 * @license     https://industrialax.com/crm-general-license
 */

namespace app\modules\storage\actions;

use app\modules\storage\models\StorageItem;
use League\Flysystem\File as FlysystemFile;
use League\Flysystem\FilesystemInterface;
use trntv\filekit\events\UploadEvent;
use Yii;
use yii\helpers\FileHelper;
use yii\web\HttpException;

class DeleteAction extends \trntv\filekit\actions\DeleteAction
{
	public function run()
	{
		$path = \Yii::$app->request->get($this->pathParam);
		$paths = \Yii::$app->session->get($this->sessionKey, []);
		if (in_array($path, $paths, true)) {
			//$success = $this->getFileStorage()->delete($path);
			$success = FileHelper::unlink(Yii::getAlias('@webroot/upload/').$path);
			StorageItem::deleteAll(['path' => $path]);
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
	
	/**
	 * @param $path
	 */
	public function afterDelete($path)
	{
		$file = null;
		$fs = $this->getFileStorage()->getFilesystem();
		if ($fs instanceof FilesystemInterface) {
			$file = new FlysystemFile($fs, $path);
		}
		$this->trigger(self::EVENT_AFTER_DELETE, new UploadEvent([
			'path' => $path,
			'filesystem' => $fs,
			'file' => $file
		]));
	}
}
