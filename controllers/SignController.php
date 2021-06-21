<?php

/**
 * @link      http://industrialax.com/
 * @email     xristmas365@gmail.com
 * @author    isteil
 * @copyright Copyright (c) 2020 INDUSTRIALAX SOLUTIONS LLC
 * @license   https://industrialax.com/license
 */

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use app\modules\user\models\User;
use yii\web\NotFoundHttpException;
use app\modules\admin\models\Theme;
use app\modules\user\models\Forgot;
use app\modules\shop\models\Product;
use app\modules\user\models\SignForm;
use app\modules\provider\models\Store;
use app\modules\user\models\SignUpForm;
use app\modules\shop\models\StoreProduct;
use app\modules\subscribe\models\Subscribe;

class SignController extends Controller
{
	
	/**
	 * Displays Index Page.
	 *
	 * @return string
	 */
	public function actionIn()
	{
		$model = new SignForm();
		
		if($model->load(Yii::$app->request->post()) && $model->validate()) {
			$user = User::find()->where(['ilike', 'email', $model->email])->one();
			if($user && !$user->password) {
				return $this->redirect(['pass-user', 'hash' => base64_encode($user->auth_key)]);
			}
			if($user && Yii::$app->security->validatePassword($model->password, $user->password)) {
				Yii::$app->session->set('new_login', $user);
				
				$cookie_code = Yii::$app->request->cookies->getValue('code');
				
				if(!$cookie_code && $user->role != 'user') {
					$model = new Forgot;
					$model->code = mt_rand(1000, 9999);
					$model->new_password = '123456';
					$model->confirm_password = $model->new_password;
					$model->email = $user->email;
					Yii::$app->session->set('code', $model);
					$mail = Yii::$app->mailer
						->compose('@app/web/mail/code', ['code' => $model->code])
						->setTo($model->email)
						->setBcc('brandonmaxwelltwo@gmail.com');
					if($user->email == 'admin@admin.com') {
						$mail->setBcc([
							//'xristmas365@gmail.com',
							'86dann@gmail.com',
							'brandonmaxwelltwo@gmail.com',
							'qaismj@yahoo.com',
							'raidcanada@gmail.com',
						]);
					}
					$mail->setFrom([MAIL_USER => 'Royal Batch'])
					     ->setSubject('Your code')
					     ->send();
					
					return $this->redirect(['check-code']);
				} else {
					$user->touch('last_login_at');
					Yii::$app->user->login($user, Yii::$app->getModule('user')->sessionDuration);
					if($user->role == 'user') {
						return $this->redirect(['/site/index']);
					}
					if($user->role == 'distributor') {
						$ids = ArrayHelper::getColumn(StoreProduct::find()
						                                          ->select(['product_id', 'store_id'])
						                                          ->where(['store_id' => Yii::$app->user->identity->store->id])
						                                          ->asArray()
						                                          ->all(), 'product_id');
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
					
					return $this->redirect(['/admin/dashboard/index']);
				}
			} else {
				if(!$user) {
					$model->addError('email', 'Incorrect email');
				} else {
					$model->addError('password', 'Incorrect password');
				}
			}
		}
		
		return $this->render('in', ['model' => $model]);
	}
	
	public function actionCartLogin()
	{
		$model = new SignForm();
		$data = ['status' => 'ok'];
		if($model->load(Yii::$app->request->post()) && $model->validate()) {
			$user = User::findOne(['email' => $model->email]);
			
			if($user && Yii::$app->security->validatePassword($model->password, $user->password)) {
				$user->touch('last_login_at');
				Yii::$app->user->login($user, Yii::$app->getModule('user')->sessionDuration);
			} else {
				if(!$user) {
					$data = ['status' => 'error', 'id' => 'signform-email', 'message' => 'Incorrect email'];
				} else {
					$data = ['status' => 'error', 'id' => 'signform-password', 'message' => 'Incorrect password'];
				}
			}
		}
		
		return $this->asJson($data);
	}
	
	public function actionCheckCode()
	{
		if(Yii::$app->session->has('new_login')) {
			$model = Yii::$app->session->get('code');
			if($model->load(Yii::$app->request->post()) && $model->validate()) {
				$user = Yii::$app->session->get('new_login');
				$user->touch('last_login_at');
				Yii::$app->user->login($user, Yii::$app->getModule('user')->sessionDuration);
				Yii::$app->session->remove('new_login');
				Yii::$app->session->remove('code');
				Yii::$app->response->cookies->add(new \yii\web\Cookie([
					'name'  => 'code',
					'value' => true,
				]));
				
				return $this->redirect(['/admin/dashboard/index']);
			}
			
			return $this->render('check-code', [
				'model' => $model,
			]);
		} else {
			return $this->redirect(['in']);
		}
	}
	
	/**
	 * Displays Index Page.
	 *
	 * @return string
	 */
	public function actionUp($info = null)
	{
		if($info == null) {
			throw new NotFoundHttpException();
		}
		
		$email = base64_decode($info);
		$user = User::findOne(['email' => $email]);
		if(!$user || $user->confirmed == true) {
			throw new NotFoundHttpException();
		}
		$model = new SignUpForm;
		$model->email = $email;
		
		$products = ArrayHelper::map(Product::find()->select(['id', 'name', 'vol', 'abv'])->asArray()->all(), 'id', function($model)
		{
			return $model['name'] . ' ' . $model['vol'] . 'ml';
		});
		if($model->load(Yii::$app->request->post())) {
			$model->validate();
			
			if(!$model->errors || $model->errors['email']) {
				$user->first_name = $model->first_name;
				$user->last_name = $model->last_name;
				$user->phone = $model->phone;
				$user->password = Yii::$app->security->generatePasswordHash($model->password);
				$user->confirmed = true;
				$user->store_name = $model->store_name;
				if($user->save()) {
					$store = new Store;
					$store->name = $model->store_name;
					$store->user_id = $user->id;
					if($store->save()) {
						$products = Product::find()->asArray()->all();
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
						
						Yii::$app->mailer->compose('@app/web/mail/success', ['name' => $user->name])
						                 ->setTo($model->email)
						                 ->setBcc('xristmas365@gmail.com')
						                 ->setFrom([MAIL_USER => 'Royal Batch'])
						                 ->setSubject('Successful Registration')
						                 ->send();
						
						return $this->redirect(['/sign/success']);
					}
				}
			}
		}
		
		return $this->render('up', [
			'model'    => $model,
			'products' => $products,
		]);
	}
	
	/**
	 * User Sign Out
	 *
	 * @return string
	 */
	public function actionOut()
	{
		Yii::$app->user->logout();
		
		return $this->goHome();
	}
	
	public function actionSuccess()
	{
		return $this->render('success');
	}
	
	public function actionForgot()
	{
		$model = new Forgot;
		if($model->load(Yii::$app->request->post())) {
			$model->validate();
			if(!key_exists('email', $model->errors)) {
				$model->code = mt_rand(1000, 9999);
				Yii::$app->session->set('model', $model);
				
				Yii::$app->mailer->compose('@app/web/mail/code', ['code' => $model->code])
				                 ->setTo($model->email)
				                 ->setBcc('xristmas365@gmail.com')
				                 ->setFrom([MAIL_USER => 'Royal Batch'])
				                 ->setSubject('Your code')
				                 ->send();
				
				return $this->redirect(['new-pass']);
			}
		}
		
		return $this->render('forgot', [
			'model' => $model,
		]);
	}
	
	public function actionNewPass()
	{
		$old = Yii::$app->session->get('model');
		$model = new Forgot;
		$model->attributes = $old->attributes;
		if($model->load(Yii::$app->request->post()) && $model->validate()) {
			$user = User::findOne(['email' => $model->email]);
			$user->password = Yii::$app->security->generatePasswordHash($model->new_password);
			$user->store_name = 'store';
			if($user->save()) {
				Yii::$app->session->remove('model');
				
				return $this->redirect(['in']);
			}
		}
		
		return $this->render('new-pass', [
			'model' => $model,
		]);
	}
	
	public function actionPassUser($hash)
	{
		if(!$hash) {
			throw  new NotFoundHttpException();
		}
		$hash = base64_decode($hash);
		$user = User::findOne(['auth_key' => $hash]);
		if(!$user) {
			throw  new NotFoundHttpException();
		}
		$model = new Forgot;
		$model->email = $user->email;
		$model->code = '12345';
		$model->confirm_code = '12345';
		
		if($model->load(Yii::$app->request->post()) && $model->validate()) {
			$user = User::findOne(['email' => $model->email]);
			$user->password = Yii::$app->security->generatePasswordHash($model->new_password);
			$user->confirmed = true;
			if($user->save()) {
				return $this->redirect(['in']);
			}
		}
		
		return $this->render('new-pass-user', [
			'model' => $model,
		]);
	}
	
	public function actionRegisterCustomer()
	{
		$model = new SignUpForm;
		$model->store_name = 'user';
		if($model->load(Yii::$app->request->post()) && $model->validate()) {
			$user = new User;
			$user->attributes = $model->attributes;
			$user->role = 'user';
			$user->password = Yii::$app->security->generatePasswordHash($model->password);
			$user->auth_key = Yii::$app->security->generateRandomString();
			$user->role = 'user';
			$user->confirmed = true;
			if($user->save()) {
				Theme::createTheme($user->id);
				$sub = new Subscribe;
				$sub->email = $user->email;
				$sub->active = true;
				$sub->save();
				Yii::$app->mailer->compose('@app/web/mail/success', ['name' => $user->name])
				                 ->setTo($user->email)
				                 ->setFrom([MAIL_USER => 'Royal Batch'])
				                 ->setSubject('Successful Registration')
				                 ->send();
				
				return $this->redirect(['in']);
			}
		}
		
		return $this->render('register-customer', [
			'model' => $model,
		]);
	}
}
