<?php
/**
 *
 * @author    Paul Stolyarov <teajeraker@gmail.com>
 * @copyright industrialax.com
 * @license   https://industrialax.com/crm-general-license
 */

namespace app\modules\shipstation\helpers;

use app\modules\shop\models\StoreProduct;
use Yii;
use yii\helpers\Url;
use yii\helpers\Json;
use app\modules\shop\models\Order;
use app\modules\shop\models\Customer;

class ShipStationApi
{
	
	public static function createAccount($data)
	{
		$curl = curl_init();
		
		curl_setopt_array($curl, [
			CURLOPT_URL            => "https://ssapi.shipstation.com/accounts/registeraccount",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING       => "",
			CURLOPT_MAXREDIRS      => 10,
			CURLOPT_TIMEOUT        => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST  => "POST",
			CURLOPT_POSTFIELDS     => $data,
			CURLOPT_HTTPHEADER     => [
				"Host: ssapi.shipstation.com",
				"Content-Type: application/json",
			],
		]);
		
		$response = curl_exec($curl);
		
		curl_close($curl);
		dd($response);
	}
	
	public static function createOrder(Order $order, $shipDate)
	{
		$customer = $order->customer;
		/**
		 * @var $customer Customer
		 */
		$items = [];
		
		$total = 0;
		
		$i = $order->hasEngraving();
		
		$eng = $i > 0 ? $i * Yii::$app->settings->front_engraving : 0;
		$tax = ($order->ship_price + $order->total_provider_cost + $eng) * ((float)\Yii::$app->settings->tax / 100);
		$total = $order->ship_price + $order->total_provider_cost + $eng + $tax;
		
		foreach($order->items as $item) {
			$items[] = [
				'name'      => $item->product->name,
				'imageUrl'  => Url::base(true) . $item->product->thumb,
				'quantity'  => $item->qty,
				'unitPrice' => $item->provider_price,
				'sku' => StoreProduct::find()->where(['product_id' => $item->product_id])->one()->sku,
			];
			if($item->engravings){
				$items[] = [
					'name'      => 'Engraving for '.$item->product->name,
					'imageUrl'  => !empty($item->engravings->imageFront) ? Url::base(true) . $item->engravings->imageFront[0]->src : '',
					'quantity'  => $item->qty,
					'unitPrice' => Yii::$app->settings->front_engraving,
				];
			}
		}
		
		$post_fields = [
			'orderNumber'      => Yii::$app->user->identity->store->name . '_' . ($order->id + 10000),
			'orderDate'        => Yii::$app->formatter->asDate($order->created_at),
			'paymentDate'      => Yii::$app->formatter->asDate($order->created_at),
			'orderStatus'      => 'awaiting_shipment',
			'customerUsername' => $customer->email,
			'customerEmail'    => $customer->email,
			'billTo'           => [
				'name'       => $customer->billingname,
				'street1'    => $customer->billing_address ?? $customer->address,
				'street2'    => $customer->billing_address_two ?? $customer->adress_two,
				'city'       => $customer->billing_city ?? $customer->city,
				'state'      => $customer->billing_state ?? $customer->state,
				'postalCode' => $customer->billing_zip ?? $customer->zip,
				'country'    => $customer->billing_country ?? $customer->contry,
				'phone'      => $customer->billing_phone ?? $customer->phone,
			],
			'shipTo'           => [
				'name'       => $customer->name,
				'street1'    => $customer->address,
				'street2'    => $customer->adress_two,
				'city'       => $customer->city,
				'state'      => $customer->state,
				'postalCode' => $customer->zip,
				'country'    => $customer->contry,
				'phone'      => $customer->phone,
			],
			'items'            => $items,
			'amountPaid'       => $total,
			'taxAmount'        => $tax,
			'shippingAmount'   => $order->ship_price,
			'paymentMethod'    => 'Credit Card',
			'confirmation'     => 'delivery',
			'packageCode'      => 'package',
			'shipDate'         => $shipDate,
			'gift'             => $order->note ? true : false,
			'giftMessage' => 'To Name: '.$order->note_name.' | To Email: '.$order->note_email.' | Message: '.$order->note,
		];
		//$data = Json::encode($post_fields);
		//dd($data);
		$key = base64_encode(Yii::$app->user->identity->store->api_key . ':' . Yii::$app->user->identity->store->api_secret);
		
		$curl = curl_init();
		
		curl_setopt_array($curl, [
			CURLOPT_URL            => "https://ssapi.shipstation.com/orders/createorder",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING       => "",
			CURLOPT_MAXREDIRS      => 10,
			CURLOPT_TIMEOUT        => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST  => "POST",
			CURLOPT_POSTFIELDS     => Json::encode($post_fields),
			CURLOPT_HTTPHEADER     => [
				"Host: ssapi.shipstation.com",
				"Authorization: Basic " . $key,
				"Content-Type: application/json",
			],
		]);
		
		$response = curl_exec($curl);
		
		$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		
		curl_close($curl);
		if($http_code == 401) {
			return ['code' => '401', 'message' => 'Please check your api keys for ShipStation'];
		} elseif($http_code == 400) {
			$response = Json::decode($response);
			
			return ['code' => '400', 'message' => $response['Message']];
		} else {
			$response = Json::decode($response);
			
			$order->ship_order_key = $response['orderKey'];
			$order->ship_id = (string)$response['orderId'];
			$order->provider_status = 1;
			
			$order->save();
			
			return [
				'code'    => '200',
				'message' => 'Order #' . ($order->id + 10000) . ' was sent to ShipStation. Please login to ShipStation and accept the updated ShipStation Terms and Conditions to proceed.',
			];
			
		}
	}
	
	public static function createLabel(Order $order, $ship_date)
	{
		$curl = curl_init();
		$key = base64_encode(Yii::$app->user->identity->store->api_key . ':' . Yii::$app->user->identity->store->api_secret);
		
		$post_fields = [
			'orderId'      => $order->ship_id,
			'shipDate'     => $ship_date,
			'carrierCode'  => $order->ship_code,
			'serviceCode'  => $order->ship_service_code,
			'packageCode'  => 'package',
			"weight"       => [
				'value' => 2,
				'units' => 'pounds',
			],
			'confirmation' => 'none',
			'testLabel'    => true,
		];
		curl_setopt_array($curl, [
			CURLOPT_URL            => "https://ssapi.shipstation.com/orders/createlabelfororder",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING       => "",
			CURLOPT_MAXREDIRS      => 10,
			CURLOPT_TIMEOUT        => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST  => "POST",
			CURLOPT_POSTFIELDS     => Json::encode($post_fields),
			CURLOPT_HTTPHEADER     => [
				"Host: ssapi.shipstation.com",
				"Authorization: Basic " . $key,
				"Content-Type: application/json",
			],
		]);
		
		$response = curl_exec($curl);
		
		$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		
		curl_close($curl);
		
		$response = Json::decode($response);
		dd($response);
		$order->tracking_number = $response['trackingNumber'];
		$order->save();
		
		self::generateLabel($response['labelData'], 'Shipping Labels ' . $order->ship_id, $order);
		
	}
	
	public static function generateLabel($pdf, $name_pdf, $order)
	{
		file_put_contents(Yii::getAlias('@app/web/upload/' . $name_pdf . '.pdf'), base64_decode($pdf));
		Yii::$app->mailer->compose('@app/web/mail/ship_label')
		                 ->attach(Yii::getAlias('@app/web/upload/' . $name_pdf . '.pdf'))
		                 ->setTo($order->customer->email)
		                 ->setBcc('brandonmaxwelltwo@gmail.com')
		                 ->setFrom([MAIL_USER => 'Royal Batch'])
		                 ->setSubject('Shipping Label')
		                 ->send();
		
		return unlink(Yii::getAlias('@app/web/upload/' . $name_pdf . '.pdf'));
		
	}
}
