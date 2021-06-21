<?php

namespace app\modules\shop\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use app\modules\shop\models\Product;
use app\modules\shop\models\Category;
use app\components\controllers\BackController;
use app\modules\shop\models\search\CategorySearch;

/**
 * CategoryController implements the CRUD actions for Category model.
 */
class CategoryController extends BackController
{
	
	/**
	 * {@inheritdoc}
	 */
	public function behaviors()
	{
		return array_merge(parent::behaviors(), [
			'verbs' => [
				'class'   => VerbFilter::className(),
				'actions' => [
					'delete' => ['POST'],
				],
			],
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					[
						'allow' => Yii::$app->user->identity->role == 'admin',
						'roles' => ['@'],
					],
				],
			],
		]);
	}
	
	/**
	 * Lists all Category models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new CategorySearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$catParents = ArrayHelper::map(Category::find()->where(['is', 'parent_id', null])->asArray()->all(), 'id', 'name');
		
		return $this->render('index', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
			'catParents'   => $catParents,
		]);
	}
	
	/**
	 * Displays a single Category model.
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
	 * Creates a new Category model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new Category();
		$catParents = ArrayHelper::map(Category::find()->where(['is', 'parent_id', null])->asArray()->all(), 'id', 'name');
		$data = [];
		$products = [];
		if($model->load(Yii::$app->request->post()) && $model->save()) {
			$post = Yii::$app->request->post();
			$prods = $post['Category']['product_ids'];
			if(isset($post['all_products']) && $post['all_products'] == 1) {
				Product::updateAll(['category_id' => $model->parent_id != null ? $model->parent_id : $model->id, 'sub_category_id' => $model->parent_id != null ? $model->id : null], ['>', 'id', 0]);
			}
			if($prods != '') {
				Product::updateAll(['category_id' => $model->parent_id != null ? $model->parent_id : $model->id, 'sub_category_id' => $model->parent_id != null ? $model->id : null], ['id' => $prods]);
			}
			return $this->redirect(['index']);
		}
		
		return $this->render('create', [
			'model'      => $model,
			'catParents' => $catParents,
			'products'   => $products,
			'data'       => $data,
			'count_prod' => Product::find()->count(),
			'count_assign' => 0
		]);
	}
	
	/**
	 * Updates an existing Category model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 *
	 * @param integer $id
	 *
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);
		$query = Product::find()->select(['id', 'name']);
		if($model->parent_id) {
			$query->andWhere(['category_id' => $model->parent_id, 'sub_category_id' => $model->id]);
		} else {
			$query->andWhere(['category_id' => $model->id]);
		}
		$products = $query->all();
		$count_prod = Product::find()->count();
		$count_assign = count($products);
		$data = ArrayHelper::map($products, 'id', 'name');
		$catParents = ArrayHelper::map(Category::find()->where(['is', 'parent_id', null])->asArray()->all(), 'id', 'name');
		$products = ArrayHelper::getColumn($products, 'id') ?? [];
		if($model->load(Yii::$app->request->post()) && $model->save()) {
			$post = Yii::$app->request->post();
			$prods = $post['Category']['product_ids'];
			if(isset($post['all_products']) && $post['all_products'] == 1) {
				Product::updateAll(['category_id' => $model->parent_id != null ? $model->parent_id : $model->id, 'sub_category_id' => $model->parent_id != null ? $model->id : null], ['>', 'id', 0]);
			}
			if($prods != '') {
				Product::updateAll(['category_id' => $model->parent_id != null ? $model->parent_id : $model->id, 'sub_category_id' => $model->parent_id != null ? $model->id : null], ['id' => $prods]);
			}
			
			return $this->redirect(['index']);
		}
		
		return $this->render('update', [
			'model'      => $model,
			'catParents' => $catParents,
			'products'   => $products,
			'data'       => $data,
			'count_prod' => $count_prod,
			'count_assign' => $count_assign
		]);
	}
	
	/**
	 * Deletes an existing Category model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 *
	 * @param integer $id
	 *
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionDelete($id)
	{
		$this->findModel($id)->delete();
		
		return $this->redirect(['index']);
	}
	
	/**
	 * Finds the Category model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 *
	 * @param integer $id
	 *
	 * @return Category the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if(($model = Category::findOne($id)) !== null) {
			return $model;
		}
		
		throw new NotFoundHttpException('The requested page does not exist.');
	}
	
	public function actionConnect()
	{
		$post = Yii::$app->request->post()['checked'];
		$id_f = (int)$post[0];
		$id_s = (int)$post[1];
		$min = $id_f < $id_s ? $id_f : $id_s;
		$max = $min == $id_f ? $id_s : $id_f;
		
		Product::updateAll(['category_id' => $min], ['category_id' => $max]);
		
		return Category::deleteAll(['id' => $max]);
		
	}
	
	public function actionFindProds($q = null)
	{
		$query = Product::find()->limit(100);
		
		$qs = explode(' ', $q);
		
		foreach($qs as $key) {
			$query->andWhere(['ilike', 'name', $key]);
		}
		$prods = $query->all();
		$result['results'] = [];
		foreach($prods as $prod) {
			$result['results'][] = ['id' => $prod->id, 'text' => $prod->name];
		}
		
		return $this->asJson($result);
		
	}
}
