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
use kartik\mpdf\Pdf;
use yii\helpers\Url;
use yii\web\Controller;
use yii\helpers\FileHelper;
use app\modules\user\models\User;
use app\modules\shop\models\Order;
use app\modules\shop\models\Brand;
use app\modules\admin\models\Theme;
use app\modules\shop\models\Contact;
use app\modules\shop\models\Product;
use app\modules\shop\models\Category;
use app\modules\shop\models\Engraving;
use app\modules\shop\models\OrderItem;
use app\modules\shop\models\StoreProduct;
use app\modules\subscribe\models\Subscribe;
use app\modules\storage\models\StorageItem;

class SiteController extends Controller
{
	
	/**
	 * Displays Index Page.
	 *
	 * @return string
	 */
	public function actionIndex()
	{
		/**
		 * SEO tags
		 */
		$view = Yii::$app->view;
		$view->title = Yii::$app->settings->main_title;
		$view->registerMetaTag(['name' => 'description', 'content' => Yii::$app->settings->main_description]);
		$view->registerMetaTag(['name' => 'keywords', 'content' => Yii::$app->settings->main_keywords]);
		
		$products = Product::find()->with(['images', 'category.images', 'brand.images', 'subCategory.images'])->where(['available' => true, 'featured_brand' => true])->limit(16)->all();
		$offers = Product::find()->with(['images', 'category.images', 'brand.images', 'subCategory.images'])->where(['available' => true, 'special_offers' => true])->all();
		$sliders = StorageItem::find()->where(['model_name' => 'Slider'])->orderBy(['position' => SORT_ASC])->all();
		$mobile_sliders = StorageItem::find()->where(['model_name' => 'Mobile Slider'])->orderBy(['position' => SORT_ASC])->all();
		$footer_images = StorageItem::find()->where(['model_name' => 'Footer Image'])->orderBy(['position' => SORT_ASC])->all();
		$middle_images = StorageItem::find()->where(['model_name' => 'Middle Image'])->orderBy(['position' => SORT_ASC])->all();
		
		$brands = Brand::find()->where(['main' => true])->with('images')->orderBy(['position' => SORT_ASC])->all();
		
		return $this->render('index', [
			'products'       => $products,
			'offers'         => $offers,
			'brands'         => $brands,
			'sliders'        => $sliders,
			'footer_images'  => $footer_images,
			'middle_images'  => $middle_images,
			'mobile_sliders' => $mobile_sliders,
		]);
	}
	
	
	public function actionContact()
	{
		/**
		 * SEO tags
		 */
		$view = Yii::$app->view;
		$view->title = Yii::$app->settings->main_title;
		$view->registerMetaTag(['name' => 'description', 'content' => Yii::$app->settings->main_description]);
		$view->registerMetaTag(['name' => 'keywords', 'content' => Yii::$app->settings->main_keywords]);
		
		return $this->redirect(['index']);
		$model = new Contact;
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
	
	public function actionTerms()
	{
		return $this->render('terms');
	}
	
	public function actionPolicy()
	{
		return $this->render('policy');
	}
	
	public function actionDelivery()
	{
		return $this->render('delivery');
	}
	
	public function actionContactus()
	{
		return $this->render('contactus');
	}
	
	public function actionPrivacy()
	{
		return $this->render('privacy');
	}
	
	public function actionBrands()
	{
		$brands = Brand::find()->where(['main' => true])->with('images')->orderBy(['position' => SORT_ASC])->all();
		
		return $this->render('brands', [
			'brands' => $brands,
		]);
	}
	
	public function actionError()
	{
		$this->layout = YII_ENV == 'dev' ? false : 'main';
		$path = YII_ENV == 'dev' ? '@yii/views/errorHandler/exception.php' : '404';
		
		return $this->render($path, [
			'handler'   => Yii::$app->errorHandler,
			'exception' => Yii::$app->errorHandler->exception,
		]);
	}
	
	/**
	 * @param null $term
	 * @param null $id
	 *
	 * @return \yii\web\Response
	 */
	public function actionSearch($term = null)
	{
		$term = preg_replace('/[^\p{L}\p{N}\s]/u', '', $term);
		//		$sql = <<< SQL
		//		Select product.id, slug, product.name, storage.base_url,storage.path, price,  word_similarity(product.name, '$term') as rel  from Product join storage on
		//		product.id = storage.model_id and storage.model_name = 'Product'
		//		where product.visible is true ORDER  BY product.name <-> '$term'  limit 5
		//SQL;
		$when = [];
		
		foreach(explode(' ', $term) as $t) {
			$when[] = "CASE
WHEN  product.name ilike '%$t%' THEN 1
ELSE 0
END";
		}
		$when = implode(' + ', $when);
		$sql = <<< SQL
		Select product.id, slug, product.name, storage.base_url,storage.path, price  from Product join storage on
		product.id = storage.model_id and storage.model_name = 'Product'
		where product.visible is true ORDER  BY ($when) DESC limit 5
SQL;
		$products = Yii::$app->db->createCommand($sql)
		                         ->queryAll();
		
		$result = [];
		foreach($products as $product) {
			$highlightName = $product['name'];
			$result[] = [
				'id'    => $product['id'],
				'value' => $highlightName,
				'label' => $highlightName,
				'image' => $product['base_url'] . '/' . $product['path'],
				'price' => Yii::$app->formatter->asCurrency($product['price']),
				'link'  => Url::toRoute(['/shop/shop/product', 'slug' => $product['slug']]),
			];
		}
		
		return $this->asJson($result);
	}
	
	
	public function actionPdf()
	{
		$pdf = new Pdf([
			'mode'        => Pdf::MODE_CORE,
			//'cssInline'   => $this->render('css/pdf-css.css'),
			'destination' => Pdf::DEST_BROWSER,
			'cssFile'     => '@app/web/dist/pdf.css',
			'content'     => $this->renderPartial('_pdf'),
			'methods'     => [
				'SetHeader'  => ['Royal Batch || Created On: ' . date("m/d/y")],
				'SetFooter'  => ['|{PAGENO}<br>ANSWER TO UNVERIFIED COMPLAINT|'],
				'SetAuthor'  => 'royal-batch.com',
				'SetCreator' => 'royal-batch.com',
			],
		]);
		
		return $pdf->render();
	}
	
	public function actionSubscribeForm()
	{
		$model = new Subscribe;
		$model->load(Yii::$app->request->post());
		if(Subscribe::find()->where(['email' => $model->email])->exists() || User::find()->where(['email' => $model->email])->exists()) {
			return $this->asJson(['status' => 'error', 'message' => 'You are already subscribed']);
		}
		$model->active = true;
		$model->save();
		$user = new User;
		$user->email = $model->email;
		$user->first_name = explode('@', $model->email)[0];
		$user->auth_key = Yii::$app->security->generateRandomString(32);
		if($user->save()) {
			Theme::createTheme($user->id);
		};
		
		Yii::$app->mailer->compose('@app/web/mail/sign_up_user', ['code' => $user->auth_key, 'user' => $user])
		                 ->setTo($model->email)
		                 ->setFrom([MAIL_USER => 'Royal Batch'])
		                 ->setSubject('Thank you for subscribing')
		                 ->send();
		
		return $this->asJson(['status' => 'ok', 'message' => 'Thank you for subscribing! Please check your email']);
	}
	
	
	public function actionFinal()
	{
		$path = Yii::getAlias('@app/web/upload/products/');
		
		FileHelper::createDirectory($path, 0777, true);
		Product::deleteAll(['>', 'id', 0]);
		StoreProduct::deleteAll(['>', 'id', 0]);
		Order::deleteAll(['>', 'id', 0]);
		OrderItem::deleteAll(['>', 'id', 0]);
		Engraving::deleteAll(['>', 'id', 0]);
		StorageItem::deleteAll(['model_name' => 'Product']);
		
		if(($handle = fopen(Yii::getAlias('@app/final.csv'), "r")) !== false) {
			$flag = true;
			//find or create store
			$i = 0;
			while(($data = fgetcsv($handle, 1000000, ',')) !== false) {
				if($flag) {
					$flag = false;
					continue;
				}
				//dd($data);
				
				$brand = Brand::find()->where(['name' => trim($data[1])])->one();
				if(!$brand) {
					$brand = new Brand;
					$brand->name = trim($data[1]);
					$brand->save();
				}
				
				$cat = Category::find()->where(['name' => trim($data[3])])->one();
				if(!$cat) {
					$cat = new Category;
					$cat->name = trim($data[3]);
					$cat->save();
				}
				
				if($data[4] != '') {
					$sub_cat = Category::find()->where(['name' => trim($data[4])])->one();
					if(!$sub_cat) {
						$sub_cat = new Category;
						$sub_cat->name = trim($data[4]);
						$sub_cat->parent_id = $cat->id;
						$sub_cat->save();
					}
				}
				$tags = [];
				if($data[11] == 'Yes' || $data[23] == 'Yes') {
					$tags[] = 3;
				}
				if($data[24] == 'Yes') {
					$tags[] = 4;
				}
				if($data[25] == 'Yes') {
					$tags[] = 5;
				}
				if($data[20] == 'Yes') {
					$tags[] = 0;
				}
				if($data[21] == 'Yes') {
					$tags[] = 1;
				}
				if($data[22] == 'Yes') {
					$tags[] = 2;
				}
				
				$product = new Product;
				$product->brand_id = $brand->id;
				$product->category_id = $cat->id;
				$product->sub_category_id = $data[4] != '' ? $sub_cat->id : null;
				$product->name = trim($data[2]);
				$product->description = trim($data[6]);
				$product->price = (float)trim($data[7]);
				$product->sale_price = (float)trim($data[9]);
				$product->cap = (int)trim($data[10]);
				$product->tags = implode(',', $tags);
				$product->vol = trim($data[14]);
				$product->abv = (float)trim($data[15]);
				$product->available = $data[16] == 'available' || $data[16] == 'Yes' ? true : false;
				$product->visible = $data[17] == 'visible' || $data[17] == 'Yes' ? true : false;
				$product->age = $data[19];
				$product->year = $data[26];
				$product->country = $data[28];
				$product->region = $data[29];
				$product->special_offers = $data[30] == 'Yes' ? true : false;
				$product->featured_brand = $data[31] == 'Yes' ? true : false;
				$product->seo_title = $data[32];
				$product->seo_keywords = $data[33];
				$product->seo_description = $data[34];
				
				$product->save();
				
				$product->sku = 'RBBA' . (100000000 + $product->id);
				
				if(trim($data[18]) != '') {
					$array = explode('/', $data[18]);
					$name_img = array_pop($array);
					$new_name = $product->id . '.' . explode('.', $name_img)[1];
					copy(Yii::getAlias('@app/web') . '/upload/other/' . $name_img, $path . $new_name);
					
					$image = new StorageItem;
					$image->model_id = $product->id;
					$image->model_name = 'Product';
					$image->base_url = 'upload/';
					$image->type = 'image/png';
					$image->path = 'products/' . $new_name;
					$image->name = $new_name;
					$image->save();
					echo $image->path . "\n";
					
				}
				echo $product->id . "\n";
				echo $i . "\n";
				$i++;
			}
		}
	}
	
	public function actionDelete()
	{
		return Category::deleteAll(['>','id',0]);
	}
}
