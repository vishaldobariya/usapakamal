<?php

namespace app\modules\subscribe\controllers;

use Yii;
use yii\helpers\FileHelper;
use yii\filters\VerbFilter;
use app\modules\subscribe\models\Subscribe;
use app\components\controllers\BackController;
use app\modules\subscribe\models\search\SubscribeSearch;

/**
 * SubscribeController implements the CRUD actions for Subscribe model.
 */
class SubscribeController extends BackController
{
	
	/**
	 * {@inheritdoc}
	 */
	public function behaviors()
	{
		return [
			'verbs' => [
				'class'   => VerbFilter::className(),
				'actions' => [
					'delete' => ['POST'],
				],
			],
		];
	}
	
	/**
	 * Lists all Subscribe models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new SubscribeSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
		return $this->render('index', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}
	
	public function actionExport()
	{
		ini_set('safe_mode', 'Off');
		ini_set('memory_limit', '-1');
		set_time_limit(0);
		
		$list[] = [
			'email',
			'active',
		
		];
		FileHelper::createDirectory(Yii::getAlias('@app/web/upload/csv/'), 0775, true);
		
		$subscribes = Subscribe::find()->all();
		
		foreach($subscribes as $subscribe) {
			$list[] = [
				$subscribe->email,
				$subscribe->active ? 'Yes' : 'No',
			];
		}
		
		$fp = fopen(Yii::getAlias('@app/web/upload/csv/list.csv'), 'w');
		
		foreach($list as $fields) {
			fputcsv($fp, $fields);
		}
		
		fclose($fp);
		//return Yii::getAlias('@app/web/upload/csv/list.csv');
		\Yii::$app->response->sendFile(Yii::getAlias('@app/web/upload/csv/list.csv'));
		unlink(Yii::getAlias('@app/web/upload/csv/list.csv'));
	}
	
	public function actionDelete($id)
	{
		Subscribe::deleteAll(['id' => $id]);
		
		return $this->redirect(['index']);
	}
}
