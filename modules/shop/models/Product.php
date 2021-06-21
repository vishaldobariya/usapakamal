<?php

namespace app\modules\shop\models;

use yii\behaviors\SluggableBehavior;
use app\modules\provider\models\Store;
use yz\shoppingcart\CartPositionTrait;
use yz\shoppingcart\CartPositionInterface;
use app\modules\storage\models\StorageItem;
use app\modules\storage\behaviors\UploadBehavior;

/**
 * This is the model class for table "product".
 *
 * @property int         $id
 * @property string|null $name
 * @property string|null $description
 * @property string|null $seo_title
 * @property string|null $seo_description
 * @property string|null $seo_keywords
 * @property string|null $shipping
 * @property string|null $sku
 * @property string|null $country
 * @property string|null $region
 * @property float|null  $price
 * @property float|null  $price_min
 * @property float|null  $price_max
 * @property float|null  $sale_price
 * @property float|null  $provider_price
 * @property int|null    $category_id
 * @property int|null    $sub_category_id
 * @property int|null    $vol
 * @property float|null  $abv
 * @property float|null  $age
 * @property int|null    $brand_id
 * @property int|null    $year
 * @property int|null    $cap
 * @property int|null    $store_id
 * @property string|null $slug
 * @property string|null $tags
 * @property string|null $catalogs
 * @property boolean     $available
 * @property boolean     $special_offers
 * @property boolean     $featured_brand
 * @property boolean     $visible
 *
 * @property Brand       $brand
 * @property Category    $category
 */
class Product extends \yii\db\ActiveRecord implements CartPositionInterface
{
	
	use CartPositionTrait;
	
	public $image;
	
	public $relevance;
	
	public $percent;
	
	public $key;
	
	public $engraving_front;
	
	public $engraving_back;
	
	public $engraving = [];
	
	public $estimated_price;
	
	public $warning;
	
	const TAGS = [
		0 => 'Sale',
		1 => 'Sold Out',
		2 => 'New',
		3 => 'Available with Engraving',
		4 => 'Limited Edition',
		5 => 'Special Promotion',
	];
	
	/**
	 * {@inheritdoc}
	 */
	public static function tableName()
	{
		return 'product';
	}
	
	public function behaviors()
	{
		return [
			[
				'class'         => SluggableBehavior::class,
				'attribute'     => 'name',
				'slugAttribute' => 'slug',
				'ensureUnique'  => true,
			],
			[
				'class'            => UploadBehavior::class,
				'multiple'         => true,
				'attribute'        => 'image',
				'uploadRelation'   => 'images',
				'filesStorage'     => 'fileStorage',
				'pathAttribute'    => 'path',
				'baseUrlAttribute' => 'base_url',
				'typeAttribute'    => 'type',
				'sizeAttribute'    => 'size',
				'nameAttribute'    => 'name',
				'orderAttribute'   => false,
			],
			
		];
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['description', 'shipping', 'sku', 'catalogs', 'seo_keywords', 'seo_title', 'seo_description', 'region', 'country'], 'string'],
			[['price', 'price_min', 'price_max', 'sale_price', 'abv', 'age', 'provider_price'], 'number'],
			[['category_id', 'brand_id'], 'default', 'value' => null],
			[['category_id', 'brand_id', 'cap', 'vol', 'year'], 'integer'],
			[['name', 'slug'], 'string', 'max' => 255],
			[['brand_id'], 'exist', 'skipOnError' => true, 'targetClass' => Brand::className(), 'targetAttribute' => ['brand_id' => 'id']],
			[['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
			[['sub_category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['sub_category_id' => 'id']],
			['image', 'safe'],
			[['available', 'tags', 'featured_brand', 'special_offers', 'store_id', 'visible'], 'safe'],
		];
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels()
	{
		return [
			'id'             => 'ID',
			'name'           => 'Name',
			'price'          => 'Price',
			'category_id'    => 'Category',
			'brand_id'       => 'Brand',
			'slug'           => 'Slug',
			'available'      => 'Available',
			'sku'            => 'SKU',
			'cap'            => 'CAP',
			'sale_price'     => 'Sale Price',
			'tags'           => 'tags',
			'special_offers' => 'Special Offers',
			'vol'            => 'VOL',
			'abv'            => 'ABV',
			'featured_brand' => 'Featured Brand',
			'store_id'       => 'Store',
			'visible'        => 'Visible',
			'age'            => 'Age',
			'year'           => 'Year',
			'sub_category_id' => 'Sub Category'
		];
	}
	
	/**
	 * Gets query for [[Brand]].
	 *
	 * @return \yii\db\ActiveQuery
	 */
	public function getBrand()
	{
		return $this->hasOne(Brand::className(), ['id' => 'brand_id']);
	}
	
	/**
	 * Gets query for [[Category]].
	 *
	 * @return \yii\db\ActiveQuery
	 */
	public function getCategory()
	{
		return $this->hasOne(Category::className(), ['id' => 'category_id']);
	}
	
	public function getSubCategory()
	{
		return $this->hasOne(Category::className(), ['id' => 'sub_category_id']);
	}
	
	/**
	 * @return \yii\db\ActiveQuery
	 * @throws \yii\base\InvalidConfigException
	 */
	public function getImages()
	{
		return $this->hasMany(StorageItem::class, ['model_id' => 'id'])->onCondition(['model_name' => $this->formName()]);
	}
	
	/**
	 * @return string
	 */
	public function getThumb()
	{
		return !empty($this->images) ? $this->images[0]->src : '/images/logo-gold.png';
	}
	
	/**
	 * @return string
	 */
	public function getNameImage()
	{
		return !empty($this->images) ? $this->images[0]->name : '';
	}
	
	public function getPrice()
	{
		$price = 0;
		if(in_array('0', explode(',', $this->tags))) {
			$price = $this->sale_price;
		} else {
			$price = $this->price;
		}
		
		//$price += $this->engraving_front ? (float)\Yii::$app->settings->front_engraving : 0;
		//$price += $this->engraving_back ? (float)\Yii::$app->settings->back_engraving : 0;
		
		return $price;
	}
	
	/**
	 * @return string
	 */
	public function getId()
	{
		return $this->id;
	}
	
	public function isSale()
	{
		return in_array('0', explode(',', $this->tags));
	}
	
	public function isSold()
	{
		return in_array('1', explode(',', $this->tags));
	}
	
	public function isNew()
	{
		return in_array('2', explode(',', $this->tags));
	}
	
	public function isAvailableWithEngraving()
	{
		return in_array('3', explode(',', $this->tags));
	}
	
	public function isLimitedEdition()
	{
		return in_array('4', explode(',', $this->tags));
	}
	
	public function isSpecialPromotion()
	{
		return in_array('5', explode(',', $this->tags));
	}
	
	public function getProvider()
	{
		return $this->hasOne(Store::class, ['id' => 'store_id']);
	}
	
	public function getStoreProducts()
	{
		return $this->hasMany(StoreProduct::class, ['product_id' => 'id'])->where([StoreProduct::tableName() . '.connected' => true])->orderBy([StoreProduct::tableName() . '.price' => SORT_ASC]);
	}
	
	public function getStoreProds()
	{
		return $this->hasMany(StoreProduct::class, ['product_id' => 'id']);
	}
	
	public function getStoreProduct()
	{
		return $this->hasOne(StoreProduct::class, ['product_id' => 'id'])->onCondition(['store_id' => \Yii::$app->user->identity->store->id]);
	}
	
}
