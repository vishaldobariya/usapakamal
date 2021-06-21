<?php
/**
 *
 * @author    Paul Stolyarov <teajeraker@gmail.com>
 * @copyright industrialax.com
 * @license   https://industrialax.com/crm-general-license
 */

namespace app\modules\shipstation\models;

use yii\base\Model;
use yii\helpers\Json;
use app\modules\provider\models\Store;

class ShipStation extends Model
{
	
	public $firstName;
	
	public $lastName;
	
	public $email;
	
	public $password;
	
	public $companyName;
	
	public function rules()
	{
		return [
			[['firstName', 'lastName', 'email', 'password', 'companyName'], 'required'],
			['email', 'email'],
		];
	}
	
	public static function getCarriers($store_id)
	{
		$store = Store::findOne(['id' => $store_id]);
		$curl = curl_init();
		
		curl_setopt_array($curl, [
			CURLOPT_URL            => "https://ssapi.shipstation.com/carriers",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING       => "",
			CURLOPT_MAXREDIRS      => 10,
			CURLOPT_TIMEOUT        => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST  => "GET",
			CURLOPT_HTTPHEADER     => [
				"Host: ssapi.shipstation.com",
				"Authorization:  Basic " . base64_encode($store->api_key . ':' . $store->api_secret),
			],
		]);
		
		$response = curl_exec($curl);
		
		curl_close($curl);
		
		return Json::decode($response);
	}
	
	public static function getRates($store_id)
	{
		$carriers = self::getCarriers($store_id);
		
		$store = Store::findOne(['id' => $store_id]);
		
		$customer = \Yii::$app->session->get('customer');
		$rates = [];
		foreach($carriers as $carrier) {
			$postfields = [
				'carrierCode'    => $carrier['code'],
				'fromPostalCode' => $store->user->zip,
				'toCountry'      => $customer->contry,
				'toPostalCode'   => $customer->zip,
				'weight'         => ['value' => 2, 'units' => 'ounces'],
			];
			
			$curl = curl_init();
			
			curl_setopt_array($curl, [
				CURLOPT_URL            => "https://ssapi.shipstation.com/shipments/getrates",
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING       => "",
				CURLOPT_MAXREDIRS      => 10,
				CURLOPT_TIMEOUT        => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST  => "POST",
				CURLOPT_POSTFIELDS     => Json::encode($postfields),
				CURLOPT_HTTPHEADER     => [
					"Host: ssapi.shipstation.com",
					"Authorization: Basic " . base64_encode($store->api_key . ':' . $store->api_secret),
					"Content-Type: application/json",
				],
			]);
			
			$response = curl_exec($curl);
			
			curl_close($curl);
			$rates[] = array_merge(['name' => $carrier['name'], 'code' => $carrier['code'], 'accountNumber' => $carrier['accountNumber']], ['methods' => Json::decode($response)]);
		}
		
		return $rates;
	}
}
