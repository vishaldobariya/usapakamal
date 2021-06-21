<?php
/**
 *
 * @author    Paul Stolyarov <teajeraker@gmail.com>
 * @copyright industrialax.com
 * @license   https://industrialax.com/crm-general-license
 */

namespace app\modules\shipstation\controllers;

use app\modules\provider\models\Store;
use app\components\controllers\BackController;
use app\modules\shipstation\helpers\ShipStationApi;
use app\modules\shipstation\models\ShipStation;
use yii\helpers\Json;

class ShipstationController extends BackController
{
	
	public function actionIndex()
	{
		$store = Store::findOne(['id' => \Yii::$app->user->identity->store->id]);
		$model = new ShipStation;
		
		if(\Yii::$app->request->isPost) {
			$post = \Yii::$app->request->post();
			if($post['keys'] == '1') {
				$store->load($post);
				$store->connected = true;
				$store->save();
				
				return $this->redirect(['index']);
			}
			
			if($post['keys'] == '0' && $model->load($post) && $model->validate()) {
			ShipStationApi::createAccount(Json::encode($model->attributes));
			}
		}
		
		return $this->render('index', [
			'store' => $store,
			'model' => $model,
		]);
	}
}
