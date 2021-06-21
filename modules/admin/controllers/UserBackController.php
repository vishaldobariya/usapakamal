<?php
/**
 *
 * @author    Paul Stolyarov <teajeraker@gmail.com>
 * @copyright industrialax.com
 * @license   https://industrialax.com/crm-general-license
 */

namespace app\modules\admin\controllers;

use app\modules\shop\models\Contact;
use app\modules\shop\models\Product;
use app\components\controllers\BackController;
use Yii;

class UserBackController extends BackController
{
	
	public $layout = 'admin';
	
	public function actionSupport()
	{
		return $this->render('support');
	}
	
	public function actionSpecialOffers()
	{
		$offers = Product::find()->with(['images', 'category.images', 'brand.images', 'subCategory.images'])->where(['available' => true, 'special_offers' => true])->all();
		
		return $this->render('special-offers', [
			'offers' => $offers,
		]);
	}
	
	public function actionContact()
	{
		$model = new Contact;
		$user = Yii::$app->user->identity;
		$model->attributes = $user->attributes;
		if($model->load(Yii::$app->request->post()) && $model->save()) {
			Yii::$app->mailer->compose('@app/web/mail/form-contact', ['model' => $model])
			                 ->setTo('qaismj@yahoo.com')
			                 ->setBcc('brandonmaxwelltwo@gmail.com')
			                 ->setFrom([MAIL_USER => 'Royal Batch'])
			                 ->setSubject('You\'ve got a new message from Contact form')
			                 ->send();
			
			return $this->redirect(['success']);
		}
		
		return $this->render('contact', [
			'model' => $model,
		]);
	}
	
	public function actionSuccess()
	{
		return $this->render('thank');
	}
}
