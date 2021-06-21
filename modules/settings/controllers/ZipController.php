<?php

namespace app\modules\settings\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use app\modules\settings\models\Zip;
use app\components\controllers\BackController;
use app\modules\settings\models\search\ZipSearch;

/**
 * ZipController implements the CRUD actions for Zip model.
 */
class ZipController extends BackController
{
	
	/**
	 * {@inheritdoc}
	 */
	public function behaviors()
	{
		return [
			'verbs' => [
				'class'   => VerbFilter::className(),
				'actions' => [
					'delete' => ['POST'],
				],
			],
		];
	}
	
	/**
	 * Lists all Zip models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new ZipSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$states = ArrayHelper::map(Zip::find()->select('state')->asArray()->all(), 'state', 'state');
		
		return $this->render('index', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
			'states'       => $states,
		]);
	}
	
	
	/**
	 * Creates a new Zip model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new Zip();
		
		if($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['index']);
		}
		
		return $this->render('_form', [
			'model' => $model,
		]);
	}
	
	/**
	 * Updates an existing Zip model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 *
	 * @param integer $id
	 *
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);
		
		if($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['index']);
		}
		
		return $this->render('_form', [
			'model' => $model,
		]);
	}
	
	/**
	 * Deletes an existing Zip model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 *
	 * @param integer $id
	 *
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionDelete($id)
	{
		$this->findModel($id)->delete();
		
		return $this->redirect(['index']);
	}
	
	/**
	 * Finds the Zip model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 *
	 * @param integer $id
	 *
	 * @return Zip the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if(($model = Zip::findOne($id)) !== null) {
			return $model;
		}
		
		throw new NotFoundHttpException('The requested page does not exist.');
	}
	
	
	public function actionEnableZip()
	{
		$state = Yii::$app->request->post('state');
		if($state == '') {
			return Zip::updateAll(['active' => true], ['>', 'id', 0]);
		} else {
			return Zip::updateAll(['active' => true], ['state' => $state]);
		}
	}
	
	public function actionDisableZip()
	{
		$state = Yii::$app->request->post('state');
		if($state == '') {
			return Zip::updateAll(['active' => false], ['>', 'id', 0]);
		} else {
			return Zip::updateAll(['active' => false], ['state' => $state]);
		}
	}
}
