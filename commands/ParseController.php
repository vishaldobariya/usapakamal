<?php
/**
 *
 * @author    Paul Stolyarov <teajeraker@gmail.com>
 * @copyright industrialax.com
 * @license   https://industrialax.com/crm-general-license
 */

namespace app\commands;

use Yii;
use GuzzleHttp\Client;
use yii\console\Controller;
use yii\helpers\FileHelper;
use app\modules\shop\models\Order;
use app\modules\shop\models\Brand;
use app\modules\shop\models\Catalog;
use app\modules\shop\models\Product;
use app\modules\shop\models\Category;
use app\modules\shop\models\Engraving;
use app\modules\shop\models\OrderItem;
use app\modules\shop\models\StoreProduct;
use GuzzleHttp\Exception\ClientException;
use app\modules\storage\models\StorageItem;

class ParseController extends Controller
{
	
	public function actionParse()
	{
		//$client = new Client([
		//	'defaults' => ['timeout' => 1111000],
		//]);
		$res = $this->getContent('https://www.reservebar.com/sitemap_products_1.xml?from=1903286616161&to=4697200197729');
		//$body = $res->getBody();
		$document = \phpQuery::newDocumentHTML($res);
		$links = $document->find('url > loc')->text();
		$links = explode(PHP_EOL, $links);
		$i = 1627;
		$links = array_slice($links, 1627);
		foreach($links as $link) {
			//$client = new Client([
			//	'defaults' => ['timeout' => 1111000],
			//]);
			$res = $this->getContent($link);
			//$body = $res->getBody();
			$document = \phpQuery::newDocumentHTML($res);
			$data = json_decode($document->find('#ProductJson-product-template')->text(), true);
			$cat = Category::findOne(['name' => $data['type']]);
			
			if(!$cat) {
				$cat = new Category;
				$cat->name = $data['type'];
				$cat->save();
			}
			
			$brand = Brand::findOne(['name' => $data['vendor']]);
			if(!$brand) {
				$brand = new Brand;
				$brand->name = $data['vendor'];
				$brand->save();
			}
			$product = Product::findOne(['name' => $data['title']]);
			if($product) {
				$product->price = $data['price'] / 100;
				$product->save();
				if(empty($product->images)) {
					try {
						$img = $data['media'][0]['src'];
						
						$path = Yii::getAlias('@app/web/upload/reservebar/' . $product->slug);
						FileHelper::createDirectory($path, 0777, true);
						copy($img, $path . '/' . $product->slug . '.jpg');
						
						$storage = new StorageItem();
						$storage->model_id = $product->id;
						$storage->model_name = 'Product';
						$storage->base_url = '/upload';
						$storage->path = $product->slug . '/' . $product->slug . '.jpg';
						$storage->name = $product->slug;
						$storage->type = 'image/jpg';
						$storage->save();
						echo $product->id . " :image added \n";
					} catch(\ErrorException $e) {
						echo $e . "\n";
					}
				}
				echo $product->id . " was updated \n";
			}
			echo 'i=' . $i . "\n";
			$i++;
			
			continue;
			$product = new Product;
			$product->name = $data['title'];
			$product->description = $data['description'];
			$product->brand_id = $brand->id;
			$product->category_id = $cat->id;
			$product->price = $data['price'] / 100;
			$product->catalogs = '1';
			$product->visible = true;
			//$product->price_min = $data['price_min'] / 100;
			//$product->price_max = $data['price_max'] / 100;
			//$product->available = $data['available'];
			//$product->shipping = $document->find('h4.shipping + div.product__description')->html();
			if($product->save()) {
				try {
					$img = $data['media'][0]['src'];
					
					$path = Yii::getAlias('@app/web/upload/' . $product->slug);
					FileHelper::createDirectory($path, 0777, true);
					copy($img, $path . '/' . $product->slug . '.jpg');
					
					$storage = new StorageItem();
					$storage->model_id = $product->id;
					$storage->model_name = 'Product';
					$storage->base_url = '/upload';
					$storage->path = $product->slug . '/' . $product->slug . '.jpg';
					$storage->name = $product->slug;
					$storage->type = 'image/jpg';
					$storage->save();
				} catch(\ErrorException $e) {
					echo $e . "\n";
				}
				
			} else {
				die;
			}
			echo $product->id . " was created \n";
			sleep(1);
			
		}
	}
	
	
	public function getContent($url)
	{
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, $url);
		
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 5.1) AppleWebKit/536.11 (KHTML, like Gecko) Chrome/20.0.1132.47 Safari/536.11");
		
		curl_setopt($ch, CURLOPT_HEADER, 0);
		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
		
		$content = curl_exec($ch);
		
		curl_close($ch);
		
		return $content;
	}
	
	public function actionCaskers()
	{
		$catalog = Catalog::find()->where(['name' => 'Caskers'])->one();
		if(!$catalog) {
			$catalog = new Catalog;
			$catalog->name = 'Caskers';
			$catalog->save();
		}
		$client = new Client([
			'defaults' => ['timeout' => 1111000],
		]);
		$res = $client->get('https://www.caskers.com/list-bottles.xml');
		$body = $res->getBody();
		$document = \phpQuery::newDocumentHTML($body);
		$links = $document->find('url > loc')->text();
		$links = explode(PHP_EOL, $links);
		$links = array_slice($links, 646);
		
		$i = 646;
		foreach($links as $link) {
			if($link == '') {
				continue;
			}
			//if($i < 645) {
			//	$i++;
			//	continue;
			//}
			
			//$client = new Client([
			//	'defaults' => ['timeout' => 1111000],
			//]);
			try {
				$content = $this->getContent($link);
			} catch(ClientException $e) {
				continue;
			}
			
			//$body = $res->getBody();
			$document = \phpQuery::newDocumentHTML($content);
			$name = trim($document->find('meta[name=sailthru.title]')->attr('content'));
			
			$product = Product::find()->where(['ilike', 'name', $name])->one();
			
			if($product) {
				$catalogs = explode(',', $product->catalogs);
				if(!in_array($catalog->id, $catalogs)) {
					$catalogs[] = $catalog->id;
					$product->catalogs = implode(',', $catalogs);
					$product->save();
				}
				continue;
			} else {
				$product = new Product;
				
				//category
				$cat = trim($document->find('#product-addtocart-button')->attr('data-category'));
				
				if($cat == '') {
					$cat = explode(' ', $name);
					$category = Category::find();
					$where = ['OR'];
					foreach($cat as $ct) {
						$where[] = ['ilike', 'name', $ct];
					}
					$category = $category->andWhere($where)->one();
					
				} else {
					$category = Category::find()->where(['ilike', 'name', $cat])->one();
				}
				
				if(!$category) {
					$category = new Category;
					$category->name = is_array($cat) ? $cat[0] : $cat;
					$category->save();
				}
				
				//brand
				$br = trim($document->find('#product-addtocart-button')->attr('data-brand'));
				if($br == '') {
					$br = explode(' ', $name);
					$brand = Brand::find();
					$where = ['OR'];
					foreach($br as $ct) {
						$where[] = ['ilike', 'name', $ct];
					}
					$brand = $brand->andWhere($where)->one();
				} else {
					$brand = Brand::findOne(['name' => $br]);
					
				}
				if(!$brand) {
					$brand = new Brand;
					$brand->name = is_array($br) ? $br[0] : $br;
					$brand->save();
				}
				
				$abv = $document->find('td[data-th=Proof]')->text();
				preg_match('$^[0-9]*[.,]?[0-9]+$', $abv, $q);
				$abv = (float)($q[0] ?? 0) / 2;
				$product->abv = $abv;
				
				$vol = (float)$document->find('td[data-th=Size]')->text();
				if($vol < 50) {
					$vol *= 1000;
				}
				$product->vol = $vol;
				
				$product->name = $name;
				$product->category_id = $category->id;
				$product->brand_id = $brand->id;
				$description = $document->find('meta[name=sailthru.description]')->attr('content');
				$product->description = $description;
				$price = (int)$document->find('meta[name=sailthru.price]')->attr('content');
				$product->price = (float)($price / 100);
				$product->catalogs = '2';
				$product->visible = false;
				if($product->save()) {
					try {
						$img = $document->find('meta[property=og:image]')->attr('content');
						$path = Yii::getAlias('@app/web/upload/caskers/' . $product->slug);
						FileHelper::createDirectory($path, 0777, true);
						copy($img, $path . '/' . $product->slug . '.jpg');
						$storage = new StorageItem();
						$storage->model_id = $product->id;
						$storage->model_name = 'Product';
						$storage->base_url = '/upload';
						$storage->path = 'caskers/' . $product->slug . '/' . $product->slug . '.jpg';
						$storage->name = $product->slug;
						$storage->type = 'image/jpg';
						$storage->save();
					} catch(\ErrorException $e) {
						echo $e . "\n";
					}
				}
				
			}
			echo $i . "\n";
			sleep(1);
			$i++;
		}
	}
	
	public function csvToArray($filepath)
	{
		$csv = array_map('str_getcsv', file($filepath)); //get array from csv
		
		$header = array_shift($csv); // select just header from arrays
		
		$header = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $header); //remove Excel's hidden strings
		
		array_unshift($csv, $header); // back header to arrays
		
		/**
		 * combine header with each value array
		 */
		//$csv = array_map(function($i)
		//{
		//	unset($i[14]);
		//
		//	return $i;
		//}, $csv);
		
		array_walk($csv, function(&$a) use ($csv)
		{
			$a = array_combine($csv[0], $a);
		});
		
		//array_shift($csv); //remove header
		
		return $csv;
	}
	
	
	public function actionAge()
	{
		$num = [
			'zero'      => '0',
			'a'         => '1',
			'one'       => '1',
			'two'       => '2',
			'three'     => '3',
			'four'      => '4',
			'five'      => '5',
			'six'       => '6',
			'seven'     => '7',
			'eight'     => '8',
			'nine'      => '9',
			'ten'       => '10',
			'eleven'    => '11',
			'twelve'    => '12',
			'thirteen'  => '13',
			'fourteen'  => '14',
			'fifteen'   => '15',
			'sixteen'   => '16',
			'seventeen' => '17',
			'eighteen'  => '18',
			'nineteen'  => '19',
			'twenty'    => '20',
			'thirty'    => '30',
			'forty'     => '40',
			'fourty'    => '40', // common misspelling
			'fifty'     => '50',
			'sixty'     => '60',
			'seventy'   => '70',
			'eighty'    => '80',
			'ninety'    => '90',
			'hundred'   => '100',
			'thousand'  => '1000',
			'million'   => '1000000',
			'billion'   => '1000000000',
			'and'       => '',
		];
		
		$products = Product::find()->select(['id', 'name', 'description'])->orderBy(['id' => SORT_ASC])->all();
		foreach($products as $product) {
			/**
			 * @var $product Product
			 */
			preg_match('$\d+ [y,Y]ear$', $product->name, $q);
			if(empty($q)) {
				preg_match('$\d+-[y,Y]ear$', $product->name, $q);
			}
			if(empty($q)) {
				preg_match('$\d+ [y,Y]ear$', $product->description, $q);
			}
			if(empty($q)) {
				preg_match('$\d+-[y,Y]ear$', $product->description, $q);
			}
			
			if(!empty($q)) {
				$age = $q[0];
				$age = str_replace('-year', '', $age);
				$age = str_replace('-Year', '', $age);
				$age = str_replace(' year', '', $age);
				$age = str_replace(' Year', '', $age);
				if(isset($num[$age])) {
					$age = $num[$age];
				}
				if((float)$age < 100) {
					$product->age = (float)$age;
					$product->save();
					echo $product->id . "\n";
				}
				
			}
			
		}
	}
	
	public function actionCsv()
	{
		$catalog = Catalog::find()->where(['name' => 'Reservebar'])->one();
		
		if(($handle = fopen(Yii::getAlias('@app/products.csv'), "r")) !== false) {
			$flag = true;
			//find or create store
			
			while(($data = fgetcsv($handle, 1000000, ',')) !== false) {
				if($flag) {
					$flag = false;
					continue;
				}
				
				$product = Product::find()->where(['ilike', 'name', trim($data[7])])->one();
				if($product) {
					$catalogs = explode(',', $product->catalogs);
					if(!in_array($catalog->id, $catalogs)) {
						$catalogs[] = $catalog->id;
						$product->catalogs = implode(',', $catalogs);
						$product->save();
					}
					continue;
				} else {
					$product = new Product;
					$product->name = trim($data[7]);
					$cat = trim($data[3]);
					if($cat == '') {
						$cat = explode(' ', trim($data[7]));
						$category = Category::find();
						$where = ['OR'];
						foreach($cat as $ct) {
							$where[] = ['ilike', 'name', $ct];
						}
						$category = $category->andWhere($where)->one();
						
					} else {
						$category = Category::find()->where(['ilike', 'name', $cat])->one();
					}
					
					if(!$category) {
						$category = new Category;
						$category->name = is_array($cat) ? $cat[0] : $cat;
						$category->save();
					}
					$product->category_id = $category->id;
					
					$br = trim($data[1]);
					if($br == '') {
						$br = explode(' ', trim($data[7]));
						$brand = Brand::find();
						$where = ['OR'];
						foreach($br as $ct) {
							$where[] = ['ilike', 'name', $ct];
						}
						$brand = $brand->andWhere($where)->one();
					} else {
						$brand = Brand::findOne(['name' => $br]);
						
					}
					if(!$brand) {
						$brand = new Brand;
						$brand->name = is_array($br) ? $br[0] : $br;
						$brand->save();
					}
					
					$product->brand_id = $brand->id;
					$product->catalogs = '1';
					$product->sku = trim($data[6]);
					$product->visible = false;
					$product->abv = (float)trim($data[8]);
					$product->vol = (float)trim($data[5]);
					$product->validate();
					
					if(!$product->save()) {
						dd($product->errors);
					};
					
				}
			}
		}
		
	}
	
	public function actionCaskCartelCsv()
	{
		$catalog = Catalog::find()->where(['name' => 'Cask Cartel'])->one();
		if(!$catalog) {
			$catalog = new Catalog;
			$catalog->name = 'Cask Cartel';
			$catalog->save();
		}
		
		if(($handle = fopen(Yii::getAlias('@app/caskCartel.csv'), "r")) !== false) {
			$flag = true;
			//find or create store
			
			while(($data = fgetcsv($handle, 1000000, ',')) !== false) {
				if($flag) {
					$flag = false;
					continue;
				}
				
				$product = Product::find()->where(['ilike', 'name', trim($data[0])])->one();
				if($product) {
					$catalogs = explode(',', $product->catalogs);
					if(!in_array($catalog->id, $catalogs)) {
						$catalogs[] = $catalog->id;
						$product->catalogs = implode(',', $catalogs);
						$product->save();
					}
					continue;
				} else {
					$product = new Product;
					$product->name = trim($data[0]);
					$cat = trim($data[3]);
					if($cat == '') {
						$cat = explode(' ', trim($data[0]));
						$category = Category::find();
						$where = ['OR'];
						foreach($cat as $ct) {
							$where[] = ['ilike', 'name', $ct];
						}
						$category = $category->andWhere($where)->one();
						
					} else {
						$category = Category::find()->where(['ilike', 'name', $cat])->one();
					}
					
					if(!$category) {
						$category = new Category;
						$category->name = is_array($cat) ? $cat[0] : $cat;
						$category->save();
					}
					$product->category_id = $category->id;
					
					$br = trim($data[1]);
					if($br == '') {
						$br = explode(' ', trim($data[0]));
						$brand = Brand::find();
						$where = ['OR'];
						foreach($br as $ct) {
							$where[] = ['ilike', 'name', $ct];
						}
						$brand = $brand->andWhere($where)->one();
					} else {
						$brand = Brand::findOne(['name' => $br]);
						
					}
					if(!$brand) {
						$brand = new Brand;
						$brand->name = is_array($br) ? $br[0] : $br;
						$brand->save();
					}
					
					$product->brand_id = $brand->id;
					$product->catalogs = (string)$catalog->id;
					$product->sku = trim($data[6]);
					$product->visible = false;
					$product->vol = (float)trim($data[5]);
					$product->validate();
					
					if(!$product->save()) {
						var_dump($product->errors);
					};
					
				}
			}
		}
		
	}
	
	public function actionMash()
	{
		$catalog = Catalog::find()->where(['name' => 'Mics'])->one();
		if(!$catalog) {
			$catalog = new Catalog;
			$catalog->name = 'Mics';
			$catalog->save();
		}
		
		if(($handle = fopen(Yii::getAlias('@app/mics.csv'), "r")) !== false) {
			$flag = true;
			//find or create store
			
			while(($data = fgetcsv($handle, 1000000, ',')) !== false) {
				if($flag) {
					$flag = false;
					continue;
				}
				
				$product = Product::find()->where(['ilike', 'name', trim($data[0])])->one();
				if($product) {
					$catalogs = explode(',', $product->catalogs);
					if(!in_array($catalog->id, $catalogs)) {
						$catalogs[] = $catalog->id;
						$product->catalogs = implode(',', $catalogs);
						$product->save();
					}
					continue;
				} else {
					$product = new Product;
					$product->name = trim($data[0]);
					$cat = trim($data[3]);
					if($cat == '') {
						$cat = explode(' ', trim($data[0]));
						$category = Category::find();
						$where = ['OR'];
						foreach($cat as $ct) {
							$where[] = ['ilike', 'name', $ct];
						}
						$category = $category->andWhere($where)->one();
						
					} else {
						$category = Category::find()->where(['ilike', 'name', $cat])->one();
					}
					
					if(!$category) {
						$category = new Category;
						$category->name = is_array($cat) ? $cat[0] : $cat;
						$category->save();
					}
					$product->category_id = $category->id;
					
					$br = trim($data[1]);
					if($br == '') {
						$br = explode(' ', trim($data[0]));
						$brand = Brand::find();
						$where = ['OR'];
						foreach($br as $ct) {
							$where[] = ['ilike', 'name', $ct];
						}
						$brand = $brand->andWhere($where)->one();
					} else {
						$brand = Brand::findOne(['name' => $br]);
						
					}
					if(!$brand) {
						$brand = new Brand;
						$brand->name = is_array($br) ? $br[0] : $br;
						$brand->save();
					}
					
					$product->brand_id = $brand->id;
					$product->catalogs = (string)$catalog->id;
					$product->sku = trim($data[4]);
					$product->visible = false;
					$product->vol = (float)trim($data[5]);
					$product->validate();
					
					if(!$product->save()) {
						var_dump($product->errors);
					};
					
				}
			}
		}
		
	}
	
	
	public function actionCask()
	{
		$res = $this->getContent('https://www.mashandgrape.com/sitemap_products_1.xml?from=392457780&to=6161001513147');
		$document = \phpQuery::newDocumentHTML($res);
		$links = $document->find('url > loc')->text();
		$links = explode(PHP_EOL, $links);
		dd($links);
	}
	
	public function actionMashGrape()
	{
		die;
		$catalog = Catalog::find()->where(['name' => 'Mash and Grape'])->one();
		if(!$catalog) {
			$catalog = new Catalog;
			$catalog->name = 'Mash and Grape';
			$catalog->save();
		}
		$res = $this->getContent('https://www.mashandgrape.com/sitemap_products_1.xml?from=392457780&to=6161001513147');
		$document = \phpQuery::newDocumentHTML($res);
		$links = $document->find('url > loc')->text();
		$links = explode(PHP_EOL, $links);
		unset($links[2456]);
		unset($links[0]);
		$links = array_slice($links, 1450);
		foreach($links as $link) {
			//$client = new Client([
			//	'defaults' => ['timeout' => 1111000],
			//]);
			$res = $this->getContent($link);
			//$body = $res->getBody();
			$document = \phpQuery::newDocumentHTML($res);
			$data = json_decode($document->find('[data-desc=seo-product]')->text(), true);
			
			$name = trim($data['name']);
			$cats = explode('-', str_replace(' ', '-', $name));
			
			$query = Category::find();
			foreach($cats as $cat) {
				$query->orWhere(['ilike', 'name', trim($cat)]);
			}
			$category = $query->one();
			if(!$category) {
				$category = Category::find()->where(['ilike', 'name', ' '])->one();
			}
			
			$brand = Brand::findOne(['name' => $data['brand']['name']]);
			if(!$brand) {
				$brand = new Brand;
				$brand->name = $data['brand']['name'];
				$brand->save();
			}
			
			$product = Product::findOne(['name' => $data['name']]);
			if($product) {
				$catalogs = explode(',', $product->catalogs);
				if(!in_array($catalog->id, $catalogs)) {
					$catalogs[] = $catalog->id;
					$product->catalogs = implode(',', $catalogs);
					$product->save();
				}
				
				if(empty($product->images)) {
					try {
						$img = $data['image'];
						$path = Yii::getAlias('@app/web/upload/' . $product->slug);
						FileHelper::createDirectory($path, 0777, true);
						copy($img, $path . '/' . $product->slug . '.jpg');
						$storage = new StorageItem();
						$storage->model_id = $product->id;
						$storage->model_name = 'Product';
						$storage->base_url = '/upload';
						$storage->path = $product->slug . '/' . $product->slug . '.jpg';
						$storage->name = $product->slug;
						$storage->type = 'image/jpg';
						$storage->save();
						echo $product->id . " :image added \n";
					} catch(\ErrorException $e) {
						echo $e . "\n";
					}
				}
				echo $product->id . " was updated \n";
			} else {
				$product = new Product;
				$product->name = $data['name'];
				$product->description = $data['description'] . '<p>This product is available in: AR, AZ, CA, CO, CT, DC, DE, FL, IA, ID, IL, IN, KY, LA, MA, MD, ME, MN, MO, MT, NC, ND, NE,
					 NH, NJ, NM, NV, NY, OK, OR, RI, SC, SD, TX, VA, VT, WA, WI, WV, WY.</p>';
				$product->brand_id = $brand->id;
				$product->category_id = $category->id;
				$product->price = (float)trim($data['offers']['price']);
				$product->catalogs = (string)$catalog->id;
				$product->visible = false;
				if($product->save()) {
					try {
						$img = $data['image'];
						
						$path = Yii::getAlias('@app/web/upload/' . $product->slug);
						FileHelper::createDirectory($path, 0777, true);
						copy($img, $path . '/' . $product->slug . '.jpg');
						
						$storage = new StorageItem();
						$storage->model_id = $product->id;
						$storage->model_name = 'Product';
						$storage->base_url = '/upload';
						$storage->path = $product->slug . '/' . $product->slug . '.jpg';
						$storage->name = $product->slug;
						$storage->type = 'image/jpg';
						$storage->save();
					} catch(\ErrorException $e) {
						echo $e . "\n";
					}
					
				} else {
					var_dump($product->errors);
					die;
				}
				echo $product->id . " was created \n";
				sleep(1);
			}
			
		}
	}
	
	public function actionCaskCartel()
	{
		$catalog = Catalog::find()->where(['name' => 'Cask Cartel'])->one();
		if(!$catalog) {
			$catalog = new Catalog;
			$catalog->name = 'Cask Cartel';
			$catalog->save();
		}
		$sitemaps = [
			//'https://caskcartel.com/sitemap_products_1.xml?from=1649159143535&to=4490212376714',
			//'https://caskcartel.com/sitemap_products_2.xml?from=4490212671626&to=5180574335114',
			'https://caskcartel.com/sitemap_products_3.xml?from=5180574367882&to=5380903207050',
		];
		
		foreach($sitemaps as $url) {
			$res = $this->getContent($url);
			$document = \phpQuery::newDocumentHTML($res);
			$links = $document->find('url > loc')->text();
			$links = explode(PHP_EOL, $links);
			$i = 0;
			//$links = array_slice($links, $i);
			foreach($links as $link) {
				if($link == '' || $link == 'https://caskcartel.com/es' || $link == 'https://caskcartel.com/') {
					continue;
				}
				
				$res = $this->getContent($link . '.js');
				//$document = \phpQuery::newDocumentHTML($res);
				
				$data = json_decode($res, true);
				$cat = Category::find()->where(['name' => trim($data['type'])])->one();
				if(!$cat) {
					$cat = Category::find()->where(['ilike', 'name', trim($data['type'])])->one();
				}
				if(!$cat) {
					$cat = new Category;
					$cat->name = trim($data['type']);
					$cat->save();
				}
				$br = explode(' ', trim($data['title']))[0];
				$brand = Brand::find()->where(['ilike', 'name', $br])->one();
				
				if(!$brand) {
					$brand = new Brand;
					$brand->name = $br;
					$brand->save();
				}
				
				$product = Product::findOne(['name' => trim($data['title'])]);
				if($product) {
					$catalogs = explode(',', $product->catalogs);
					if(!in_array($catalog->id, $catalogs)) {
						$catalogs[] = $catalog->id;
						$product->catalogs = implode(',', $catalogs);
						$product->save();
					}
					
					if(empty($product->images)) {
						try {
							$img = $data['media'][0]['src'];
							$path = Yii::getAlias('@app/web/upload/' . $product->slug);
							FileHelper::createDirectory($path, 0777, true);
							copy($img, $path . '/' . $product->slug . '.jpg');
							$storage = new StorageItem();
							$storage->model_id = $product->id;
							$storage->model_name = 'Product';
							$storage->base_url = '/upload';
							$storage->path = $product->slug . '/' . $product->slug . '.jpg';
							$storage->name = $product->slug;
							$storage->type = 'image/jpg';
							$storage->save();
							echo $product->id . " :image added \n";
						} catch(\ErrorException $e) {
							echo $e . "\n";
						}
					}
					echo $product->id . " was updated \n";
				} else {
					$product = new Product;
					$product->name = $data['title'];
					$product->description = explode('<!-- split --><!-- start interested in -->', $data['description'])[0] . '<p>This product is available in: AR, AZ, CA, CO, CT, DC, DE, FL, IA, ID, IL, IN, KY, LA, MA, MD, ME, MN, MO, MT, NC, ND, NE,
					 NH, NJ, NM, NV, NY, OK, OR, RI, SC, SD, TX, VA, VT, WA, WI, WV, WY.</p>';
					$product->brand_id = $brand->id;
					$product->category_id = $cat->id;
					$product->price = (float)($data['price'] / 100);
					$product->catalogs = (string)$catalog->id;
					$product->visible = true;
					if($product->save()) {
						try {
							$img = $img = $data['media'][0]['src'];
							
							$path = Yii::getAlias('@app/web/upload/' . $product->slug);
							FileHelper::createDirectory($path, 0777, true);
							copy($img, $path . '/' . $product->slug . '.jpg');
							
							$storage = new StorageItem();
							$storage->model_id = $product->id;
							$storage->model_name = 'Product';
							$storage->base_url = '/upload';
							$storage->path = $product->slug . '/' . $product->slug . '.jpg';
							$storage->name = $product->slug;
							$storage->type = 'image/jpg';
							$storage->save();
						} catch(\ErrorException $e) {
							echo $e . "\n";
						}
						
					} else {
						var_dump($product->errors);
						die;
					}
					echo $product->id . " was created \n";
					sleep(1);
				}
				$i++;
				echo "i=" . $i . "\n";
			}
			echo "DONE\n";
		}
		
	}
	
	public function actionDesc()
	{
		$products = Product::find()->all();
		foreach($products as $product) {
			/**
			 * @var $product Product
			 */
			$product->description = str_replace('This product is available in: AR, AZ, CA, CO, CT, DC, DE, FL, IA, ID, IL, IN, KY, LA, MA, MD, ME, MN, MO, MT, NC, ND, NE, NH, NJ, NM, NV, NY, OK, OR, RI, SC, SD, TX, VA, VT, WA, WI, WV, WY.', '', $product->description);
			if(!$product->save()) {
				var_dump($product->errors);
				die;
			};
			echo $product->id . "\n";
		}
	}
	
	public function actionFinal()
	{
		$path = Yii::getAlias('@app/web/upload/products/');
		
		FileHelper::createDirectory($path, 0777, true);
		Product::deleteAll(['>', 'id', 0]);
		Category::deleteAll(['>', 'id', 0]);
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
					$image->base_url = '/upload/';
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
	
	public function actionSku()
	{
		$prods = Product::find()->orderBy(['id' => SORT_ASC])->all();
		
		foreach($prods as $prod) {
			$prod->sku = 'RBBA' . ($prod->id + 1000000000);
			$prod->save();
		}
	}
}
