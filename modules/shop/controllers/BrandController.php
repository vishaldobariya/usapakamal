<?php

namespace app\modules\shop\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use app\modules\shop\models\Brand;
use app\modules\shop\models\Product;
use app\components\controllers\BackController;
use app\modules\shop\models\search\BrandSearch;

/**
 * BrandController implements the CRUD actions for Brand model.
 */
class BrandController extends BackController
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
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					[
						'allow' => Yii::$app->user->identity->role == 'admin',
						'roles' => ['@'],
					],
				],
			],
		]);
	}
	
	/**
	 * Lists all Brand models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new BrandSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
		return $this->render('index', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}
	
	/**
	 * Displays a single Brand model.
	 *
	 * @param integer $id
	 *
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionView($id)
	{
		return $this->render('view', [
			'model' => $this->findModel($id),
		]);
	}
	
	/**
	 * Creates a new Brand model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new Brand();
		
		if($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['index']);
		}
		
		return $this->render('create', [
			'model' => $model,
		]);
	}
	
	/**
	 * Updates an existing Brand model.
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
		
		return $this->render('update', [
			'model' => $model,
		]);
	}
	
	/**
	 * Deletes an existing Brand model.
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
	 * Finds the Brand model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 *
	 * @param integer $id
	 *
	 * @return Brand the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if(($model = Brand::findOne($id)) !== null) {
			return $model;
		}
		
		throw new NotFoundHttpException('The requested page does not exist.');
	}
	
	public function actionConnect()
	{
		$post = Yii::$app->request->post()['checked'];
		$id_f = (int)$post[0];
		$id_s = (int)$post[1];
		$min = $id_f < $id_s ? $id_f : $id_s;
		$max = $min == $id_f ? $id_s : $id_f;
		
		Product::updateAll(['brand_id' => $min], ['brand_id' => $max]);
		
		return Brand::deleteAll(['id' => $max]);
		
	}
}
