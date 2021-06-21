<?php
/**
 * *
 *  * @link      http://industrialax.com/
 *  * @email     xristmas365@gmail.com
 *  * @copyright Copyright (c) 2020 INDUSTRIALAX SOLUTIONS LLC
 *  * @license   https://industrialax.com/license
 *
 *
 */

namespace app\modules\storage\models;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "ax_storage_item".
 *
 * @property int          $id
 * @property int          $model_id
 * @property int          $model_name
 * @property string       $path
 * @property string       $base_url
 * @property string       $type
 * @property string       $link
 * @property int          $size
 * @property int          $position
 * @property string       $name
 * @property int          $created_at
 * @property-read  string $src
 */
class StorageItem extends ActiveRecord
{
	
	/**
	 * {@inheritdoc}
	 */
	public static function tableName()
	{
		return '{{%storage}}';
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['model_id', 'model_name', 'size', 'created_at'], 'default', 'value' => null],
			[['model_id', 'size', 'created_at'], 'integer'],
			[['path'], 'required'],
			[['path', 'base_url', 'model_name', 'type', 'name'], 'string', 'max' => 255],
			[['link', 'position'], 'safe'],
		];
	}
	
	public function behaviors()
	{
		return [
			[
				'class'              => TimestampBehavior::class,
				'updatedAtAttribute' => false,
			],
		];
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels()
	{
		return [
			'id'         => 'ID',
			'model_id'   => 'Model ID',
			'model_name' => 'Model Name',
			'path'       => 'Path',
			'base_url'   => 'Base Url',
			'type'       => 'Type',
			'size'       => 'Size',
			'name'       => 'Name',
			'created_at' => 'Created At',
		];
	}
	
	public function getSrc()
	{
		return $this->base_url . '/' . $this->path;
	}
	
}
