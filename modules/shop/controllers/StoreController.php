<?php
/**
 *
 * @author    Paul Stolyarov <teajeraker@gmail.com>
 * @copyright industrialax.com
 * @license   https://industrialax.com/crm-general-license
 */

namespace app\modules\shop\controllers;

use app\modules\shop\models\Order;
use Yii;
use Exception;
use yii\helpers\Url;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use app\modules\shop\models\Price;
use app\modules\shop\models\Product;
use app\modules\provider\models\Store;
use app\modules\shop\models\StoreProduct;
use app\modules\shop\models\AddProductForm;
use app\modules\shop\models\StoreCsvUpload;
use app\modules\provider\models\PriceProvider;
use app\components\controllers\BackController;
use app\modules\shop\models\search\StoreProductSearch;

class StoreController extends BackController
{
	
	/**
	 * {@inheritdoc}
	 */
	public function behaviors()
	{
		return array_merge(parent::behaviors(), [
			'verbs'  => [
				'class'   => VerbFilter::className(),
				'actions' => [
					'delete' => ['POST'],
				],
			],
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					[
						'allow' => Yii::$app->user->identity->role == 'distributor',
						'roles' => ['@'],
					],
				],
			],
		]);
	}
	
	public function actionIndex()
	{
		$stores = Store::find()
		               ->joinWith([
			               'storeProducts' => function($query)
			               {
				               $query->onCondition(['store_product.connected' => true]);
			               },
		               ])
		               ->asArray()
		               ->all();
		
		$names = ArrayHelper::map($stores, 'name', 'name');
		$csv = new StoreCsvUpload;
		$products = [];
		$catalogs = Product::find()
		                   ->select(['id', 'sku', 'name', 'price', 'tags', 'sale_price'])
		                   ->asArray()
		                   ->all();
		foreach($stores as $store) {
			foreach($store['storeProducts'] as $prod) {
				$products[$prod['product_id']][$store['name']] = $prod;
			}
		}
		foreach($catalogs as $prod) {
			$products[$prod['id']]['catalog'] = $prod;
		}
		
		return $this->render('index', [
			'csv'      => $csv,
			'names'    => $names,
			'stores'   => $stores,
			'products' => $products,
		]);
	}
	
	public function actionSample()
	{
		\Yii::$app->response->sendFile(Yii::getAlias('@app/modules/shop/files/sample.csv'));
	}
	
	public function actionUpload()
	{
		$model = new StoreCsvUpload();
		$model->csv = UploadedFile::getInstance($model, 'csv');
		if($model->csv->extension !== 'csv') {
			Yii::$app->session->setFlash('upload', ['title' => 'Error CSV extension', 'message' => 'Please, upload format CSV only']);
			
		} else {
			$model->csv->saveAs(Yii::getAlias('@app/web/upload/' . $model->csv->baseName . '.' . $model->csv->extension));
			
			if(($handle = fopen(Yii::getAlias('@app/web/upload/' . $model->csv->baseName . '.' . $model->csv->extension), "r")) !== false) {
				$flag = true;
				//find or create store
				$name = trim(Yii::$app->request->post('StoreCsvUpload')['store_name']);
				$store = Store::findOne(['name' => $name]);
				if(!$store) {
					$store = new Store;
					$store->name = $name;
					$store->user_id = Yii::$app->user->id;
					$store->save();
				}
				
				while(($data = fgetcsv($handle, 1000000, ',')) !== false) {
					if($flag) {
						$flag = false;
						continue;
					}
					
					$db = \Yii::$app->db;
					$transaction = $db->beginTransaction();
					
					try {
						$store_product = StoreProduct::find()->where(['sku' => trim($data[0]), 'store_id' => $store->id])->one();
						if(!$store_product) {
							$store_product = new StoreProduct;
							$store_product->sku = trim($data[0]);
							$store_product->product_name = trim($data[1]);
							$store_product->store_id = $store->id;
							$store_product->price = (float)trim($data[2]);
							$store_product->save();
						} else {
							$store_product->old_price = $store_product->price;
							$store_product->price = (float)trim($data[2]);
							$store_product->save();
						}
						$transaction->commit();
					} catch(Exception $e) {
						$transaction->rollback();
						unlink(Yii::getAlias('@app/web/upload/' . $model->csv->baseName . '.' . $model->csv->extension));
						Yii::$app->session->setFlash('upload', [
							'title'   => 'Error loading',
							'message' => 'Download the correct csv file. Please, see <a href="' . Url::toRoute
								(['/shop/store/sample']) . '">example</a>',
						]);
						
						return $this->redirect(Yii::$app->request->referrer);
						
					}
					
				}
			}
			unlink(Yii::getAlias('@app/web/upload/' . $model->csv->baseName . '.' . $model->csv->extension));
			Yii::$app->session->setFlash('upload', ['title' => 'Success', 'message' => 'File was uploaded']);
			
			Yii::$app->mailer->compose('@app/web/mail/update-products', ['model' => Yii::$app->user->identity])
			                 ->setTo('qaismj@yahoo.com')
			                 ->setBcc('xristmas365@gmail.com')
			                 ->setFrom([MAIL_USER => 'Royal Batch'])
			                 ->setSubject('Provider ' . Yii::$app->user->identity->email . ' updated its products')
			                 ->send();
			
		}
		
		return $this->redirect(Yii::$app->request->referrer);
	}
	
	public function actionUpdatePrice()
	{
		$product = Product::findOne(['id' => Yii::$app->request->post('id')]);
		$product->price = (float)Yii::$app->request->post('price');
		$product->price_min = (float)Yii::$app->request->post('price');
		$product->store_id = Yii::$app->request->post('store');
		Price::addPrice($product->price, $product->id);
		
		return $product->save();
	}
	
	
	public function actionMyProducts()
	{
		$searchModel = new StoreProductSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
		$csv = new StoreCsvUpload;
		$product = new StoreProduct;
		$model = new AddProductForm;
		$ids = ArrayHelper::getColumn(StoreProduct::find()->select(['product_id', 'store_id'])->where(['store_id' => Yii::$app->user->identity->store->id])->asArray()->all(), 'product_id');
		
		$products = ArrayHelper::map(Product::find()->select(['id', 'name', 'vol', 'abv'])->where(['not in', 'id', $ids])->asArray()->all(), 'id', function($model)
		{
			return $model['name'] . ' ' . $model['vol'] . 'ml';
		});
		
		if($model->load(Yii::$app->request->post()) && $model->validate()) {
			Yii::$app->mailer->compose('@app/web/mail/add-product', ['model' => $model])
			                 ->setTo('qaismj@yahoo.com')
			                 ->setBcc('brandonmaxwelltwo@gmail.com')
			                 ->setFrom([MAIL_USER => 'Royal Batch'])
			                 ->setSubject('Please add product')
			                 ->send();
			Yii::$app->session->setFlash('upload', [
				'title'   => 'Success',
				'message' => 'Thank you. We have received your request. As soon as we add the product we will inform you',
			]);
			
			return $this->redirect(['my-products']);
		}
		
		return $this->render('my-products', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
			'csv'          => $csv,
			'product'      => $product,
			'model'        => $model,
			'products'     => $products,
		]);
	}
	
	public function actionUploadProvider($new = 0)
	{
		$model = new StoreCsvUpload();
		$model->csv = UploadedFile::getInstance($model, 'csv');
		
		if($model->csv->extension !== 'csv') {
			Yii::$app->session->setFlash('upload', ['title' => 'Error CSV extension', 'message' => 'Please, upload format CSV only']);
			
		} else {
			$model->csv->saveAs(Yii::getAlias('@app/web/upload/' . $model->csv->baseName . '.' . $model->csv->extension));
			
			if(($handle = fopen(Yii::getAlias('@app/web/upload/' . $model->csv->baseName . '.' . $model->csv->extension), "r")) !== false) {
				$flag = true;
				//find or create store
				$name = trim(Yii::$app->request->post('StoreCsvUpload')['store_name']);
				$store = Store::findOne(['name' => $name]);
				if(!$store) {
					$store = new Store;
					$store->name = $name;
					$store->user_id = Yii::$app->user->id;
					$store->save();
				}
				
				while(($data = fgetcsv($handle, 1000000, ',')) !== false) {
					if($flag) {
						$flag = false;
						continue;
					}
					
					$db = \Yii::$app->db;
					$transaction = $db->beginTransaction();
					
					try {
						$store_product = StoreProduct::find()->where(['product_name' => trim($data[1]), 'store_id' => $store->id])->one();
						if(!$store_product) {
							$store_product = new StoreProduct;
							$sku = trim($data[2]);
							if($sku == '') {
								$sku = $store->name . '_' . ($data[0] + 1000);
							}
							$store_product->sku = $sku;
							$store_product->product_name = trim($data[1]);
							$store_product->cap = trim($data[3]);
							$store_product->vol = trim($data[4]);
							$store_product->abv = trim($data[5]);
							$store_product->store_id = $store->id;
							$store_product->product_id = trim($data[0]);
							$store_product->price = (float)trim($data[6]);
							$store_product->connected = true;
							$store_product->save();
						} else {
							$store_product->cap = trim($data[3]);
							$store_product->vol = trim($data[4]);
							$store_product->abv = trim($data[5]);
							$store_product->old_price = $store_product->price;
							$store_product->price = (float)trim($data[6]);
							$store_product->save();
						}
						if($data[3] == '' || $data[6] = '' || !Product::find()->where(['id' => $data[0]])->exists()) {
							$store_product->connected = false;
							$store_product->save();
						}
						
						$transaction->commit();
					} catch(Exception $e) {
						$transaction->rollback();
						unlink(Yii::getAlias('@app/web/upload/' . $model->csv->baseName . '.' . $model->csv->extension));
						Yii::$app->session->setFlash('upload', [
							'title'   => 'Error loading',
							'message' => 'Download the correct csv file. Please, see <a href="' . Url::toRoute
								(['/shop/store/sample']) . '">example</a>',
						]);
						
						return $this->redirect(Yii::$app->request->referrer);
						
					}
					
				}
			}
			unlink(Yii::getAlias('@app/web/upload/' . $model->csv->baseName . '.' . $model->csv->extension));
			Yii::$app->session->setFlash('upload', ['title' => 'Success', 'message' => 'File was uploaded']);
			
			Yii::$app->mailer->compose('@app/web/mail/update-products', ['model' => Yii::$app->user->identity])
			                 ->setTo('qaismj@yahoo.com')
			                 ->setBcc('brandonmaxwelltwo@gmail.com')
			                 ->setBcc('xristmas365@gmail.com')
			                 ->setFrom([MAIL_USER => 'Royal Batch'])
			                 ->setSubject('Provider ' . Yii::$app->user->identity->email . ' updated its products')
			                 ->send();
			
		}
		
		return $this->redirect(Yii::$app->request->referrer);
	}
	
	public function actionGetStoreProduct()
	{
		return $this->asJson(StoreProduct::findOne(['id' => Yii::$app->request->post('id')]));
	}
	
	public function actionUpdateProduct()
	{
		$post = Yii::$app->request->post('StoreProduct');
		$product = StoreProduct::findOne(['id' => $post['id']]);
		$old_price = $product->price;
		unset($post['id']);
		$product->attributes = $post;
		if($product->sku == '') {
			$product->sku = Yii::$app->user->identity->store->name . '_' . (10000 + $product->product_id);
		}
		
		if(in_array($product->price, ['0', '']) || !Product::find()->where(['id' => $product->product_id])->exists()) {
			$product->connected = false;
			
		} else {
			$product->connected = true;
			
			$min_prod = StoreProduct::find()
			                        ->where(['product_id' => $product->product_id])
			                        ->andWhere(['!=', 'store_id', Yii::$app->user->identity->store->id])
			                        ->andWhere(['connected' => true])
			                        ->orderBy(['price' => SORT_ASC])
			                        ->one();
			
			Order::checkProviderPrice($product->product_id, $product->price);
			
			
			if(!$min_prod || $min_prod->price > $product->price) {
				$main_prod = Product::findOne(['id' => $product->product_id]);
				$main_prod->provider_price = $product->price;
				$main_prod->save();
			}
		}
		
		if($old_price != $product->price) {
			$history = new PriceProvider;
			$history->price = $product->price;
			$history->product_id = $product->id;
			$history->save();
		}
		
		$product->save();
		
		return $this->redirect(Yii::$app->request->referrer);
	}
	
	public function actionSaveSessionItems()
	{
		$items = Yii::$app->session->get('items') ?? [];
		
		if(Yii::$app->request->post('type') == 'add') {
			$items = array_merge($items, Yii::$app->request->post('values'));
			Yii::$app->session->set('items', $items);
			
			return count($items);
		} else {
			$items = array_diff($items, Yii::$app->request->post('values'));
			if(empty($items)) {
				Yii::$app->session->remove('items');
				
				return 0;
			}
			Yii::$app->session->set('items', $items);
			
			return count($items);
		}
	}
	
	public function actionUpdateCatalog()
	{
		$ids = ArrayHelper::getColumn(StoreProduct::find()->select('product_id')->where(['store_id' => Yii::$app->user->identity->store->id])->asArray()->all(), 'product_id');
		$products = Product::find()->where(['not in', 'id', $ids])->asArray()->all();
		
		$store_product = new StoreProduct;
		$attributes = $store_product->attributes();
		unset($attributes[array_search('id', $attributes)]);
		$rows = [];
		foreach($products as $product) {
			$rows[] = [
				'product_id'   => $product['id'],
				'product_name' => $product['name'],
				'store_id'     => Yii::$app->user->identity->store->id,
				'connected'    => false,
				'vol'          => $product['vol'],
				'abv'          => $product['abv'],
				'sku'          => Yii::$app->user->identity->store->name . '_' . ($product['id'] + 1000),
			];
		}
		Yii::$app->db->createCommand()->batchInsert(StoreProduct::tableName(), $attributes, $rows)->execute();
		
		return $this->redirect(Yii::$app->request->referrer);
	}
	
	public function actionFindProducts($q = null)
	{
		$query = StoreProduct::find()->where(['store_id' => Yii::$app->user->identity->store->id]);
		
		$qs = explode(' ', $q);
		$prod_query = clone $query;
		foreach($qs as $key) {
			$prod_query->andWhere(['ilike', 'product_name', $key]);
		}
		$sku_query = clone $query;
		foreach($qs as $key) {
			$sku_query->andWhere(['ilike', 'sku', $key]);
		}
		$products = $prod_query->all();
		$skus = $sku_query->all();
		$result['results'] = [];
		foreach($products as $inventory) {
			$result['results'][] = ['id' => $inventory->product_name, 'text' => $inventory->product_name];
		}
		foreach($skus as $inventory) {
			$result['results'][] = ['id' => $inventory->sku, 'text' => $inventory->sku];
		}
		
		return $this->asJson($result);
	}
	
	public function actionGetHistory()
	{
		$data = [];
		$prices = PriceProvider::findAll(['product_id' => Yii::$app->request->post('val')]);
		foreach($prices as $price) {
			$elem ['updated_at'] = date('F j, Y', $price->created_at);
			$elem['price'] = Yii::$app->formatter->asCurrency($price->price);
			$data[] = $elem;
		}
		
		return $this->asJson($data);
	}
	
	public function actionUpdateMyProducts()
	{
		$post = Yii::$app->request->post();
		
		$ids = ArrayHelper::getColumn(StoreProduct::find()->select('product_id')->where(['store_id' => Yii::$app->user->identity->store->id])->asArray()->all(), 'product_id');
		
		$post_id = $post['product_id'];
		$post_id = array_map(function($item) use ($ids)
		{
			if(!in_array((int)$item, $ids)) {
				return (int)$item;
			}
			
		}, $post_id);
		$products = Product::find()->where(['id' => $post_id])->asArray()->all();
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
		
		return $this->redirect(Yii::$app->request->referrer);
		
	}
	
	public function findModel($id)
	{
		if(($model = StoreProduct::findOne($id)) !== null) {
			return $model;
		}
		
		throw new NotFoundHttpException('The requested page does not exist.');
	}
	
	public function actionChangeSku($id)
	{
		$product = $this->findModel($id);
		$product->sku = Yii::$app->request->post('sku');
		
		return $product->save();
	}
}
