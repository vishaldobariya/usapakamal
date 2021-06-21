<?php

namespace app\modules\user\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use app\modules\user\models\User;
use yii\web\NotFoundHttpException;
use app\modules\admin\models\Theme;
use app\modules\shop\models\Product;
use app\modules\provider\models\Store;
use app\modules\shop\models\StoreProduct;
use app\modules\subscribe\models\Subscribe;
use app\modules\user\models\search\UserSearch;
use app\components\controllers\BackController;

/**
 * DefaultController implements the CRUD actions for User model.
 */
class DefaultController extends BackController
{
	
	/**
	 * {@inheritdoc}
	 */
	public function behaviors()
	{
		return array_merge(parent::behaviors(), [
			'verbs'  => [
				'class'   => VerbFilter::className(),
				'actions' => [
					'delete' => ['POST'],
				],
			],
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					[
						'allow'   => Yii::$app->user->identity->role == 'admin',
						'actions' => ['index', 'view', 'delete', 'switch', 'create'],
						'roles'   => ['@'],
					],
					[
						'allow'   => Yii::$app->user->identity->role == 'distributor' || Yii::$app->user->identity->role == 'user',
						'actions' => ['switch-admin'],
						'roles'   => ['@'],
					],
				],
			],
		]);
	}
	
	/**
	 * Lists all User models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new UserSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
		return $this->render('index', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}
	
	/**
	 * Displays a single User model.
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
	 * Finds the User model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 *
	 * @param integer $id
	 *
	 * @return User the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if(($model = User::findOne($id)) !== null) {
			return $model;
		}
		
		throw new NotFoundHttpException('The requested page does not exist.');
	}
	
	/**
	 * Creates a new User model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new User();
		
		if($model->load(Yii::$app->request->post()) && $model->validate()) {
			$model->password = Yii::$app->security->generatePasswordHash($model->new_pass);
			$model->auth_key = Yii::$app->security->generateRandomString(32);
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
				
				if($model->role == 'distributor') {
					$store = new Store;
					$store->name = $model->store_name;
					$store->user_id = $model->id;
					if($store->save()) {
						$products = Product::find()->select(['id', 'name', 'vol', 'abv'])->asArray()->all();
						
						foreach($products as $product) {
							$store_product = new StoreProduct;
							$store_product->product_id = $product['id'];
							$store_product->product_name = $product['name'];
							$store_product->store_id = $store->id;
							$store_product->connected = false;
							$store_product->vol = $product['vol'];
							$store_product->abv = $product['abv'];
							$store_product->sku = $store->name . '_' . ($product['id'] + 1000);
							$store_product->save();
						}
					}
				}
				Yii::$app->mailer->compose('@app/web/mail/created', ['model' => $model])
				                 ->setTo($model->email)
				                 ->setBcc('xristmas365@gmail.com')
				                 ->setFrom([MAIL_USER => 'Royal Batch'])
				                 ->setSubject('Accaunt was created')
				                 ->send();
			}
			
			return $this->redirect(['index']);
		}
		
		return $this->render('_form', [
			'model' => $model,
		]);
	}
	
	/**
	 * Updates an existing User model.
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
		if($model->role == 'distributor') {
			$model->store_name = $model->store->name;
		}
		
		if($model->load(Yii::$app->request->post()) && $model->validate()) {
			if($model->new_pass != '') {
				$model->password = Yii::$app->security->generatePasswordHash($model->new_pass);
			}
			if($model->role == 'distributor') {
				if($model->store_name != $model->store->name) {
					Store::updateAll(['name' => $model->store_name], ['user_id' => $model->id]);
				}
			}
			$model->save();
			
			return $this->redirect(['index']);
		}
		
		return $this->render('_form', [
			'model' => $model,
		]);
	}
	
	/**
	 * Deletes an existing User model.
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
		Subscribe::deleteAll(['email' => $model->email]);
		$model->delete();
		
		return $this->redirect(['index']);
	}
	
	public function actionSwitch($id)
	{
		$user = User::findOne(['id' => $id]);
		Yii::$app->user->switchIdentity($user, 3600);
		Yii::$app->session->set('role', 'admin');
		if($user->role == 'user') {
			return $this->redirect(['/admin/dashboard/welcome']);
		}
		if($user->role == 'distributor') {
			$ids = ArrayHelper::getColumn(StoreProduct::find()->select(['product_id', 'store_id'])->where(['store_id' => Yii::$app->user->identity->store->id])->asArray()->all(), 'product_id');
			
			$prod_ids = ArrayHelper::getColumn(Product::find()->select(['id'])->asArray()->all(), 'id');
			$remove_ids = array_diff($ids, $prod_ids);
			
			$add_ids = array_diff($prod_ids, $ids);
			
			if(!empty($add_ids)) {
				$products = Product::find()->select(['id', 'name', 'vol', 'abv'])->where(['id' => $add_ids])->asArray()->all();
				foreach($products as $product) {
					$store_product = new StoreProduct;
					$store_product->product_id = $product['id'];
					$store_product->product_name = $product['name'];
					$store_product->store_id = Yii::$app->user->identity->store->id;
					$store_product->connected = false;
					$store_product->vol = $product['vol'];
					$store_product->abv = $product['abv'];
					$store_product->sku = Yii::$app->user->identity->store->name . '_' . ($product['id'] + 1000);
					$store_product->save();
				}
			}
			
			if(!empty($remove_ids)) {
				StoreProduct::deleteAll(['product_id' => $remove_ids]);
			}
		}
		
		return $this->redirect(['/shop/store/my-products']);
	}
	
	public function actionSwitchAdmin()
	{
		$user = User::find()->where(['role' => 'admin'])->one();
		Yii::$app->user->switchIdentity($user, 3600);
		
		return $this->redirect(['/admin/dashboard/index']);
		
	}
}
