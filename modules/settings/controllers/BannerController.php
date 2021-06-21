<?php
/**
 *
 * @author    Paul Stolyarov <teajeraker@gmail.com>
 * @copyright industrialax.com
 * @license   https://industrialax.com/crm-general-license
 */

namespace app\modules\settings\controllers;

use app\modules\storage\models\StorageItem;
use app\components\controllers\BackController;

class BannerController extends BackController
{
	
	public function actionSlider()
	{
		$files = StorageItem::find()->where(['model_name' => 'Slider'])->orderBy(['position' => SORT_ASC])->all();
		\Yii::$app->session->set('count-main-slider', count($files));
		
		return $this->render('slider', [
			'files' => $files,
		]);
	}
	
	public function actionMobileSlider()
	{
		$files = StorageItem::find()->where(['model_name' => 'Mobile Slider'])->orderBy(['position' => SORT_ASC])->all();
		\Yii::$app->session->set('count-main-slider', count($files));
		
		return $this->render('mobile_banner', [
			'files' => $files,
		]);
	}
	
	public function actionDelete()
	{
		$path = \Yii::$app->request->post('path');
		$storage = StorageItem::find()->where(['path' => $path])->one();
		
		$id = $storage->id;
		$storage->delete();
		
		return $id;
	}
	
	public function actionGetId()
	{
		$files = StorageItem::find()->where(['model_name' => 'Slider'])->orderBy(['position' => SORT_ASC])->asArray()->all();
		
		$html = '';
		$array = array_slice($files, (int)\Yii::$app->session->get('count-main-slider'), null, true);
		
		foreach($array as $key => $file) {
			$html .= '<div class="mb-4  js-' . $file['id'] . '">
				<label for="">Link ' . ($key + 1) . '</label>
				<input type="text" data-id="' . $file['id'] . '" class="w-100" value="' . $file['link'] . '">
						</div>';
		}
		\Yii::$app->session->set('count-main-slider', count($files));
		
		return $html;
		
	}
	
	public function actionMobileGetId()
	{
		$files = StorageItem::find()->where(['model_name' => 'Mobile Slider'])->orderBy(['position' => SORT_ASC])->asArray()->all();
		
		$html = '';
		$array = array_slice($files, (int)\Yii::$app->session->get('count-main-slider'), null, true);
		
		foreach($array as $key => $file) {
			$html .= '<div class="mb-4  js-' . $file['id'] . '">
				<label for="">Link ' . ($key + 1) . '</label>
				<input type="text" data-id="' . $file['id'] . '" class="w-100" value="' . $file['link'] . '">
						</div>';
		}
		\Yii::$app->session->set('count-main-slider', count($files));
		
		return $html;
		
	}
	public function actionGetMiddleId()
	{
		$files = StorageItem::find()->where(['model_name' => 'Middle Image'])->orderBy(['position' => SORT_ASC])->asArray()->all();
		
		$html = '';
		$array = array_slice($files, (int)\Yii::$app->session->get('count-middle-images'), null, true);
		
		foreach($array as $key => $file) {
			$html .= '<div class="mb-4  js-' . $file['id'] . '">
				<label for="">Link ' . ($key + 1) . '</label>
				<input type="text" data-id="' . $file['id'] . '" class="w-100" value="' . $file['link'] . '">
						</div>';
		}
		
		\Yii::$app->session->set('count-middle-images', count($files));
		
		return $html;
		
	}
	
	public function actionGetFooterId()
	{
		$files = StorageItem::find()->where(['model_name' => 'Footer Image'])->orderBy(['position' => SORT_ASC])->asArray()->all();
		
		$html = '';
		$array = array_slice($files, (int)\Yii::$app->session->get('count-footer-images'), null, true);
		
		foreach($array as $key => $file) {
			$html .= '<div class="mb-4  js-' . $file['id'] . '">
				<label for="">Link ' . ($key + 1) . '</label>
				<input type="text" data-id="' . $file['id'] . '" class="w-100" value="' . $file['link'] . '">
						</div>';
		}
		
		\Yii::$app->session->set('count-footer-images', count($files));
		
		return $html;
		
	}
	
	public function actionSaveLink()
	{
		return StorageItem::updateAll(['link' => \Yii::$app->request->post('val')], ['id' => \Yii::$app->request->post('id'), 'model_name' => \Yii::$app->request->post('model_name')]);
	}
	
	public function actionMiddleImages()
	{
		$files = StorageItem::find()->where(['model_name' => 'Middle Image'])->orderBy(['position' => SORT_ASC])->all();
		\Yii::$app->session->set('count-middle-images', count($files));
		
		return $this->render('middle_images', [
			'files' => $files,
		]);
	}
	
	public function actionFooterImages()
	{
		$files = StorageItem::find()->where(['model_name' => 'Footer Image'])->orderBy(['position' => SORT_ASC])->all();
		
		\Yii::$app->session->set('count-footer-images', count($files));
		
		return $this->render('footer_images', [
			'files' => $files,
		]);
	}
	
	public function actionChangePosition()
	{
		$keys = \Yii::$app->request->post('keys');
		for($i = 0; $i < count($keys); $i++) {
			$arr = explode('/', $keys[$i]);
			$index = array_pop($arr);
			$storage = StorageItem::find()->where(['ilike', 'path', $index])->one();
			$storage->position = $i;
			$storage->save();
		}
	}
}
