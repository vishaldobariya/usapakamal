<?php

namespace app\modules\shop\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\behaviors\SluggableBehavior;
use app\modules\storage\models\StorageItem;
use app\modules\storage\behaviors\UploadBehavior;

/**
 * This is the model class for table "category".
 *
 * @property int         $id
 * @property string|null $name
 * @property string|null $description
 * @property string|null $slug
 * @property int|null    $status
 * @property int|null    $parent_id
 *
 * @property Product[]   $products
 */
class Category extends \yii\db\ActiveRecord
{
	
	public $image;
	
	public $product_ids;
	
	/**
	 * {@inheritdoc}
	 */
	public static function tableName()
	{
		return 'category';
	}
	
	const STATUSES = [
		0 => 'Draft',
		1 => 'Published',
	];
	
	/**
	 * @return array
	 */
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
			[['description'], 'string'],
			[['name', 'slug'], 'string', 'max' => 255],
			[['parent_id', 'status'], 'safe'],
			[['name'], 'required'],
		
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
		];
	}
	
	/**
	 * Gets query for [[Products]].
	 *
	 * @return \yii\db\ActiveQuery
	 */
	public function getProducts()
	{
		return $this->hasMany(Product::className(), ['category_id' => 'id']);
	}
	
	/**
	 * Gets query for [[Products]].
	 *
	 * @return \yii\db\ActiveQuery
	 */
	public function getSubProducts()
	{
		return $this->hasMany(Product::className(), ['sub_category_id' => 'id']);
	}
	
	public function getImages()
	{
		return $this->hasMany(StorageItem::class, ['model_id' => 'id'])->onCondition(['model_name' => $this->formName()]);
	}
	
	
	public function getThumb()
	{
		return !empty($this->images) ? $this->images[0]->src : '/frontend/images/product-logo.jpg';
	}
	
	public function getParent()
	{
		return $this->hasOne(self::class, ['id' => 'parent_id']);
	}
	
	public function getChildren()
	{
		return $this->hasMany(self::class, ['parent_id' => 'id']);
	}
	
	public static function getFilter()
	{
		$query = self::find()
		             ->select(['id', 'name'])
		             ->with('images')
		            // ->where(['status' => 1])
		             ->where(['parent_id' => null])
		             ->orderBy(['name' => SORT_ASC]);
		
		return ArrayHelper::map($query->all(), 'id', 'name');
	}
	
	public static function getSubFilter()
	{
		$query = self::find()
		             ->select(['id', 'name'])
		            // ->where(['status' => 1])
		             ->where(['is not', 'parent_id', null])
		             ->with('images')
		             ->orderBy(['name' => SORT_ASC]);
		if(isset(\Yii::$app->request->get('ProductSearch')['category_id']) && \Yii::$app->request->get('ProductSearch')['category_id'] != '') {
			$query->where(['parent_id' => Yii::$app->request->get('ProductSearch')['category_id']]);
		}
		
		return ArrayHelper::map($query->all(), 'id', 'name');
	}
}
