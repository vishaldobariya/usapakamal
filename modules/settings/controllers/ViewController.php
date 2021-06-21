<?php

namespace app\modules\settings\controllers;

use Yii;
use yii\base\DynamicModel;
use yii\helpers\ArrayHelper;
use app\modules\settings\models\Setting;
use app\components\controllers\BackController;

/**
 * ViewController implements the CRUD actions for View model.
 */
class ViewController extends BackController
{
	
	/**
	 * @return string
	 * @throws \yii\base\InvalidConfigException
	 */
	public function actionIndex()
	{
		/**
		 * @var $settings Setting[]
		 */
		$settings = Setting::find()->where(['not in', 'system_key', ['ship_one_bottle', 'ship_more_bottle','ship_one_bottle_ground', 'ship_more_bottle_ground']])->orderBy(['id' => SORT_ASC])->all();
		
		$attr = ArrayHelper::getColumn($settings, 'system_key');
		
		$model = new DynamicModel($attr);
		
		foreach($settings as $setting) {
			$model->addRule($setting->system_key, 'string');
			$model->addRule($setting->system_key, 'filter', ['filter' => 'trim']);
		}
		
		if($model->load(Yii::$app->request->post()) && $model->validate()) {
			$data = Yii::$app->request->post($model->formName());
			foreach($data as $key => $datum) {
				Setting::updateAll(['value' => $datum], ['system_key' => $key]);
			}
			$settings = Setting::find()->where(['not in', 'system_key', ['ship_one_bottle', 'ship_more_bottle']])->orderBy(['id' => SORT_ASC])->all();
		}
		
		return $this->render('form', [
			'settings' => $settings,
			'model'    => $model,
		]);
	}
	
	/**
	 * Administration index page size
	 */
	public function actionPageSize()
	{
		$size = Yii::$app->request->get('size', 20);
		
		Yii::$app->session->set('page-size', $size);
	}
	
	public function actionShipRate()
	{
		$settings = Setting::find()->where(['system_key' => ['ship_one_bottle', 'ship_more_bottle','ship_one_bottle_ground', 'ship_more_bottle_ground','two_days_ship']])->orderBy(['id' => SORT_ASC])->all();
		
		$attr = ArrayHelper::getColumn($settings, 'system_key');
		
		$model = new DynamicModel($attr);
		
		foreach($settings as $setting) {
			$model->addRule($setting->system_key, 'string');
			$model->addRule($setting->system_key, 'filter', ['filter' => 'trim']);
		}
		
		if($model->load(Yii::$app->request->post()) && $model->validate()) {
			$data = Yii::$app->request->post($model->formName());
			foreach($data as $key => $datum) {
				Setting::updateAll(['value' => $datum], ['system_key' => $key]);
			}
			return $this->redirect(['ship-rate']);
		}
		
		return $this->render('ship_rate', [
			'settings' => $settings,
			'model'    => $model,
		]);
	}
	
	
}
