<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use app\modules\user\models\User;
use app\modules\shop\models\Price;
use app\modules\shop\models\Order;
use app\modules\admin\models\Theme;
use app\modules\shop\models\Product;
use app\modules\provider\models\Store;
use app\modules\shop\models\StoreProduct;
use app\modules\shop\models\ShippingAddress;

class DashboardController extends Controller
{
	
	public $layout = 'admin';
	
	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::class,
				'rules' => [
					[
						'allow' => true,
						'roles' => ['@'],
					],
				
				],
			],
		];
	}
	
	/**
	 * Administration index page
	 */
	public function actionIndex()
	{
		if(Yii::$app->user->identity->role == 'distributor') {
			return $this->redirect(['/shop/store/my-products']);
		}
		
		if(Yii::$app->user->identity->role == 'user') {
			return $this->redirect(['welcome']);
		}
		$count = Product::find()->count();
		$orders = Order::find()->count();
		$summary = Order::find()->select('SUM(total_cost) as sum')->all();
		$alerts = Product::find()
		                 ->with(['images', 'storeProducts'])
		                 ->having(['<', '(price-provider_price)/provider_price', 0.3])
		                 ->groupBy(['product.id'])->count();
		
		return $this->render('index', [
			'count'   => $count,
			'orders'  => $orders,
			'summary' => $summary[0]->sum,
			'alerts'  => $alerts,
		]);
	}
	
	public function actionChangeTheme()
	{
		$key = Yii::$app->request->post('key');
		$value = Yii::$app->request->post('value');
		
		/**
		 * @var $theme Theme
		 */
		$theme = Yii::$app->user->identity->theme;
		
		return $theme->updateAttributes([$key => $value]);
	}
	
	public function actionProfile()
	{
		$model = User::findOne(['id' => Yii::$app->user->id]);
		$model->store_name = $model->store->name;
		$prods = 0;
		$con_prods = 0;
		$not_con_prods = 0;
		if($model->role == 'distributor') {
			$query = StoreProduct::find()->where(['store_id' => $model->store->id]);
			$prods = (clone $query)->count();
			$con_prods = (clone $query)->andWhere(['connected' => true])->count();
			$not_con_prods = (clone $query)->andWhere(['connected' => false])->count();
		}
		
		if(Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());
			if($model->new_pass != '') {
				$model->password = Yii::$app->security->generatePasswordHash($model->new_pass);
			}
			if($model->store_name != $model->store->name) {
				Store::updateAll(['name' => $model->store_name], ['user_id' => $model->id]);
			}
			$model->save();
			
			return $this->redirect('profile');
		}
		
		return $this->render('profile', [
			'model'         => $model,
			'prods'         => $prods,
			'con_prods'     => $con_prods,
			'not_con_prods' => $not_con_prods,
		]);
	}
	
	public function actionUserProfile()
	{
		$model = User::findOne(['id' => Yii::$app->user->id]);
		$ship_address = null;
		$addresses = [];
		$states = [];
		if($model->role == 'user') {
			$ship_address = new ShippingAddress;
			$addresses = ShippingAddress::find()->where(['user_id' => Yii::$app->user->id])->orderBy(['id' => SORT_ASC])->all();
			$states = json_decode(file_get_contents(Yii::getAlias('@app/states.json')), true);
		}
		if($model->load(Yii::$app->request->post()) && $model->validate()) {
			$model->save();
			
			return $this->redirect('user-profile');
			
		}
		
		return $this->render('user-profile', [
			'model'        => $model,
			'states'       => $states,
			'ship_address' => $ship_address,
			'addresses'    => $addresses,
		]);
	}
	
	public function actionFindProducts($q = null)
	{
		$query = Product::find();
		
		$qs = explode(' ', $q);
		
		foreach($qs as $key) {
			$query->andWhere(['ilike', 'name', $key]);
		}
		$products = $query->all();
		$result['results'] = [];
		foreach($products as $inventory) {
			$result['results'][] = ['id' => $inventory->id, 'text' => $inventory->name];
		}
		
		return $this->asJson($result);
	}
	
	public function actionGetPricesByProduct()
	{
		$prices = Price::findAll(['product_id' => Yii::$app->request->post('val')]);
		$result = [];
		foreach(ArrayHelper::getColumn($prices, 'created_at') as $date) {
			$result['labels'][] = Yii::$app->formatter->asDate($date);
		}
		$result['data'] = ArrayHelper::getColumn($prices, 'price');
		
		return $this->asJson($result);
	}
	
	public function actionWelcome()
	{
		return $this->render('welcome');
	}
	
	public function actionAddAddress()
	{
		$data = [];
		$model = new ShippingAddress;
		$model->load(Yii::$app->request->post());
		$model->user_id = Yii::$app->user->id;
		if($model->save()) {
			$data['status'] = 'ok';
		} else {
			$data['status'] = 'error';
			
			$keys = array_keys($model->errors);
			$key = array_shift($keys);
			$data['errors'] = [
				'key'     => $key,
				'message' => $model->errors[$key][0],
			];
			
		}
		
		return $this->asJson($data);
	}
	
	public function actionSetAddress()
	{
		ShippingAddress::updateAll(['is_default' => false], ['user_id' => Yii::$app->user->id]);
		
		return ShippingAddress::updateAll(['is_default' => true], ['id' => Yii::$app->request->post('val')]);
	}
	
	public function actionRemoveAddress()
	{
		return ShippingAddress::deleteAll(['id' => Yii::$app->request->post('id')]);
	}
}
