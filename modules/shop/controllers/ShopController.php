<?php
/**
 *
 * @author    Paul Stolyarov <teajeraker@gmail.com>
 * @copyright industrialax.com
 * @license   https://industrialax.com/crm-general-license
 */

namespace app\modules\shop\controllers;

use Yii;
use app\modules\shop\models\Brand;
use yii\web\NotFoundHttpException;
use app\modules\shop\models\Product;
use app\modules\shop\models\Category;
use app\components\controllers\FrontController;
use app\modules\shop\models\search\ProductSearch;

class ShopController extends FrontController
{
	
	public function actionProduct($slug = null)
	{
		$product = Product::find()->where(['slug' => $slug])->with(['images', 'brand', 'category.images'])->one();
		if(!$product) {
			throw new NotFoundHttpException();
		}
		/**
		 * SEO tags
		 */
		$view = Yii::$app->view;
		$view->title = $product->seo_title != '' ? $product->seo_title : $product->name . ': Royal Batch';
		$view->registerMetaTag(['name' => 'description', 'content' => trim(strip_tags($product->seo_description != '' ? $product->seo_description : $product->description))]);
		$view->registerMetaTag(['name' => 'keywords', 'content' => $product->seo_keywords != '' ? $product->seo_keywords : Yii::$app->settings->main_keywords]);
		
		$sames = Product::find()
		                ->where(['category_id' => $product->category_id])
		                ->andWhere(['!=', 'id', $product->id])
		                ->limit(4)
		                ->with(['images', 'brand', 'category.images', 'subCategory.images'])
		                ->orderBy('random()')
		                ->all();
		
		return $this->render('product', [
			'product' => $product,
			'sames'   => $sames,
		]);
	}
	
	public function actionCollections()
	{
		/**
		 * SEO tags
		 */
		$view = Yii::$app->view;
		$view->title = Yii::$app->settings->main_title;
		$view->registerMetaTag(['name' => 'description', 'content' => strip_tags(Yii::$app->settings->main_description)]);
		$view->registerMetaTag(['name' => 'keywords', 'content' => Yii::$app->settings->main_keywords]);
		
		$searchModel = new ProductSearch();
		$dataProvider = $searchModel->searchFront(Yii::$app->request->queryParams);
		
		$categories = Category::getFilter();
		$brands = Brand::getFilter();
		$subCats = Category::getSubFilter();
		
		return $this->render('collections', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
			'brands'       => $brands,
			'categories'   => $categories,
			'subCats'      => $subCats,
		]);
	}
	
}
