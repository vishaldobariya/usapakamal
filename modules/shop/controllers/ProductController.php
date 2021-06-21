<?php

namespace app\modules\shop\controllers;

use Yii;
use Exception;
use yii\helpers\Url;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use yii\helpers\FileHelper;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use app\modules\shop\models\Price;
use app\modules\shop\models\Brand;
use yii\web\NotFoundHttpException;
use app\modules\shop\models\Catalog;
use app\modules\shop\models\Product;
use app\modules\shop\models\Category;
use app\modules\shop\models\CsvUpload;
use app\modules\shop\models\StoreProduct;
use app\modules\storage\models\StorageItem;
use app\components\controllers\BackController;
use app\modules\shop\models\search\ProductSearch;
use yii\helpers\VarDumper;
/**
 * ProductController implements the CRUD actions for Product model.
 */
class ProductController extends BackController
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
						'allow'   => Yii::$app->user->identity->role == 'admin',
						'actions' => [
							'index',
							'without-images',
							'view',
							'create',
							'update',
							'delete',
							'gide-image',
							'export',
							'import',
							'featured',
							'special',
							'find-products',
							'get-history',
							'add-checked',
							'alert',
							'change-price',
							'subcat',
							'delete-csv',
						],
						'roles'   => ['@'],
					],
					[
						'allow'   => Yii::$app->user->identity->role == 'distributor',
						'actions' => ['export-main-catalog', 'get-history'],
						'roles'   => ['@'],
					],
				],
			],
		]);
	}
	
	/**
	 * Lists all Product models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new ProductSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$data = Product::find()->select(['sku', 'name'])->with('images')->orderBy(['id' => SORT_ASC])->all();
		$sku = ArrayHelper::map($data, 'sku', 'sku');
		$names = ArrayHelper::map($data, 'name', 'name');
		$csv = new CsvUpload;
		//$dataProvider->sort = false;
		$categories = ArrayHelper::map(Category::find()->select(['id', 'name'])->where(['parent_id' => null])->asArray()->all(), 'id', 'name');
		$categories_sub = ArrayHelper::map(Category::find()->select(['id', 'name'])->where(['is not', 'parent_id', null])->asArray()->all(), 'id', 'name');
		$brands = ArrayHelper::map(Brand::find()->select(['id', 'name'])->asArray()->all(), 'id', 'name');
		if(!Yii::$app->session->has('checked')) {
			$checked = [
				'name',
				'price',
				'catalogs',
				'category_id',
				'brand_id',
				'sku',
				'available',
				'visible',
				'featured_brand',
				'special_offers',
			];
			Yii::$app->session->set('checked', $checked);
		}
		
		return $this->render('index', [
			'searchModel'    => $searchModel,
			'dataProvider'   => $dataProvider,
			'sku'            => $sku,
			'names'          => $names,
			'csv'            => $csv,
			'categories'     => $categories,
			'brands'         => $brands,
			'model'          => new Product(),
			'categories_sub' => $categories_sub,
		]);
	}
	
	/**
	 * Lists all Product models.
	 * @return mixed
	 */
	public function actionWithoutImages()
	{
		$searchModel = new ProductSearch();
		$dataProvider = $searchModel->searchWithoutImage(Yii::$app->request->queryParams);
		$data = Product::find()->select(['sku', 'name'])->with('images')->orderBy(['id' => SORT_ASC])->all();
		$catalogs = ArrayHelper::map(Catalog::find()->select(['id', 'name'])->asArray()->all(), 'id', 'name');
		$sku = ArrayHelper::map($data, 'sku', 'sku');
		$names = ArrayHelper::map($data, 'name', 'name');
		$csv = new CsvUpload;
		$dataProvider->sort = false;
		$categories = ArrayHelper::map(Category::find()->select(['id', 'name'])->asArray()->all(), 'id', 'name');
		$brands = ArrayHelper::map(Brand::find()->select(['id', 'name'])->asArray()->all(), 'id', 'name');
		if(!Yii::$app->session->has('checked')) {
			$checked = [
				'name',
				'price',
				'catalogs',
				'category_id',
				'brand_id',
				'sku',
				'available',
				'visible',
				'featured_brand',
				'special_offers',
			];
			Yii::$app->session->set('checked', $checked);
		}
		
		return $this->render('index-without', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
			'sku'          => $sku,
			'names'        => $names,
			'csv'          => $csv,
			'catalogs'     => $catalogs,
			'categories'   => $categories,
			'brands'       => $brands,
			'model'        => new Product(),
		]);
	}
	
	/**
	 * Displays a single Product model.
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
	 * Creates a new Product model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new Product();
		$cats = ArrayHelper::map(Category::find()->select(['id', 'name'])->orderBy(['name' => SORT_ASC])->asArray()->all(), 'id', 'name');
		$sub_cats = [];
		$brands = ArrayHelper::map(Brand::find()->select(['id', 'name'])->orderBy(['name' => SORT_ASC])->asArray()->all(), 'id', 'name');
		if(Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());
			$model->tags = $model->tags != '' ? implode(',', $model->tags) : '';
			$model->save();
			Price::addPrice($model->price, $model->id);
			
			return $this->redirect(['index']);
		}
		
		return $this->render('create', [
			'model'    => $model,
			'cats'     => $cats,
			'brands'   => $brands,
			'sub_cats' => $sub_cats,
		]);
	}
	
	/**
	 * Updates an existing Product model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 *
	 * @param integer $id
	 *
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionUpdate($id)
	{
		if(!Yii::$app->session->has('back_url')) {
			Yii::$app->session->set('back_url', Yii::$app->request->referrer);
		}
		$model = $this->findModel($id);
		$old_price = $model->price;
		$cats = ArrayHelper::map(Category::find()->select(['id', 'name'])->orderBy(['name' => SORT_ASC])->asArray()->all(), 'id', 'name');
		$sub_cats = ArrayHelper::map(Category::find()->select(['id', 'name'])->where(['parent_id' => $model->category_id])->orderBy(['name' => SORT_ASC])->asArray()->all(), 'id', 'name');
		
		$brands = ArrayHelper::map(Brand::find()->select(['id', 'name'])->orderBy(['name' => SORT_ASC])->asArray()->all(), 'id', 'name');
		if(Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());
			$model->tags = $model->tags != '' ? implode(',', $model->tags) : '';
			$model->save();
			if($old_price != $model->price) {
				Price::addPrice($model->price, $model->id);
			}
			if(Yii::$app->user->identity->role == 'distributor') {
				$this->sendMessage($model);
			}
			$url = Yii::$app->session->get('back_url');
			Yii::$app->session->remove('back_url');
			
			return $this->redirect($url);
		}
		
		return $this->render('update', [
			'model'    => $model,
			'cats'     => $cats,
			'brands'   => $brands,
			'sub_cats' => $sub_cats,
		]);
	}
	
	/**
	 * Deletes an existing Product model.
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
	 * Finds the Product model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 *
	 * @param integer $id
	 *
	 * @return Product the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if(($model = Product::findOne($id)) !== null) {
			return $model;
		}
		
		throw new NotFoundHttpException('The requested page does not exist.');
	}
	
	public function actionHideImage()
	{
		if(Yii::$app->session->get('hide_image')) {
			Yii::$app->session->remove('hide_image');
		} else {
			Yii::$app->session->set('hide_image', true);
		}
		
		return Yii::$app->session->has('hide_image');
		
	}
	
	public function sendMessage($model)
	{
		Yii::$app->mailer->compose('@app/web/mail/contact', ['model' => $model])
		                 ->setTo('qaismj@yahoo.com')
		                 ->setBcc('brandonmaxwelltwo@gmail.com')
		                 ->setBcc('xristmas365@gmail.com')
		                 ->setFrom([MAIL_USER => 'Royal Batch'])
		                 ->setSubject('The distributor price was updated')
		                 ->send();
	}
	
	public function actionExport()
	{
		ini_set('safe_mode', 'Off');
		ini_set('memory_limit', '-1');
		set_time_limit(0);
		$count = Yii::$app->request->post('count');
		$list[] = [
			'id',
			'brand',
			'name',
			'category',
			'sub category',
			'slug',
			'description',
			'website_price',
			'provider_price',
			'sale_price',
			'cap',
			'sku',
			'vol',
			'abv',
			'available',
			'visible',
			'images',
			'age',
			'sale',
			'sold out',
			'new',
			'Available with Engraving',
			'Limited Edition',
			'Special Promotion',
			'year',
			'country',
			'region',
			'special offers',
			'featured brands',
			'seo_title',
			'seo_keywords',
			'seo_description',
		];
		FileHelper::createDirectory(Yii::getAlias('@app/web/upload/csv/'), 0775, true);
		
		$query = Product::find()->with(['category', 'brand', 'images','storeProducts','subCategory']);
		if($count != 'all') {
			$query->limit($count);
		}
		$products = $query->all();
		
		foreach($products as $product) {
			/**
			 * @var $product Product
			 */
			
			$list[] = [
				$product->id,
				$product->brand != null ? $product->brand->name : '',
				$product->name,
				$product->subCategory != null ? $product->subCategory->name : '',
				$product->category != null ? $product->category->name : '',
				$product->slug,
				$product->description,
				$product->price,
				$product->storeProducts[0]->price ?? 'no provider',
				$product->sale_price,
				$product->cap,
				$product->sku,
				$product->vol,
				$product->abv,
				$product->available == true ? 'available' : 'not available',
				$product->visible == true ? 'visible' : 'hidden',
				Url::base(true) . $product->thumb,
				$product->age,
				$product->isSale() ? 'Yes' : 'No',
				$product->isSold() ? 'Yes' : 'No',
				$product->isNew() ? 'Yes' : 'No',
				$product->isAvailableWithEngraving() ? 'Yes' : 'No',
				$product->isLimitedEdition() ? 'Yes' : 'No',
				$product->isSpecialPromotion() ? 'Yes' : 'No',
				$product->year,
				$product->country,
				$product->region,
				$product->special_offers ? 'Yes' : 'No',
				$product->featured_brand ? 'Yes' : 'No',
				$product->seo_title,
				$product->seo_keywords,
				$product->seo_description,
			
			];
		}
		
		$fp = fopen(Yii::getAlias('@app/web/upload/csv/list.csv'), 'w');
		
		foreach($list as $fields) {
			fputcsv($fp, $fields);
		}
		
		fclose($fp);
		//return Yii::getAlias('@app/web/upload/csv/list.csv');
		//\Yii::$app->response->sendFile(Yii::getAlias('@app/web/upload/csv/list.csv'));
		//unlink(Yii::getAlias('@app/web/upload/csv/list.csv'));
		return '/upload/csv/list.csv';
	}
	
	public function actionDeleteCsv()
	{
		return unlink(Yii::getAlias('@app/web/upload/csv/list.csv'));
		
	}
	
	public function actionImport()
	{
		ini_set('safe_mode', 'Off');
		ini_set('memory_limit', '-1');
		set_time_limit(300);
		
		$model = new CsvUpload;
		$model->csv = UploadedFile::getInstance($model, 'csv');
		if($model->csv->extension !== 'csv') {
			Yii::$app->session->setFlash('upload', ['title' => 'Error', 'message']);
			
		}
		$model->csv->saveAs(Yii::getAlias('@app/web/upload/' . $model->csv->baseName . '.' . $model->csv->extension));
		
		if(($handle = fopen(Yii::getAlias('@app/web/upload/' . $model->csv->baseName . '.' . $model->csv->extension), "r")) !== false) {
			$flag = true;
			while(($data = fgetcsv($handle, 1000000, ',')) !== false) {
				if($flag) {
					$flag = false;
					continue;
				}
				
				try {
					if(trim($data[2]) != ''){

						if(trim($data[0]) == '') {
							$product = new Product;
						} else {
							$product = Product::findOne(['id' => trim($data['0'])]);
							if(empty($product))
							{
								$product = new Product;
							}
						}
						
						$brand = Brand::findOne(['name' => trim($data[1])]);
						if(!$brand) {
							$brand = new Brand;
							$brand->name = trim($data[1]);
							$brand->save();
						}
						
						$product->name = trim($data[2]);
						
						$cat = Category::findOne(['name' => trim($data[3])]);
						if(!empty($data[3]))
						{
							if(empty($cat)) {
								$cat = new Category;
								$cat->name = trim($data[3]);
								$cat->slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $cat->name)));
								$cat->status = 1;
								$cat->save();
							}
						}
						$product->brand_id = $brand->id;
						$product->category_id = $cat->id;  
						$sub_cat = Category::find()->where(['name' => trim($data[4])])->andWhere(['is not', 'parent_id', null])->one();

						if(empty($sub_cat)) {
							$sub_cat = new Category;
							$sub_cat->name = trim($data[4]);
							$sub_cat->slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $sub_cat->name)));
							$sub_cat->status = 1;
							$sub_cat->parent_id = $cat->id;
							$sub_cat->save();
						} 
						
						$product->sub_category_id = $sub_cat->id;
						$product->slug = trim($data[5]);
						$product->description = trim($data[6]);
						$product->price = (trim($data[7]) == 'no provider')? null :  (float) trim($data[7]);
						$product->sale_price = trim($data[9]) == '0' || trim($data[9]) == '' ? null : (float)trim($data[9]);
						$product->cap = (int)trim($data[10]);
						$product->sku = trim($data[11]);
						$product->vol = (int)trim($data[12]);
						$product->abv = (float)trim($data[13]);
						$product->available = trim($data[14]) == 'available' ? true : false;
						$product->visible = trim($data[15]) == 'visible' ? true : false;
						$product->age = (float)trim($data[17]);
						$tags = [];
						if(trim($data[18]) == 'Yes') {
							$tags[] = 0;
						}
						if(trim($data[19]) == 'Yes') {
							$tags[] = 1;
						}
						if(trim($data[20]) == 'Yes') {
							$tags[] = 2;
						}
						if(trim($data[21]) == 'Yes') {
							$tags[] = 3;
						}
						if(trim($data[22]) == 'Yes') {
							$tags[] = 4;
						}
						if(trim($data[23]) == 'Yes') {
							$tags[] = 5;
						}
						
						$product->tags = implode(',', $tags);
						$product->year = (int)trim($data[24]);
						$product->country = trim($data[25]);
						$product->region = trim($data[26]);
						$product->special_offers = trim($data[27]) == 'Yes' ? true : false;
						$product->featured_brand = trim($data[28]) == 'Yes' ? true : false;
						$product->seo_title = trim($data[29]);
						$product->seo_keywords = trim($data[30]);
						$product->seo_description = trim($data[31]);
						$product->save();
						/*echo '<pre>';
					    VarDumper::dump($product);
					    echo '</pre>';
						 die();*/
						    
						$exportImagePath = trim($data[16]);
						if($exportImagePath != '')
						{
							$image = StorageItem::find()->where(['model_name' => 'Product', 'model_id' => $product->id])->one();
							if(!empty($image))
							{
								if($image && Url::base(true) . $image->src == $exportImagePath) {
									continue;
								} elseif($image && Url::base(true) . $image->src != $exportImagePath) {
									$image->delete();
								}
							}
							$img_name = Yii::$app->security->generateRandomString(12);
							$path = Yii::getAlias('@app/web/upload/products');
							$ext = explode('.', $exportImagePath);
							$ext = array_pop($ext);
						    if(!file_exists($path)) 
					    	{
								FileHelper::createDirectory($path, 0777, true);
					    	}
							$this->download_file($exportImagePath, $path . '/' . $img_name . '.' . $ext);
							$image = new StorageItem;
							$image->model_name = $product->formName();
							$image->model_id = $product->id;
							$image->type = 'image/' . $ext;
							$name = explode('/', $exportImagePath);
							$image->name = array_pop($name);
							$image->base_url = '/upload';
							$image->path = 'products/' . $img_name . '.' .$ext;
							$image->save();
						}
							
					}
					

				} catch(Exception $e) {
					unlink(Yii::getAlias('@app/web/upload/' . $model->csv->baseName . '.' . $model->csv->extension));
					Yii::$app->session->setFlash('uploaded-error', '2');
					
					return $this->redirect(Yii::$app->request->referrer);
					
				}
				
			}
		}
		unlink(Yii::getAlias('@app/web/upload/' . $model->csv->baseName . '.' . $model->csv->extension));
		Yii::$app->session->setFlash('uploaded-success', '1');
		
		return $this->redirect(Yii::$app->request->referrer);
	}
	
	public function actionFeatured()
	{
		$model = Product::findOne(['id' => Yii::$app->request->post('id')]);
		if($model->featured_brand === true) {
			$model->featured_brand = false;
		} else {
			$model->featured_brand = true;
		};
		
		return $model->save();
	}
	
	public function actionSpecial()
	{
		$model = Product::findOne(['id' => Yii::$app->request->post('id')]);
		if($model->special_offers === true) {
			$model->special_offers = false;
		} else {
			$model->special_offers = true;
		};
		
		return $model->save();
	}
	
	public function actionExportMainCatalog($items = null)
	{
		$base = explode('/shop', Yii::$app->request->absoluteUrl)[0];
		$list[] = [
			'product_number',
			'name',
			'sku',
			'cap',
			'vol.',
			'abv',
			'price',
		
		];
		FileHelper::createDirectory(Yii::getAlias('@app/web/upload/csv/'), 0775, true);
		
		$query = StoreProduct::find()->where(['store_id' => Yii::$app->user->identity->store->id]);
		if($items != null) {
			$query->where(['id' => Yii::$app->session->get('items')]);
			
		}
		$products = $query->all();
		
		foreach($products as $product) {
			/**
			 * @var $product StoreProduct
			 */
			$list[] = [
				$product->product_id,
				$product->product_name,
				$product->sku ?? '',
				$product->cap ?? '',
				$product->vol ?? '',
				$product->abv ?? '',
				$product->price ?? '',
			];
		}
		
		$fp = fopen(Yii::getAlias('@app/web/upload/csv/list.csv'), 'w');
		
		foreach($list as $fields) {
			fputcsv($fp, $fields);
		}
		
		fclose($fp);
		Yii::$app->session->remove('items');
		//return Yii::getAlias('@app/web/upload/csv/list.csv');
		\Yii::$app->response->sendFile(Yii::getAlias('@app/web/upload/csv/list.csv'));
		unlink(Yii::getAlias('@app/web/upload/csv/list.csv'));
	}
	
	public function actionFindProducts($q = null)
	{
		$query = Product::find();
		
		$qs = explode(' ', $q);
		$prod_query = clone $query;
		foreach($qs as $key) {
			$prod_query->andWhere(['ilike', 'name', $key]);
		}
		$sku_query = clone $query;
		foreach($qs as $key) {
			$sku_query->andWhere(['ilike', 'sku', $key]);
		}
		$products = $prod_query->all();
		$skus = $sku_query->all();
		$result['results'] = [];
		foreach($products as $inventory) {
			$result['results'][] = ['id' => $inventory->name, 'text' => $inventory->name];
		}
		foreach($skus as $inventory) {
			$result['results'][] = ['id' => $inventory->sku, 'text' => $inventory->sku];
		}
		
		return $this->asJson($result);
	}
	
	public function actionGetHistory()
	{
		$data = [];
		$prices = Price::findAll(['product_id' => Yii::$app->request->post('val')]);
		foreach($prices as $price) {
			$elem ['updated_at'] = date('F j, Y', $price->created_at);
			$elem['price'] = Yii::$app->formatter->asCurrency($price->price);
			$data[] = $elem;
		}
		
		return $this->asJson($data);
	}
	
	public function actionAddChecked()
	{
		$val = Yii::$app->request->post('val');
		$checked = Yii::$app->session->get('checked');
		if(in_array($val, $checked)) {
			unset($checked[array_search($val, $checked)]);
		} else {
			$checked[] = $val;
		}
		
		return Yii::$app->session->set('checked', $checked);
	}
	
	public function actionAlert()
	{
		$searchModel = new ProductSearch();
		$dataProvider = $searchModel->searchAlert(Yii::$app->request->queryParams);
		
		return $this->render('alert', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}
	
	public function actionChangePrice($id)
	{
		$product = $this->findModel($id);
		$product->price = Yii::$app->request->post('price');
		
		return $product->save();
	}
	
	public function actionSubcat()
	{
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		$out = [];
		if(isset($_POST['depdrop_parents'])) {
			$parents = $_POST['depdrop_parents'];
			
			if($parents != null) {
				$cat_id = $parents[0];
				$out = [];
				$sub_cats = Category::find()->select(['id', 'name'])->where(['parent_id' => $cat_id])->asArray()->all();
				foreach($sub_cats as $sub) {
					$out[] = ['id' => $sub['id'], 'name' => $sub['name']];
				}
				
				return ['output' => $out, 'selected' => ''];
			}
		}
		
		return ['output' => '', 'selected' => ''];
	}

	function download_file($url, $path) {

	  $newfilename = $path;
	  $file = fopen ($url, "rb");
	  if ($file) {
	    $newfile = fopen ($newfilename, "wb");

	    if ($newfile)
	    while(!feof($file)) {
	      fwrite($newfile, fread($file, 1024 * 8 ), 1024 * 8 );
	    }
	  }

	  if ($file) {
	    fclose($file);
	  }
	  if ($newfile) {
	    fclose($newfile);
	  }
 }
}
