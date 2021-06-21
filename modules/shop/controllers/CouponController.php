<?php

namespace app\modules\shop\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use app\modules\user\models\User;
use yii\web\NotFoundHttpException;
use app\modules\shop\models\Coupon;
use app\modules\shop\models\CouponUser;
use app\components\controllers\BackController;
use app\modules\shop\models\search\CouponSearch;
use app\modules\shop\models\search\CouponUserSearch;

/**
 * CouponController implements the CRUD actions for Coupon model.
 */
class CouponController extends BackController
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
			//'access' => [
			//	'class' => AccessControl::className(),
			//	'only' => ['index','create','update','delete','attach','change-count','delete-attach','apply-coupon'],
			//	'rules' => [
			//		[
			//			'allow' => Yii::$app->user->identity->role == 'admin',
			//			'actions' => ['index','create','update','delete','attach','change-count','delete-attach'],
			//			'roles' => ['@'],
			//		],
			//		[
			//			'allow' => true,
			//			'actions' => ['apply-coupon'],
			//			'roles' => ['?'],
			//		],
			//	],
			//],
		];
	}
	
	/**
	 * Lists all Coupon models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new CouponSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
		return $this->render('index', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}
	
	
	/**
	 * Creates a new Coupon model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new Coupon();
		if(Yii::$app->request->isPost) {
			$post = Yii::$app->request->post();
			$model->load($post);
			switch($post['Coupon']['type']) {
				case 'is_products_with_ship':
					$model->is_products_with_ship = true;
					break;
				case 'is_only_products':
					$model->is_only_products = true;
					break;
				case 'is_only_ship':
					$model->is_only_ship = true;
					break;
				
			}
			
			switch($post['Coupon']['num']) {
				case '0':
					$model->is_percent = true;
					break;
				case '1':
					$model->is_usd = true;
					break;
			}
			$model->start_date = strtotime($model->start_date);
			$model->end_date = strtotime($model->end_date);
			if($model->validate() && $model->save()) {
				return $this->redirect(['index']);
				
			}
		}
		
		return $this->render('_form', [
			'model' => $model,
		]);
	}
	
	/**
	 * Updates an existing Coupon model.
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
		
		if(Yii::$app->request->isPost) {
			$post = Yii::$app->request->post();
			$model->load($post);
			switch($post['Coupon']['type']) {
				case 'is_products_with_ship':
					$model->is_products_with_ship = true;
					$model->is_only_products = false;
					$model->is_only_ship = false;
					break;
				case 'is_only_products':
					$model->is_only_products = true;
					$model->is_products_with_ship = false;
					$model->is_only_ship = false;
					break;
				case 'is_only_ship':
					$model->is_only_ship = true;
					$model->is_products_with_ship = false;
					$model->is_only_products = false;
					break;
				
			}
			
			switch($post['Coupon']['num']) {
				case '0':
					$model->is_percent = true;
					$model->is_usd = false;
					
					break;
				case '1':
					$model->is_usd = true;
					$model->is_percent = false;
					
					break;
			}
			$model->start_date = strtotime($model->start_date);
			$model->end_date = strtotime($model->end_date);
			
			if($model->validate() && $model->save()) {
				return $this->redirect(['index']);
				
			}
		}
		
		return $this->render('_form', [
			'model' => $model,
		]);
	}
	
	/**
	 * Deletes an existing Coupon model.
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
		$model->status = 1;
		$model->safe_delete = true;
		$model->save();
		
		return $this->redirect(['index']);
	}
	
	/**
	 * Finds the Coupon model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 *
	 * @param integer $id
	 *
	 * @return Coupon the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if(($model = Coupon::findOne($id)) !== null) {
			return $model;
		}
		
		throw new NotFoundHttpException('The requested page does not exist.');
	}
	
	public function actionApplyCoupon()
	{
		$coupon = Coupon::find()
		                ->with('couponUsers')
		                ->where(['name' => Yii::$app->request->post('coupon')])
		                ->one();
		/**
		 * @var $coupon Coupon
		 */
		
		$data = [];
		if(!$coupon) {
			$data['status'] = 'Error';
			$data['message'] = 'Coupon is invalid';
			
		} else {
			$data = $coupon->isValid();
			
		}
		if(empty($data)) {
			Yii::$app->session->set('coupon', $coupon);
			
		} else {
			if(Yii::$app->session->has('coupon')) {
				Yii::$app->session->remove('coupon');
			}
		}
		
		return $this->asJson($data);
	}
	
	public function actionAttach($id)
	{
		$searchModel = new CouponUserSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams, $id);
		$model = new CouponUser;
		$users = ArrayHelper::map(User::find()->select('email')->where(['role' => 'user'])->asArray()->all(), 'email', 'email');
		
		if(Yii::$app->request->isPost) {
			$post = Yii::$app->request->post();
			$count = $post['CouponUser']['count'];
			foreach($post['CouponUser']['email'] as $email) {
				$cu = new CouponUser;
				$cu->email = $email;
				$cu->count = $count;
				$cu->coupon_id = $id;
				$cu->save();
			}
		}
		
		return $this->render('attach', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
			'model'        => $model,
			'users'        => $users,
		]);
		
	}
	
	public function actionDeleteAttach($id)
	{
		$cu = CouponUser::findOne(['id' => $id]);
		$id = $cu->coupon_id;
		$cu->delete();
		
		return $this->redirect(['attach', 'id' => $id]);
	}
	
	public function actionChangeCount($id)
	{
		$cu = CouponUser::findOne(['id' => $id]);
		$cu->count = Yii::$app->request->post('count');
		
		return $cu->save();
	}
	
	public function actionDeleteAll()
	{
		$ids = Yii::$app->request->post('ids');
		return Coupon::updateAll(['status' => 1, 'safe_delete' => true], ['id' => $ids]);
	}
}
