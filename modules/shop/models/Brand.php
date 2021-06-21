<?php

namespace app\modules\shop\models;

use yii\behaviors\SluggableBehavior;
use app\modules\storage\models\StorageItem;
use app\modules\storage\behaviors\UploadBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "brand".
 *
 * @property int         $id
 * @property int         $position
 * @property string|null $name
 * @property bool        $main
 * @property string|null $description
 * @property string|null $slug
 *
 * @property Product[]   $products
 */
class Brand extends \yii\db\ActiveRecord
{
	
	public $image;
	
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
	public static function tableName()
	{
		return 'brand';
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['description'], 'string'],
			[['name', 'slug'], 'string', 'max' => 255],
			[['image', 'position', 'main'], 'safe'],
		];
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels()
	{
		return [
			'id'          => 'ID',
			'name'        => 'Name',
			'description' => 'Description',
			'slug'        => 'Slug',
			'main'        => 'Visible on Main Page',
		];
	}
	
	/**
	 * Gets query for [[Products]].
	 *
	 * @return \yii\db\ActiveQuery
	 */
	public function getProducts()
	{
		return $this->hasMany(Product::className(), ['brand_id' => 'id']);
	}
	
	public function getImages()
	{
		return $this->hasMany(StorageItem::class, ['model_id' => 'id'])->onCondition(['model_name' => $this->formName()]);
	}
	
	
	public function getThumb()
	{
		return !empty($this->images) ? $this->images[0]->src : '/frontend/images/product-logo.jpg';
	}
	
	public static function getFilter()
	{
		 $query = self::find()
		             ->select(['id', 'name'])
		             ->with('images')
		             ->orderBy(['name' => SORT_ASC]);
		if(isset(\Yii::$app->request->get('ProductSearch')['category_id']) && \Yii::$app->request->get('ProductSearch')['category_id'] != ''){
			$brand_ids = ArrayHelper::getColumn(Product::find()
			                                           ->select(['brand_id'])
			                                           ->where(['category_id' => \Yii::$app->request->get('ProductSearch')['category_id']])
			                                           ->all(), 'brand_id');
			$query->where(['id' => $brand_ids]);
		}
		return ArrayHelper::map($query->all(), 'id', 'name');
	}
}
