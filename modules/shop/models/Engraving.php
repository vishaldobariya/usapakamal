<?php

namespace app\modules\shop\models;

use yz\shoppingcart\CartPositionTrait;
use yz\shoppingcart\CartPositionInterface;
use app\modules\storage\models\StorageItem;
use app\modules\storage\behaviors\UploadBehavior;

/**
 * This is the model class for table "engraving".
 *
 * @property int         $id
 * @property int|null    $order_item_id
 * @property int|null    $qty
 * @property string|null $front_line_1
 * @property string|null $front_line_2
 * @property string|null $front_line_3
 * @property string|null $back_line_1
 * @property string|null $back_line_2
 * @property string|null $back_line_3
 * @property bool|null   $front_price
 * @property bool|null   $back_price
 */
class Engraving extends \yii\db\ActiveRecord implements CartPositionInterface
{
	
	use CartPositionTrait;
	
	public $image_front;
	
	public $image_back;
	
	public $product_id;
	
	public $key;
	
	public $data = [];
	
	/**
	 * {@inheritdoc}
	 */
	public static function tableName()
	{
		return 'engraving';
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['order_item_id'], 'default', 'value' => null],
			[['order_item_id','qty'], 'integer'],
			[['front_line_1', 'front_line_2', 'front_line_3', 'back_line_1', 'back_line_2', 'back_line_3'], 'string', 'max' => 255],
			[['front_price', 'back_price'], 'safe'],
		];
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels()
	{
		return [
			'id'            => 'ID',
			'order_item_id' => 'Order Item ID',
			'front_line_1'  => 'Front Line 1',
			'front_line_2'  => 'Front Line 2',
			'front_line_3'  => 'Front Line 3',
			'back_line_1'   => 'Back Line 1',
			'back_line_2'   => 'Back Line 2',
			'back_line_3'   => 'Back Line 3',
		];
	}
	
	public function behaviors()
	{
		return [
			[
				'class'            => UploadBehavior::class,
				'multiple'         => true,
				'attribute'        => 'image_front',
				'uploadRelation'   => 'imageFront',
				'filesStorage'     => 'fileStorage',
				'pathAttribute'    => 'path',
				'baseUrlAttribute' => 'base_url',
				'typeAttribute'    => 'type',
				'sizeAttribute'    => 'size',
				'nameAttribute'    => 'name',
				'orderAttribute'   => false,
			],
			//[
			//	'class'            => UploadBehavior::class,
			//	'multiple'         => true,
			//	'attribute'        => 'image_back',
			//	'uploadRelation'   => 'imageBack',
			//	'filesStorage'     => 'fileStorage',
			//	'pathAttribute'    => 'path',
			//	'baseUrlAttribute' => 'base_url',
			//	'typeAttribute'    => 'type',
			//	'sizeAttribute'    => 'size',
			//	'nameAttribute'    => 'name',
			//	'orderAttribute'   => false,
			//],
		
		];
	}
	
	public function getImageFront()
	{
		return $this->hasMany(StorageItem::class, ['model_id' => 'id'])->onCondition(['model_name' => 'Engraving Front']);
	}
	
	public function getImageBack()
	{
		return $this->hasMany(StorageItem::class, ['model_id' => 'id'])->onCondition(['model_name' => 'Engraving Back']);
	}
	
	public function getPrice()
	{
		
		return (float)\Yii::$app->settings->front_engraving;
	}
	
	/**
	 * @return string
	 */
	public function getId()
	{
		return $this->key;
	}
	
}
