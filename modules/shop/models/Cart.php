<?php
/**
 *
 * @author    Paul Stolyarov <teajeraker@gmail.com>
 * @copyright industrialax.com
 * @license   https://industrialax.com/crm-general-license
 */

namespace app\modules\shop\models;

use Yii;
use yii\base\Model;
use app\modules\user\models\User;
use app\modules\admin\models\Theme;
use app\modules\settings\models\Zip;
use app\modules\storage\models\StorageItem;
use app\modules\subscribe\models\Subscribe;

class Cart extends Model
{
	
	public static function saveCustomer($form = [])
	{
		$customer = Yii::$app->session->get('customer') ?? new Customer;
		
		if(!empty($form)) {
			$customer->load($form);
		}
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
				$subscribe->status = 2;
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
		
		$customer->save();
		
		$shipAddress = ShippingAddress::find()->where(['address' => $customer->address, 'user_id' => $user->id])->one();
		if(!$shipAddress) {
			$shipAddress = new ShippingAddress;
			$shipAddress->address = $customer->address;
			$shipAddress->address_two = $customer->adress_two;
			$shipAddress->city = $customer->city;
			$shipAddress->state = $customer->state;
			$shipAddress->zip = $customer->zip;
			$shipAddress->user_id = $user->id;
			$shipAddress->first_name = $customer->first_name;
			$shipAddress->last_name = $customer->last_name;
			$shipAddress->save();
		}
		
		return $customer;
	}
	
	public static function saveOrder($post, $customer)
	{
		$shipping = Yii::$app->session->get('shipping');
		$order = Yii::$app->session->get('order');
		/**
		 * @var $order Order
		 */
		$order->ship_price = $shipping;
		$order->ship_name = Yii::$app->session->has('shipping_type') ? 'Ground Shipping' : '2 Days shipping';
		$order->status = 0;
		$order->customer_id = $customer->id;
		$order->transaction_id = $post['id'];
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
				$coup_price = $coupon->getCouponPrice($total_prod);
				$cu = CouponUser::find()->where(['coupon_id' => $coupon->id, 'email' => $customer->user->email])->one();
				if($cu) {
					$cu->count = $cu->count - 1;
					$cu->save();
				}
			}
			$order->coupon = $coupon ? $coupon->name : '';
			$order->coupon_percent = $coup_price;
			
			$tax = self::getTaxes($total_prod);
			$total = Yii::$app->cart->cost + Yii::$app->session->get('shipping') - $coup_price + $tax;
			$order->total_cost = $total;
			
			$order->user_id = $customer->user_id;
			$order->tax_percent = (float)\Yii::$app->settings->tax;
			$order->tax = $tax;
			$order->total_provider_cost = $total_provider;
			$order->save();
			
		}
		
		return $order;
	}
	
	/**
	 * @return float|int
	 */
	public static function getTaxes($total_prod)
	{
		$taxes = 0;
		if(Yii::$app->session->has('customer')) {
			$customer = Yii::$app->session->get('customer');
			/**
			 * @var $customer Customer
			 */
			if($customer && $customer->zip) {
				$tax = Zip::findOne(['zipcode' => $customer->zip]);
				$taxes = Yii::$app->cart->cost * ((float)$tax->tax / 100);
				$end = Yii::$app->cart->cost - $total_prod;
				if(Yii::$app->session->get('coupon')) {
					$coupon = Yii::$app->session->get('coupon');
					/**
					 * @var $coupon Coupon
					 */
					
					if(($coupon->is_products_with_ship || $coupon->is_only_products) && $coupon->is_percent) {
						$taxes = ($end + $total_prod * (1 - (float)$coupon->value / 100)) * ((float)$tax->tax / 100);
					}
					if($coupon->is_products_with_ship && $coupon->is_usd) {
						$taxes = ($end + $total_prod - $coupon->value) * ((float)$tax->tax / 100);
					}
					if($coupon->is_only_products && $coupon->is_usd) {
						$taxes = ($end + $total_prod - $coupon->value) * ((float)$tax->tax / 100);
					}
					
				}
			}
			
		}
		
		return $taxes;
	}
	
	public static function calculateShipping($type = null)
	{
		$count = 0;
		foreach(Yii::$app->cart->positions as $pos) {
			if($pos->formName() == 'Product') {
				$count += $pos->quantity;
			}
		}
		$price_per_bottle = $type == null ? (float)Yii::$app->settings->ship_one_bottle : (float)Yii::$app->settings->ship_one_bottle_ground;
		$price_more_bottle = $type == null ? (float)Yii::$app->settings->ship_more_bottle : (float)Yii::$app->settings->ship_more_bottle_ground;
		
		return !empty(Yii::$app->cart->positions) ? (($count - 1) * $price_more_bottle + $price_per_bottle) : 0;
	}
}
