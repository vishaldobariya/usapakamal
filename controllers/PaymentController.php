<?php
/**
 *
 * @author    Paul Stolyarov <teajeraker@gmail.com>
 * @copyright industrialax.com
 * @license   https://industrialax.com/crm-general-license
 */

namespace app\controllers;

use Yii;
use yii\helpers\Json;
use yii\web\Controller;

class PaymentController extends Controller
{
	
	private $gwlogin     = 'rbatch4121';
	
	private $restrictKey = '56ff02a01934c4572d342dcdffbd18ad';
	
	private $login_url   = 'https://secure.quantumgateway.com/cgi/tqgwdbe.php';
	
	private $agent       = 'Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.4) Gecko/20030624 Netscape/7.1 (affgrabber)';
	
	
	public function actionPay()
	{
		$card = \Yii::$app->request->post('Card');
		$customer = \Yii::$app->request->post('Customer');
		$ship_customer = \Yii::$app->session->get('customer');
		
		//Card Info
		$num = str_replace(' ', '', $card['number']);
		$cvv = $card['cvv'];
		$mo = explode('/', $card['exp'])[0];
		$yr = explode('/', $card['exp'])[1];
		
		if((int)$mo > 12 || (int)date('Y') > (int)$yr || ((int)date('m') > (int)$mo && (int)date('Y') == (int)$yr)) {
			return $this->asJson(['status' => 'error', 'message' => 'Invalid Exp Date']);
		}
		//Customer Billing Info
		$fname = $customer['billing_first_name'];
		$lname = $customer['billing_last_name'];
		$address = $customer['billing_address'] . ' ' . $customer['billing_address_two'];
		$zip = $customer['billing_zip'];
		$city = $customer['billing_city'];
		$phone = $customer['billing_phone'];
		$state = $customer['billing_state'];
		$email = $ship_customer->email;
		
		//Amount
		$amount = Yii::$app->cart->cost + (Yii::$app->session->get('shipping')['price'] ?? 0);
		
		$post_fields =
			"&gwlogin=$this->gwlogin" .
			"&RestrictKey=$this->restrictKey" .
			"&trans_type=CREDIT" .
			"&ccnum=$num" .
			"&ccmo=$mo" .
			"&amount=$amount" .
			"&ccyr=$yr" .
			"&FNAME=$fname" .
			"&LNAME=$lname" .
			"&BADDR1=$address" .
			"&BZIP1=$zip" .
			"&CVV2=$cvv" .
			"&BCITY=$city" .
			"&phone=$phone" .
			"&BSTATE=$state" .
			"&BCUST_EMAIL=$email" .
			"&initial_amount=$amount";
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_USERAGENT, $this->agent);
		curl_setopt($ch, CURLOPT_URL, $this->login_url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
		$result = curl_exec($ch);
		
		curl_close($ch);
		
		dd($result);
	}
}
