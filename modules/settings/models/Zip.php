<?php

namespace app\modules\settings\models;

/**
 * This is the model class for table "zip".
 *
 * @property int         $id
 * @property string|null $zipcode
 * @property string|null $state
 * @property string|null $city
 * @property float|null  $tax
 * @property bool|null   $active
 */
class Zip extends \yii\db\ActiveRecord
{
	
	/**
	 * {@inheritdoc}
	 */
	public static function tableName()
	{
		return 'zip';
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['active'], 'boolean'],
			[['zipcode', 'state', 'city'], 'string', 'max' => 255],
			[['tax'], 'number'],
		];
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels()
	{
		return [
			'id'      => 'ID',
			'zipcode' => 'Zipcode',
			'state'   => 'State',
			'active'  => 'Active',
		];
	}
}
