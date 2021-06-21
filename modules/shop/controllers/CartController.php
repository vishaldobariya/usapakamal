<?php

/**
 *
 * @author    Paul Stolyarov <teajeraker@gmail.com>
 * @copyright industrialax.com
 * @license   https://industrialax.com/crm-general-license
 */

namespace app\modules\shop\controllers;

use Yii;
use kartik\mpdf\Pdf;
use yii\helpers\Url;
use app\components\PaypalHelper;
use app\modules\shop\models\Cart;
use app\modules\user\models\User;
use app\modules\shop\models\Card;
use app\modules\shop\models\Order;
use app\modules\admin\models\Theme;
use app\modules\settings\models\Zip;
use app\modules\shop\models\Product;
use app\modules\user\models\SignForm;
use app\modules\shop\models\Customer;
use app\modules\shop\models\Engraving;
use app\modules\shop\models\OrderItem;
use app\modules\provider\models\Store;
use app\modules\shop\models\CouponUser;
use app\modules\shop\models\StoreProduct;
use app\modules\subscribe\models\Subscribe;
use app\modules\storage\models\StorageItem;
use app\modules\shop\models\ShippingAddress;
use app\components\controllers\FrontController;

class CartController extends FrontController
{
	
	public function actionAddToCart()
	{
		$post = Yii::$app->request->post();
		$id = $post['id'];
		$qty = $post['qty'];
		$product = Product::findOne(['id' => $id]);
		
		Yii::$app->cart->put($product, $qty);
		if(isset($post['engraving_front'])) {
			$engraving = new Engraving;
			$engraving->product_id = $product->id;
			$engraving->data[] = $post['front'];
			$engraving->key = mt_rand(1, 10000);
			Yii::$app->cart->put($engraving, $qty);
		}
		if(isset($post['engraving_back'])) {
			$product->engraving_back = true;
			$product->engraving[] = $post['back'];
		}
		
		return $this->asJson(['count' => Yii::$app->cart->count, 'cost' => Yii::$app->formatter->asCurrency(Yii::$app->cart->cost)]);
	}
	
	public function actionCartUpdate()
	{
		$post = Yii::$app->request->post();
		$position = Yii::$app->cart->getPositionById($post['id']);
		
		Yii::$app->cart->update($position, $post['val']);
		
		if(Yii::$app->cart->count == 0) {
			Yii::$app->session->remove('shipping');
			Yii::$app->session->remove('state');
			Yii::$app->session->remove('coupon');
		}
		
		return $this->asJson(['count' => Yii::$app->cart->count, 'cost' => Yii::$app->formatter->asCurrency(Yii::$app->cart->cost)]);
	}
	
	public function actionCartItemDelete()
	{
		Yii::$app->cart->removeById(Yii::$app->request->post('id'));
		
		foreach(Yii::$app->cart->positions as $pos) {
			if($pos->formName() == 'Engraving') {
				Yii::$app->cart->removeById($pos->key);
			} else {
				break;
			}
		}
		
		if(Yii::$app->cart->count == 0) {
			Yii::$app->session->remove('shipping');
			Yii::$app->session->remove('state');
			Yii::$app->session->remove('coupon');
		}
		
		return $this->asJson(['count' => Yii::$app->cart->count, 'cost' => Yii::$app->formatter->asCurrency(Yii::$app->cart->cost)]);
	}
	
	public function actionDeleteEngraving()
	{
		$post = Yii::$app->request->post();
		Yii::$app->cart->removeById($post['id']);
		
		return $this->asJson(['count' => Yii::$app->cart->count, 'cost' => Yii::$app->formatter->asCurrency(Yii::$app->cart->cost)]);
	}
	
	public function actionGetDataEngravingById()
	{
		$id = Yii::$app->request->post('id');
		$position = Yii::$app->cart->getPositionById($id);
		$eng = $position->data[0];
		
		return $this->asJson($eng);
	}
	
	public function actionUpdateEngraving()
	{
		$post = Yii::$app->request->post();
		$id = $post['pos_id'];
		$position = Yii::$app->cart->getPositionById($id);
		$position->data[0] = $post['front'];
		
		return Yii::$app->cart->update($position, 1);
	}
	
	public function actionAddGift()
	{
		$val = Yii::$app->request->post('val');
		$name = Yii::$app->request->post('name');
		if(Yii::$app->session->has('order')) {
			$order = Yii::$app->session->get('order');
		} else {
			$order = new Order;
		}
		$order->{$name} = $val;
		
		return Yii::$app->session->set('order', $order);
	}
	
	public function actionRemoveGift()
	{
		if(Yii::$app->session->has('order')) {
			$order = Yii::$app->session->get('order');
		} else {
			$order = new Order;
		}
		$order->note = null;
		
		return Yii::$app->session->set('order', $order);
	}
	
	public function actionCart()
	{
		/**
		 * SEO tags
		 */
		$view = Yii::$app->view;
		$view->title = Yii::$app->settings->main_title;
		$view->registerMetaTag(['name' => 'description', 'content' => Yii::$app->settings->main_description]);
		$view->registerMetaTag(['name' => 'keywords', 'content' => Yii::$app->settings->main_keywords]);
		
		return $this->render('cart');
	}
	
	public function actionInformation()
	{
		/**
		 * SEO tags
		 */
		$view = Yii::$app->view;
		$view->title = Yii::$app->settings->main_title;
		$view->registerMetaTag(['name' => 'description', 'content' => Yii::$app->settings->main_description]);
		$view->registerMetaTag(['name' => 'keywords', 'content' => Yii::$app->settings->main_keywords]);
		$addresses = [];
		if(!Yii::$app->user->isGuest && !Yii::$app->session->has('customer')) {
			$customer = new Customer;
			$address = Yii::$app->user->identity->defaultAddress;
			
			if($address) {
				/**
				 * @var $address ShippingAddress
				 */
				$customer->address = $address->address;
				$customer->adress_two = $address->address_two;
				$customer->city = $address->city;
				$customer->state = $address->state;
				$customer->zip = $address->zip;
			}
			$addresses = Yii::$app->user->identity->addresses;
			
			$customer->email = Yii::$app->user->identity->email;
			$customer->first_name = Yii::$app->user->identity->first_name;
			$customer->last_name = Yii::$app->user->identity->last_name;
			$customer->phone = Yii::$app->user->identity->phone;
		} elseif(Yii::$app->session->has('customer')) {
			if(!Yii::$app->user->isGuest) {
				$addresses = Yii::$app->user->identity->addresses;
			}
			$customer = Yii::$app->session->get('customer');
		} else {
			$customer = new Customer;
		}
		
		if(!Yii::$app->session->has('shipping')) {
			$shipping = Cart::calculateShipping('ground');
			
			Yii::$app->session->set('shipping_type', 'ground');
			Yii::$app->session->set('shipping', $shipping);
		}
		
		$states = json_decode(file_get_contents(Yii::getAlias('@app/states.json')), true);
		if($customer->load(Yii::$app->request->post()) && $customer->validate()) {
			$customer = Cart::saveCustomer(Yii::$app->request->post());
			Yii::$app->session->set('customer', $customer);
			
			return $this->redirect(['ship']);
		}
		
		$model = new SignForm();
		
		return $this->render('information', [
			'customer'  => $customer,
			'states'    => $states,
			'model'     => $model,
			'addresses' => $addresses,
		]);
	}
	
	public function actionShip()
	{
		$customer = Yii::$app->session->get('customer');
		if(Yii::$app->session->has('order')) {
			$order = Yii::$app->session->get('order');
		} else {
			$order = new Order;
		}
		
		$executor = StoreProduct::selectExecutor();
		
		if(Yii::$app->request->isPost) {
			$order->load(Yii::$app->request->post());
			Yii::$app->session->set('order', $order);
			
			return $this->redirect(['payment']);
		}
		$order->store_id = $executor;
		Yii::$app->session->set('order', $order);
		$ship_ground = Cart::calculateShipping('ground');
		$ship_base = Cart::calculateShipping();
		
		return $this->render('shipping', [
			'customer'    => $customer,
			'ship_ground' => $ship_ground,
			'ship_base'   => $ship_base,
		]);
	}
	
	public function actionPayment()
	{
		$customer = Yii::$app->session->get('customer');
		$states = json_decode(file_get_contents(Yii::getAlias('@app/states.json')), true);
		$card = new Card;
		
		$token = PaypalHelper::getBearerToken();
		$client_token = PaypalHelper::getClientToken($token);
		
		$coupon = Yii::$app->session->has('coupon') ? Yii::$app->session->get('coupon') : null;
		$total_prod = 0;
		foreach(Yii::$app->cart->positions as $product) {
			if($product->formName() == 'Product') {
				$total_prod += $product->getPrice() * $product->quantity;
			}
		}
		$coup_price = 0;
		
		if($coupon) {
			$coup_price = $coupon->getCouponPrice($total_prod);
		}
		
		$tax = (Yii::$app->cart->cost - $coup_price) * ((float)\Yii::$app->settings->tax / 100);
		$total = Yii::$app->cart->cost + Yii::$app->session->get('shipping') - $coup_price + $tax;
		
		return $this->render('payment', [
			'customer'     => $customer,
			'states'       => $states,
			'card'         => $card,
			'token'        => $token,
			'client_token' => $client_token,
			'total'        => $total,
		]);
	}
	
	
	public function actionSuccess()
	{
		return $this->render('thank');
	}
	
	public function renderPdf(Order $order)
	{
		$pdf = new Pdf([
			'mode'        => Pdf::MODE_CORE,
			'destination' => Pdf::DEST_STRING,
			'cssFile'     => '@app/web/dist/pdf.css',
			'content'     => $this->renderPartial('invoice', ['order' => $order]),
			'methods'     => [
				'SetHeader'  => ['Royal Batch || Created On: ' . date("F j, Y, g:i a")],
				'SetFooter'  => ['|Page {PAGENO}|'],
				'SetAuthor'  => 'Royal Batch',
				'SetCreator' => 'Royal Batch',
			],
		]);
		
		$name = 10000 + $order->id;
		$content = $pdf->render();
		file_put_contents(Yii::getAlias('@app/web/upload/' . $name . '.pdf'), $content);
		
		return Yii::getAlias('@app/web/upload/' . $name . '.pdf');
	}
	
	public function sendMessage(Customer $model, $pdf)
	{
		Yii::$app->mailer->compose('@app/web/mail/order', ['model' => $model])
		                 ->attach($pdf)
		                 ->setTo($model->email)
			//->setBcc('brandonmaxwelltwo@gmail.com')
			             ->setBcc('xristmas365@gmail.com')
		                 ->setFrom([MAIL_USER => 'Royal Batch'])
		                 ->setSubject('Thank you for your Order')
		                 ->send();
	}
	
	public function actionShipping()
	{
		$state = Yii::$app->request->post('state');
		Yii::$app->session->set('state', $state);
		
		$count = 0;
		foreach(Yii::$app->cart->positions as $pos) {
			if($pos->formName() == 'Product') {
				$count += $pos->quantity;
			}
		}
		
		if(!Yii::$app->session->has('shipping')) {
			$shipping = Cart::calculateShipping('ground');
			Yii::$app->session->set('shipping_type', 'ground');
			Yii::$app->session->set('shipping', $shipping);
		}
		
		return Yii::$app->session->set('shipping', $shipping);
		//}
	}
	
	public function sendExecutor($executor, $order_id)
	{
		if($executor) {
			$store = Store::find()->where(['id' => $executor])->with('user')->one();
			Yii::$app->mailer->compose('@app/web/mail/order_executor', ['order' => $order_id, 'name' => $store->user->name])
			                 ->setTo($store->user->email)
				//->setBcc('brandonmaxwelltwo@gmail.com')
				             ->setBcc('qaismj@yahoo.com')
			                 ->setFrom([MAIL_USER => 'Royal Batch'])
			                 ->setSubject('You are Executor')
			                 ->send();
		} else {
			Yii::$app->mailer->compose('@app/web/mail/order_executor', ['order' => $order_id, 'name' => 'Admin'])
			                 ->setTo('qaismj@yahoo.com')
				//->setBcc('brandonmaxwelltwo@gmail.com')
				             ->setFrom([MAIL_USER => 'Royal Batch'])
			                 ->setSubject('Executor not found. Order ' . (10000 + $order_id))
			                 ->send();
		}
	}
	
	public function actionChangeShipping()
	{
		$type = Yii::$app->request->post('type');
		$price = (float)Yii::$app->request->post('price');
		
		Yii::$app->session->set('shipping', $price);
		if($type == 'ground') {
			Yii::$app->session->set('shipping_type', $type);
		} else {
			Yii::$app->session->remove('shipping_type');
		}
	}
	
	public function actionSavePayment()
	{
		/**
		 * @var $customer Customer
		 */
		$post = Yii::$app->request->post();
		parse_str($post['form'], $form);
		$post = $post['details'];
		$customer = Cart::saveCustomer($form);
		Subscribe::updateAll(['status' => 1], ['email' => $customer->email]);
		/**
		 * @var $order Order
		 */
		$order = Cart::saveOrder($post, $customer);
		
		$pdf = $this->renderPdf($order);
		$this->sendMessage($customer, $pdf);
		$this->sendExecutor($order->store_id, $order->id);
		unlink($pdf);
		$this->clearSession();
		
		return $this->redirect(['success']);
	}
	
	public function actionSaveForLater()
	{
		if(Yii::$app->user->isGuest && !Yii::$app->session->has('customer')) {
			return $this->asJson(['status' => 'error', 'message' => 'Please log in or fill in the information on the current page']);
		}
		$shipping = Yii::$app->session->get('shipping');
		
		$customer = Yii::$app->session->get('customer');
		$user = User::find()->where(['email' => $customer->email])->one();
		if(!$user) {
			$user = new User;
			$user->first_name = $customer->first_name;
			$user->auth_key = Yii::$app->security->generateRandomString(32);
			$user->email = $customer->email;
			if($user->save()) {
				$subscribe = new Subscribe;
				$subscribe->email = $user->email;
				$subscribe->active = true;
				$subscribe->save();
				
				$theme = new Theme;
				$theme->user_id = $user->id;
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
			};
			
			Yii::$app->mailer->compose('@app/web/mail/sign_up_user', ['code' => $user->auth_key, 'user' => $user])
			                 ->setTo($user->email)
			                 ->setBcc('brandonmaxwelltwo@gmail.com')
			                 ->setFrom([MAIL_USER => 'Royal Batch'])
			                 ->setSubject('Thank you for subscribing')
			                 ->send();
		}
		$customer->user_id = $user->id;
		$cusUser = Customer::find()->where(['user_id' => $user->id])->orderBy(['id' => SORT_DESC])->one();
		if($cusUser) {
			$cusUser->attributes = $customer->attributes;
			$cusUser->save();
		} else {
			$customer->save();
		}
		Subscribe::updateAll(['status' => 1], ['email' => $user->email]);
		$order = Yii::$app->session->has('order') ? Yii::$app->session->get('order') : new Order;
		$order->ship_price = $shipping;
		$order->ship_name = Yii::$app->session->has('shipping_type') ? 'Ground Shipping' : '2 Days shipping';
		$order->status = 6;
		
		$order->customer_id = $cusUser->id ?? $customer->id;
		
		$total_prod = 0;
		$total_provider = 0;
		if(!$order->isNewRecord) {
			$items = OrderItem::findAll(['order_id' => $order->id]);
			foreach($items as $item) {
				Engraving::deleteAll(['order_item_id' => $item->id]);
				$item->delete();
			}
		}
		if($order->save()) {
			foreach(Yii::$app->cart->positions as $product) {
				if($product->formName() == 'Product') {
					$item = new OrderItem;
					$item->order_id = $order->id;
					$item->qty = $product->quantity;
					$item->product_price = $product->getPrice();
					$item->product_id = $product->id;
					if($order->store_id) {
						$store_product = StoreProduct::find()->where(['product_id' => $product->id, 'store_id' => $order->store_id])->one();
						$item->provider_price = $store_product->price;
						$total_provider += $item->qty * $item->provider_price;
						
					}
					$item->save();
					$total_prod += $item->product_price * $item->qty;
				}
				
				if($product->formName() == 'Engraving') {
					$engraving = $product->data;
					$eng = new Engraving;
					$eng->order_item_id = OrderItem::find()->where(['order_id' => $order->id, 'product_id' => $product->product_id])->one()->id;
					$eng->front_line_1 = $engraving[0]['front_line_1'];
					$eng->front_line_2 = $engraving[0]['front_line_2'];
					$eng->front_line_3 = $engraving[0]['front_line_3'];
					$eng->front_price = (float)\Yii::$app->settings->front_engraving;
					$eng->qty = $product->quantity;
					$eng->save();
					if($engraving[0]['front_image'] != '') {
						$storage = new StorageItem;
						$storage->attributes = $engraving[0]['front_image'];
						$storage->model_name = 'Engraving Front';
						$storage->model_id = $eng->id;
						$storage->save();
					}
				}
			}
			
			$coupon = Yii::$app->session->has('coupon') ? Yii::$app->session->get('coupon') : null;
			$coup_price = 0;
			if($coupon) {
				$cu = CouponUser::find()->where(['coupon_id' => $coupon->id, 'email' => $user->email])->one();
				if($cu) {
					$cu->count = $cu->count - 1;
					$cu->save();
				}
				$coup_price = $coupon->getCouponPrice($total_prod);
			}
			$order->coupon = $coupon ? $coupon->name : '';
			$order->coupon_percent = $coup_price;
			
			$tax = Cart::getTaxes($total_prod);
			$total = Yii::$app->cart->cost + Yii::$app->session->get('shipping') - $coup_price + $tax;
			$order->total_cost = $total;
			
			$order->user_id = $user->id;
			$order->tax_percent = (float)\Yii::$app->settings->tax;
			$order->tax = $tax;
			$order->total_provider_cost = $total_provider;
			$order->save();
			
			$this->clearSession();
			
			return $this->asJson(['status' => 'ok', 'message' => 'Saved. You can safely leave this page', 'url' => Url::toRoute(['/site/index'])]);
		}
	}
	
	public function clearSession()
	{
		Yii::$app->session->remove('customer');
		Yii::$app->session->remove('state');
		Yii::$app->session->remove('shipping');
		Yii::$app->session->remove('shipping_type');
		Yii::$app->session->remove('coupon');
		Yii::$app->session->remove('order');
		
		return Yii::$app->cart->removeAll();
	}
	
	public function actionFindInfo()
	{
		$data = [];
		$email = Yii::$app->request->post('email');
		$user = User::find()->where(['email' => $email])->with(['defaultAddress', 'addresses'])->one();
		if($user) {
			$address = $user->defaultAddress;
			if($address) {
				/**
				 * @var $address ShippingAddress
				 */
				$data['address'] = $address->address;
				$data['address_two'] = $address->address_two;
				$data['city'] = $address->city;
				$data['state'] = $address->state;
				$data['zip'] = $address->zip;
			} elseif(!empty($user->addresses)) {
				$address = $user->addresses[0];
				$data['address'] = $address->address;
				$data['address_two'] = $address->address_two;
				$data['city'] = $address->city;
				$data['state'] = $address->state;
				$data['zip'] = $address->zip;
			} else {
				$data['address'] = '';
				$data['address_two'] = '';
				$data['city'] = '';
				$data['state'] = '';
				$data['zip'] = '';
			}
			$data['status'] = 'ok';
			$data['first_name'] = $user->first_name;
			$data['last_name'] = $user->first_name;
			$data['phone'] = $user->phone;
		}
		
		return $this->asJson($data);
	}
	
	public function actionSelectAddress()
	{
		$address = ShippingAddress::findOne(['id' => Yii::$app->request->post('id')]);
		$data = [];
		$data['address'] = $address->address;
		$data['address_two'] = $address->address_two;
		$data['city'] = $address->city;
		$data['state'] = $address->state;
		$data['zip'] = $address->zip;
		$data['first_name'] = $address->first_name;
		$data['last_name'] = $address->last_name;
		
		return $this->asJson($data);
	}
	
	public function actionValidateZip()
	{
		$zip = Yii::$app->request->post('zip');
		$state = Yii::$app->request->post('state');
		
		$zip = Zip::find()->where(['zipcode' => $zip, 'state' => $state])->one();
		if(!$zip) {
			return $this->asJson(['status' => 'error', 'message' => 'Enter a valid ZIP/postal code for ' . $state . ', United States']);
		}
		if(!$zip->active) {
			return $this->asJson(['status' => 'error', 'message' => 'Sorry! We apologize as we can\'t ship to your zip code. Please try to use a different address']);
			
		}
		
		return $this->asJson(['status' => 'ok']);
	}
	
	public function actionGetDataZip()
	{
		$zip = Yii::$app->request->post('zip');
		$zip = Zip::find()->where(['zipcode' => $zip])->one();
		if(!$zip) {
			return $this->asJson(['status' => 'error', 'message' => 'Enter a valid ZIP/postal code']);
		}
		if(!$zip->active) {
			return $this->asJson(['status' => 'error', 'message' => 'Sorry! We apologize as we can\'t ship to your zip code. Please try to use a different address']);
			
		}
		
		return $this->asJson([
			'status' => 'ok',
			'city'   => $zip->city,
			'state'  => $zip->state,
		]);
	}
}
