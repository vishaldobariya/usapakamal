<?php
/**
 *
 * @author    Paul Stolyarov <teajeraker@gmail.com>
 * @copyright industrialax.com
 * @license   https://industrialax.com/crm-general-license
 */

namespace app\components;

use yii\helpers\Json;

class PaypalHelper
{
	
	public static function getBearerToken()
	{
		$curl = curl_init();
		
		curl_setopt_array($curl, [
			CURLOPT_URL            => "https://api-m.sandbox.paypal.com/v1/oauth2/token",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING       => "",
			CURLOPT_MAXREDIRS      => 10,
			CURLOPT_TIMEOUT        => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST  => "POST",
			CURLOPT_USERPWD        => PAYPAL_CLIENT. ":" . PAYPAL_SECRET,
			CURLOPT_POSTFIELDS     => "grant_type=client_credentials",
			CURLOPT_HTTPHEADER     => [
				"Accept: application/json",
				"Accept-Language: en_US",
			],
		]);
		
		$response = curl_exec($curl);
		curl_close($curl);
		sleep(1);
		return Json::decode($response)['access_token'];
	}
	
	public static function getClientToken($token)
	{
		$curl = curl_init();
		
		curl_setopt_array($curl, [
			CURLOPT_URL            => "https://api-m.sandbox.paypal.com/v1/identity/generate-token",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING       => "",
			CURLOPT_MAXREDIRS      => 10,
			CURLOPT_TIMEOUT        => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST  => "POST",
			CURLOPT_HTTPHEADER     => [
				"Content-Type: application/json",
				"Authorization: Bearer " . $token,
				"Accept-Language: en_US",
			],
		]);
		
		$response = curl_exec($curl);
		
		curl_close($curl);
		return Json::decode($response)['client_token'];
	}
	
}
