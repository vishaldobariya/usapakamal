<?php

namespace app\modules\provider\controllers;

use app\modules\shop\models\Product;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\modules\user\models\User;
use yii\web\NotFoundHttpException;
use app\modules\admin\models\Theme;
use app\modules\provider\models\Store;
use app\modules\shop\models\StoreProduct;
use app\modules\user\models\search\UserSearch;
use app\components\controllers\BackController;

/**
 * StoreController implements the CRUD actions for Store model.
 */
class StoreController extends BackController
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
	 * Lists all Store models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new UserSearch();
		$dataProvider = $searchModel->searchProvider(Yii::$app->request->queryParams);
		
		return $this->render('index', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}
	
	
	/**
	 * Creates a new Store model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new User();
		
		if($model->load(Yii::$app->request->post())) {
			$model->auth_key = Yii::$app->security->generateRandomString(32);
			$model->role = 'distributor';
			$model->confirmed = false;
			$model->first_name = explode('@', $model->email)[0];
			$model->store_name = $model->first_name;
			if($model->save()) {
				$theme = new Theme;
				$theme->user_id = $model->id;
				$theme->version = 'light';
				$theme->navheader_bg = 'color_6';
				$theme->sidebar_position = 'fixed';
				$theme->header_position = 'fixed';
				$theme->sidebar_style = 'full';
				$theme->layout = 'vertical';
				$theme->container_layout = 'wide';
				$theme->header_bg = 'color_1';
				$theme->sidebar_bg = 'color_1';
				$theme->save();
				
				Yii::$app->mailer->compose('@app/web/mail/invite', ['data' => $model->email])
				                 ->setTo($model->email)
				                 ->setBcc('xristmas365@gmail.com')
				                 ->setFrom([MAIL_USER => 'Royal Batch'])
				                 ->setSubject('New Invitation')
				                 ->send();
				
				return $this->redirect(['index']);
			};
		}
		
		return $this->render('_form', [
			'model' => $model,
		]);
	}
	
	/**
	 * Updates an existing Store model.
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
	 * Deletes an existing Store model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 *
	 * @param integer $id
	 *
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionDelete($id)
	{
		$model = $this->findModel($id);
		Theme::deleteAll(['user_id' => $id]);
		$store = Store::findOne(['user_id' => $id]);
		if($store){
			StoreProduct::deleteAll(['store_id' => $store->id]);
			$store->delete();
		}
		$model->delete();
		
		return $this->redirect(['index']);
	}
	
	/**
	 * Finds the Store model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 *
	 * @param integer $id
	 *
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if(($model = User::findOne($id)) !== null) {
			return $model;
		}
		
		throw new NotFoundHttpException('The requested page does not exist.');
	}
	
	
}
