<?php

namespace app\modules\shop\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use app\modules\shop\models\Contact;
use app\components\controllers\BackController;
use app\modules\shop\models\search\ContactSearch;

/**
 * ContactController implements the CRUD actions for Contact model.
 */
class ContactController extends BackController
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
	 * Lists all Contact models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new ContactSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
		return $this->render('index', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}
	
	
	public function actionCreate()
	{
		return $this->redirect(['index']);
	}
	
	
	/**
	 * Deletes an existing Contact model.
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
	 * Finds the Contact model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 *
	 * @param integer $id
	 *
	 * @return Contact the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if(($model = Contact::findOne($id)) !== null) {
			return $model;
		}
		
		throw new NotFoundHttpException('The requested page does not exist.');
	}
	
	public function actionRead()
	{
		$id = Yii::$app->request->post('id');
		
		return $this->findModel($id)->text;
	}
}
