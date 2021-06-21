<?php

namespace app\modules\subscribe\models;

/**
 * This is the model class for table "subscribe".
 *
 * @property int         $id
 * @property int         $status
 * @property string|null $email
 * @property bool|null   $active
 */
class Subscribe extends \yii\db\ActiveRecord
{
	
	const STATUSES = [
		0 => 'Do not send',
		1 => 'Active',
		2 => 'Abandoned Checkout',
	];
	
	/**
	 * {@inheritdoc}
	 */
	public static function tableName()
	{
		return 'subscribe';
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['active'], 'boolean'],
			[['email'], 'string', 'max' => 255],
			['email', 'email'],
			['email', 'required'],
			['active', 'default', 'value' => true],
			['status', 'integer'],
			['status', 'default', 'value' => 1],
		];
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels()
	{
		return [
			'id'     => 'ID',
			'email'  => 'Email',
			'active' => 'Active',
		];
	}
	
	public function getStatus()
	{
		return $this->status ? self::STATUSES[$this->status] : '';
	}
}
