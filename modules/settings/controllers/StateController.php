<?php

namespace app\modules\settings\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use app\modules\settings\models\StoreState;
use app\components\controllers\BackController;
use app\modules\settings\models\search\StateSearch;

/**
 * StateController implements the CRUD actions for State model.
 */
class StateController extends BackController
{
	
	/**
	 * {@inheritdoc}
	 */
	public function behaviors()
	{
		return array_merge(parent::behaviors(), [
			'verbs' => [
				'class'   => VerbFilter::className(),
				'actions' => [
					'delete' => ['POST'],
				],
			],
		]);
	}
	
	/**
	 * Lists all State models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new StateSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$states = ArrayHelper::getColumn(StoreState::findAll(['store_id' => Yii::$app->user->identity->store->id]), 'state_id');
		
		return $this->render('index', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
			'states'       => $states,
		]);
	}
	
	public function actionState()
	{
		$store_id = Yii::$app->user->identity->store->id;
		$val = Yii::$app->request->post('val');
		if(Yii::$app->request->post('type') == 'add') {
			$model = new StoreState;
			$model->store_id = $store_id;
			$model->state_id = $val;
			
			return $model->save();
		} else {
			return StoreState::find()->where(['store_id' => $store_id, 'state_id' => $val])->one()->delete();
		}
	}
}
