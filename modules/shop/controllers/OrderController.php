<?php

namespace app\modules\shop\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\helpers\FileHelper;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use app\modules\shop\models\Order;
use app\modules\shop\models\Coupon;
use app\modules\shop\models\Product;
use app\modules\shop\models\Customer;
use app\modules\shop\models\Engraving;
use app\modules\shop\models\OrderItem;
use app\modules\provider\models\Store;
use app\modules\shop\models\StoreProduct;
use app\modules\shop\models\ShippingAddress;
use app\components\controllers\BackController;
use app\modules\shop\models\search\OrderSearch;
use app\modules\shipstation\helpers\ShipStationApi;

/**
 * OrderController implements the CRUD actions for Order model.
 */
class OrderController extends BackController
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
				'only'  => [
					'index',
					'by-status',
					'push-provider',
					'provider-index',
					'order-accept',
					'order-done',
					'order-problematic',
					'order-refuse',
					'order-shipped',
					'order-delivered',
					'user-index',
				],
				'rules' => [
					
					[
						'allow'   => Yii::$app->user->identity->role == 'admin' || Yii::$app->session->has('role'),
						'actions' => ['index', 'delete', 'by-status', 'push-provider'],
						'roles'   => ['@'],
					],
					[
						'allow'   => Yii::$app->user->identity->role == 'distributor',
						'actions' => ['provider-index', 'order-accept', 'order-done', 'order-problematic', 'order-refuse', 'order-shipped', 'order-delivered'],
						'roles'   => ['@'],
					],
					[
						'allow'   => Yii::$app->user->identity->role == 'user',
						'actions' => ['user-index', 'continue'],
						'roles'   => ['@'],
					],
				],
			],
		]);
	}
	
	/**
	 * Lists all Order models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new OrderSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$count = Product::find()->count();
		$orders = Order::find()->count();
		$summary = Order::find()->select('SUM(total_cost) as sum')->all();
		$alerts = Product::find()
		                 ->with(['images', 'storeProducts'])
		                 ->having(['<', '(price-provider_price)/provider_price', 0.3])
		                 ->groupBy(['product.id'])->count();
		$exec = ArrayHelper::map(Store::find()->asArray()->all(), 'id', 'name');
		$exec[0] = 'Executor not found';
		ksort($exec);
		
		return $this->render('index', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
			'count'        => $count,
			'orders'       => $orders,
			'summary'      => $summary[0]->sum,
			'exec'         => $exec,
			'alerts'       => $alerts,
		]);
	}
	
	public function actionProviderIndex()
	{
		$searchModel = new OrderSearch();
		$dataProvider = $searchModel->searchProvider(Yii::$app->request->queryParams);
		$exec = ArrayHelper::map(Store::find()->asArray()->all(), 'id', 'name');
		$exec[0] = 'Executor not found';
		ksort($exec);
		
		return $this->render('provider-index', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
			'exec'         => $exec,
		
		]);
	}
	
	
	public function actionUserIndex()
	{
		$searchModel = new OrderSearch();
		$dataProvider = $searchModel->searchUser(Yii::$app->request->queryParams);
		
		return $this->render('user-index', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
		
		]);
	}
	
	public function actionUserSavedIndex()
	{
		$searchModel = new OrderSearch();
		$dataProvider = $searchModel->searchUserSaved(Yii::$app->request->queryParams);
		
		return $this->render('user-index', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
		
		]);
	}
	
	public function actionDelete($id)
	{
		$this->findModel($id)->delete();
		$items = OrderItem::findAll(['order_id' => $id]);
		foreach($items as $item) {
			Engraving::deleteAll(['order_item_id' => $item->id]);
			$item->delete();
		}
		if(Yii::$app->user->identity->role == 'user') {
			return $this->redirect(['user-saved-index']);
		}
		
		return $this->redirect(['index']);
	}
	
	public function actionView($id)
	{
		$order = Order::find()
		              ->where(['id' => $id])
		              ->with(['items.product.images', 'customer', 'items.engravings'])
		              ->one();
		if(!$order) {
			throw new NotFoundHttpException();
		}
		$coupon = Coupon::find()->where(['name' => $order->coupon])->one();
		
		if($coupon) {
			$sum = 0;
			foreach($order->items as $item) {
				/**
				 * @var $item OrderItem
				 */
				$sum += $item->product_price * $item->qty;
			}
			$coup_price = $coupon->getCouponPrice($sum, $order->ship_price);
			
		}
		if(Yii::$app->user->identity->role == 'distributor') {
			if($order->store_id != Yii::$app->user->identity->store->id) {
				throw new NotFoundHttpException();
			}
			
			return $this->render('provider_item', [
				'order' => $order,
			]);
		}
		$ship_address = null;
		$addresses = [];
		$states = [];
		$ship_address = new ShippingAddress;
		if(Yii::$app->user->identity->role == 'user') {
			$addresses = ShippingAddress::find()->where(['user_id' => Yii::$app->user->id])->orderBy(['id' => SORT_ASC])->all();
			$states = json_decode(file_get_contents(Yii::getAlias('@app/states.json')), true);
		}
		
		return $this->render('item', [
			'order'        => $order,
			'coup_price'   => $coup_price ?? 0,
			'states'       => $states,
			'ship_address' => $ship_address,
			'addresses'    => $addresses,
		]);
	}
	
	/**
	 * Finds the Order model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 *
	 * @param integer $id
	 *
	 * @return Order the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if(($model = Order::findOne($id)) !== null) {
			return $model;
		}
		
		throw new NotFoundHttpException('The requested page does not exist.');
	}
	
	public function actionOrderAccept()
	{
		$order = Order::findOne(['id' => Yii::$app->request->post('id')]);
		
		$order->ship_date = Yii::$app->request->post('shipDate');
		$order->status = 1;
		$order->save();
		$data = ShipStationApi::createOrder($order, $order->ship_date);
		//$order = Order::findOne(['id' => Yii::$app->request->post('id')]);
		
		//ShipStationApi::createLabel($order, $order->ship_date);
		
		Yii::$app->mailer->compose('@app/web/mail/order_accept', ['order' => Yii::$app->request->post('id'), 'name' => Yii::$app->user->identity->store->name])
		                 ->setBcc('brandonmaxwelltwo@gmail.com')
		                 ->setTo('qaismj@yahoo.com')
		                 ->setFrom([MAIL_USER => 'Royal Batch'])
		                 ->setSubject('Order #' . (10000 + Yii::$app->request->post('id')) . ' was accepted')
		                 ->send();
		
		return $this->asJson($data);
	}
	
	public function actionOrderDone()
	{
		Order::updateAll(['status' => 7], ['id' => Yii::$app->request->post('id')]);
		
		return Yii::$app->mailer->compose('@app/web/mail/order_done', ['order' => Yii::$app->request->post('id'), 'name' => Yii::$app->user->identity->store->name])
		                        ->setBcc('brandonmaxwelltwo@gmail.com')
		                        ->setTo('qaismj@yahoo.com')
		                        ->setFrom([MAIL_USER => 'Royal Batch'])
		                        ->setSubject('Order #' . (10000 + Yii::$app->request->post('id')) . ' was completed')
		                        ->send();
	}
	
	public function actionOrderProblematic()
	{
		Order::updateAll(['status' => 4], ['id' => Yii::$app->request->post('id')]);
		
		return Yii::$app->mailer->compose('@app/web/mail/order_problematic', ['order' => Yii::$app->request->post('id'), 'name' => Yii::$app->user->identity->store->name])
		                        ->setBcc('brandonmaxwelltwo@gmail.com')
		                        ->setTo('qaismj@yahoo.com')
		                        ->setFrom([MAIL_USER => 'Royal Batch'])
		                        ->setSubject('Order #' . (10000 + Yii::$app->request->post('id')) . ' is problematic')
		                        ->send();
	}
	
	public function actionOrderShipped()
	{
		Order::updateAll(['status' => 2], ['id' => Yii::$app->request->post('id')]);
		
		return Yii::$app->mailer->compose('@app/web/mail/order_shipped', ['order' => Yii::$app->request->post('id'), 'name' => Yii::$app->user->identity->store->name])
		                        ->setBcc('brandonmaxwelltwo@gmail.com')
		                        ->setTo('qaismj@yahoo.com')
		                        ->setFrom([MAIL_USER => 'Royal Batch'])
		                        ->setSubject('Order #' . (10000 + Yii::$app->request->post('id')) . ' was shipped')
		                        ->send();
	}
	
	public function actionOrderDelivered()
	{
		Order::updateAll(['status' => 3], ['id' => Yii::$app->request->post('id')]);
		
		return Yii::$app->mailer->compose('@app/web/mail/order_delivered', ['order' => Yii::$app->request->post('id'), 'name' => Yii::$app->user->identity->store->name])
		                        ->setBcc('brandonmaxwelltwo@gmail.com')
		                        ->setTo('qaismj@yahoo.com')
		                        ->setFrom([MAIL_USER => 'Royal Batch'])
		                        ->setSubject('Order #' . (10000 + Yii::$app->request->post('id')) . ' was delivered')
		                        ->send();
	}
	
	public function actionOrderRefuse()
	{
		$order = Order::find()->where(['id' => Yii::$app->request->post('id')])->with('items')->one();
		$executor = StoreProduct::selectExecutor($order);
		$order->store_id = $executor;
		$order->status = 5;
		$order->save();
		Yii::$app->mailer->compose('@app/web/mail/order_refuse', [
			'order'        => Yii::$app->request->post('id'),
			'name'         => Yii::$app->user->identity->store->name,
			'sec_provider' => $executor == null ? null : Store::findOne(['id' => $executor])->name,
		])
		                 ->setBcc('brandonmaxwelltwo@gmail.com')
		                 ->setTo('qaismj@yahoo.com')
		                 ->setFrom([MAIL_USER => 'Royal Batch'])
		                 ->setSubject('Provider refused order')
		                 ->send();
		if($executor != null) {
			$store = Store::findOne(['id' => $executor]);
			Yii::$app->mailer->compose('@app/web/mail/order_executor', ['order' => Yii::$app->request->post('id'), 'name' => $store->name])
			                 ->setTo($store->user->email)
			                 ->setBcc('brandonmaxwelltwo@gmail.com')
			                 ->setBcc('qaismj@yahoo.com')
			                 ->setFrom([MAIL_USER => 'Royal Batch'])
			                 ->setSubject('You are Executor')
			                 ->send();
		}
		
		return true;
	}
	
	
	public function actionReadNote()
	{
		$model = Order::findOne(['id' => Yii::$app->request->post('id')]);
		
		return $this->asJson([
			'note'   => $model->note,
			'number' => '#' . (10000 + $model->id),
			'id'     => $model->id,
		]);
	}
	
	public function actionSaveNote()
	{
		$model = Order::findOne(['id' => Yii::$app->request->post('id')]);
		
		$model->note = Yii::$app->request->post('note');
		
		return $model->save();
	}
	
	public function actionPushProvider()
	{
		$order = Order::find()->where(['id' => Yii::$app->request->post('id')])->with('items')->one();
		$order->store_id = Yii::$app->request->post('val');
		$total = 0;
		foreach($order->items as $item) {
			/**
			 * @var $item OrderItem
			 */
			
			$provider_product = StoreProduct::findOne(['product_id' => $item->product_id, 'store_id' => $order->store_id]);
			
			if($provider_product) {
				$item->provider_price = $provider_product->price;
				$total += $provider_product->price * $item->qty;
			} else {
				$item->provider_price = 0;
			}
			$item->save();
			
		}
		$order->total_provider_cost = $total;
		
		return $order->save();
	}
	
	public function actionByStatus()
	{
		$searchModel = new OrderSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$count = Product::find()->count();
		$orders = Order::find()->count();
		$summary = Order::find()->select('SUM(total_cost) as sum')->all();
		$alerts = Product::find()
		                 ->with(['images', 'storeProducts'])
		                 ->having(['<', '(price-provider_price)/provider_price', 0.3])
		                 ->groupBy(['product.id'])->count();
		$exec = ArrayHelper::map(Store::find()->asArray()->all(), 'id', 'name');
		$exec[0] = 'Executor not found';
		ksort($exec);
		
		return $this->render('by_status', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
			'count'        => $count,
			'orders'       => $orders,
			'summary'      => $summary[0]->sum,
			'exec'         => $exec,
			'alerts'       => $alerts,
		]);
	}
	
	public function actionChangeShip()
	{
		$val = Yii::$app->request->post('val');
		$address = ShippingAddress::find()->where(['id' => $val])->one();
		$order = Order::find()->where(['id' => Yii::$app->request->post('order')])->with('customer')->one();
		$customer = $order->customer;
		$customer->address = $address->address;
		$customer->adress_two = $address->address_two;
		$customer->city = $address->city;
		$customer->state = $address->state;
		$customer->zip = $address->zip;
		$customer->save();
		
		Yii::$app->session->set('order', $order);
		
		return Yii::$app->session->set('customer', $customer);
		
	}
	
	public function actionChangeBill()
	{
		$customer = Yii::$app->session->get('customer');
		/**
		 * @var $customer Customer
		 */
		
		if(Yii::$app->request->post('same') == 0) {
			$data = [];
			
			$customer->billing_address = $customer->address;
			$customer->billing_address_two = $customer->adress_two;
			$customer->billing_state = $customer->state;
			$customer->billing_country = $customer->contry;
			$customer->billing_city = $customer->city;
			$customer->billing_zip = $customer->zip;
			$customer->save();
			
			$data['bill_address'] = $customer->billing_address;
			$data['bill_address_two'] = $customer->billing_address_two;
			$data['bill_city'] = $customer->billing_city;
			$data['bill_country'] = $customer->billing_country;
			$data['bill_state'] = $customer->billing_state;
			$data['bill_zip'] = $customer->billing_zip;
			
			Yii::$app->session->set('customer', $customer);
			
			return $this->asJson($data);
		} else {
			$customer->load(Yii::$app->request->post('form'));
			
			return $customer->save();
		}
	}
	
	public function actionSavePayment()
	{
		$order = Yii::$app->session->get('order');
		$order->status = 0;
		$order->save();
		Yii::$app->session->remove('order');
		Yii::$app->session->remove('customer');
		
		return $this->redirect(['view', 'id' => $order->id]);
	}
	
	public function actionContinue($id)
	{
		$order = Order::findOne(['id' => $id]);
		if(!$order || $order->status != 6) {
			throw new NotFoundHttpException();
		}
		$customer = $order->customer;
		Yii::$app->session->set('customer', $customer);
		Yii::$app->session->set('order', $order);
		Yii::$app->session->set('shipping', $order->ship_price);
		$items = OrderItem::find()->with(['product.images', 'engravings.imageFront'])->where(['order_id' => $order->id])->all();
		if($order->coupon) {
			$coupon = Coupon::find()->where(['name' => $order->coupon])->one();
			Yii::$app->session->set('coupon', $coupon);
		}
		foreach($items as $item) {
			Yii::$app->cart->put($item->product, $item->qty);
			if(!empty($item->engravings)) {
				foreach($item->engravings as $eng) {
					/**
					 * @var $eng Engraving
					 */
					$image = $eng->imageFront;
					$front_image = '';
					
					if(!empty($image)) {
						/**
						 * @var $image array
						 */
						$front_image = [
							'path'     => $image[0]->path,
							'name'     => $image[0]->name,
							'size'     => $image[0]->size,
							'type'     => $image[0]->type,
							'base_url' => $image[0]->base_url,
						];
					}
					$engraving = new Engraving;
					$engraving->product_id = $item->product_id;
					$engraving->data[] = [
						'front_line_1' => $eng->front_line_1,
						'front_line_2' => $eng->front_line_2,
						'front_line_3' => $eng->front_line_3,
						'front_image'  => $front_image,
					];
					$engraving->key = mt_rand(1, 10000);
					Yii::$app->cart->put($engraving, $eng->qty);
				}
				
			}
		}
		
		return $this->redirect(['/shop/cart/cart']);
	}
	
	public function actionExportOrder()
	{
		ini_set('safe_mode', 'Off');
		ini_set('memory_limit', '-1');
		set_time_limit(10000000);
		
		$searchModel = new OrderSearch();
		$dataProvider = $searchModel->searchExport(Yii::$app->request->queryParams);
		$models = $dataProvider->models;
		
		$data = [
			'Total Items Qty',
			'Total Items Price',
			'Internal Notes',
			'Note Name',
			'Note Email',
			'Coupon Price',
			'Ship Price',
			'Ship Name',
			'Tax',
			'Total Price Order',
			'TC Customer ID',
			'TC Customer Email',
			'TC Customer Name',
			'TC Customer Phone',
			'Created At',
			'Status',
			'Transaction Id',
			'Tracking Number',
			'Provider',
			'Ship Date',
			'Ship Order Key',
			'Shipping Address',
			'Billing Address',
		
		];
		$count = 0;
		foreach($models as $model) {
			if(count($model['items']) > $count) {
				$count = count($model['items']);
			}
		}
		
		for($i = 1; $i <= $count; $i++) {
			$data[] = 'Item #' . $i . ': SKU';
			$data[] = 'Item #' . $i . ': Provider SKU';
			$data[] = 'Item #' . $i . ': Price';
			$data[] = 'Item #' . $i . ': Provider Price';
			$data[] = 'Item #' . $i . ': Name';
			$data[] = 'Item #' . $i . ': Qt';
			$data[] = 'Item #' . $i . ': Eng';
		}
		$list[] = $data;
		
		FileHelper::createDirectory(Yii::getAlias('@app/web/upload/csv/'), 0775, true);
		
		foreach($models as $model) {
			$customer = $model['customer'];
			$store = Store::find()->where(['id' => $model['store_id']])->one();
			$sumQty = array_sum(ArrayHelper::getColumn($model['items'], 'qty'));
			$sum = 0;
			$coupon_price = 0;
			$items_info = [];
			foreach($model['items'] as $item) {
				$providerProduct = '';
				if($store) {
					$providerProduct = StoreProduct::find()->where(['product_id' => $item['product_id'], 'store_id' => $store->id])->one()->sku;
				}
				$sum += $item['product_price'] * $item['qty'];
				$items_info[] = $item['product']['sku'];
				$items_info[] = $providerProduct;
				$items_info[] = Yii::$app->formatter->asDecimal($item['product_price'], 2);
				$items_info[] = Yii::$app->formatter->asDecimal($item['provider_price'], 2);
				$items_info[] = $item['product']['name'];
				$items_info[] = $item['qty'];
				$items_info[] = !empty($item['engravings']) != null ? 'Yes' : 'No';
				
			}
			
			if($model['couponModel']) {
				$coupon = Coupon::find()->where(['id' => $model['couponModel']['id']])->one();
				$coupon_price = $coupon->getCouponPrice($sum, $model['ship_price']);
				
			}
			$common = [
				$sumQty,
				$sum,
				$model['note'],
				$model['note_name'],
				$model['note_email'],
				Yii::$app->formatter->asDecimal($coupon_price, 2),
				Yii::$app->formatter->asDecimal($model['ship_price'], 2),
				$model['ship_price'],
				Yii::$app->formatter->asDecimal($model['tax'], 2),
				Yii::$app->formatter->asDecimal($model['total_cost'], 2),
				$model['customer']['id'],
				$model['customer']['email'],
				$model['customer']['first_name'] . ' ' . $model['customer']['first_name'],
				$model['customer']['phone'],
				date("F j, Y, g:i a", $model['created_at']),
				Order::STATUSES[$model['status']],
				$model['transaction_id'],
				$model['tracking_number'],
				$store ? $store->name : 'No Provider',
				$model['ship_date'],
				$model['ship_order_key'],
				$customer['address'] . ' ' . $customer['adress_two'] . ' ' . $customer['city'] . ' ' . $customer['state'] . ' ' . $customer['zip'],
				$customer['billing_address'] . ' ' . $customer['billing_address_two'] . ' ' . $customer['billing_city'] . ' ' . $customer['billing_state'] . ' ' . $customer['billing_zip'],
			
			];
			
			$list[] = array_merge($common, $items_info);
			
		}
		$fp = fopen(Yii::getAlias('@app/web/upload/csv/order-list.csv'), 'w');
		
		foreach($list as $fields) {
			fputcsv($fp, $fields);
		}
		
		fclose($fp);
		//return Yii::getAlias('@app/web/upload/csv/list.csv');
		\Yii::$app->response->sendFile(Yii::getAlias('@app/web/upload/csv/order-list.csv'));
		unlink(Yii::getAlias('@app/web/upload/csv/order-list.csv'));
	}
	
	public function actionGetEngravings()
	{
		$id = Yii::$app->request->post('item');
		$engravings = Engraving::find()->with('imageFront')->where(['order_item_id' => $id])->all();
		$html = '';
		foreach($engravings as $engraving) {
			$img = '';
			$lines = '';
			if($engraving->imageFront) {
				$img = '<img class="card-img-top" src="' . $engraving->imageFront[0]->src . '" >';
			}
			if($engraving->front_line_1) {
				$lines .= '<p class="card-text">Line 1: ' . $engraving->front_line_1 . '</p>';
			}
			if($engraving->front_line_2) {
				$lines .= '<p class="card-text">Line 1: ' . $engraving->front_line_2 . '</p>';
			}
			if($engraving->front_line_3) {
				$lines .= '<p class="card-text">Line 1: ' . $engraving->front_line_3 . '</p>';
			}
			
			$html .= '<div class="card card-header border col-md-6" style="width: 18rem;">' . $img . '
  <div class="card-body">
    ' . $lines . '
  </div>
</div>';
		}
		
		return $html;
	}
}
