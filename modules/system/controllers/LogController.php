<?php

namespace app\modules\system\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use app\modules\system\models\Log;
use app\components\controllers\BackController;
use app\modules\system\models\search\LogSearch;

/**
 * LogController implements the CRUD actions for Log model.
 */
class LogController extends BackController
{
	
	
	/**
	 * Lists all Log models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new LogSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
		return $this->render('index', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}
	
	/**
	 * Displays a single Log model.
	 *
	 * @param integer $id
	 *
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionView($id)
	{
		return $this->render('view', [
			'model' => $this->findModel($id),
		]);
	}
	
	/**
	 * Finds the Log model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 *
	 * @param integer $id
	 *
	 * @return Log the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if(($model = Log::findOne($id)) !== null) {
			return $model;
		}
		
		throw new NotFoundHttpException('The requested page does not exist.');
	}
}
