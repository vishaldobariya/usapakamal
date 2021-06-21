<?php
/**
 *
 * @author    Paul Stolyarov <teajeraker@gmail.com>
 * @copyright industrialax.com
 * @license   https://industrialax.com/crm-general-license
 */

namespace app\modules\shop\models;

use yii\base\Model;

class CsvUpload extends Model
{
	public $csv;
	public $file;
	
	public function rules()
	{
		return [
			[['csv'], 'file', 'maxFiles' => 1],
			[['file'], 'file', 'maxFiles' => 5],
		];
	}
	
	public function upload()
	{
		if ($this->validate()) {
			$fileNames = [];
			foreach ($this->file as $file) {
				$name = \Yii::$app->security->generateRandomString(10);
				$file->saveAs(\Yii::getAlias('@app/web/upload/' . $name . '.' . $file->extension));
				$fileNames[] = $name.'.'.$file->extension;
			}
			return $fileNames;
		} else {
			return false;
		}
	}
}
