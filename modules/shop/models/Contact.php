<?php

namespace app\modules\shop\models;

use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "contact".
 *
 * @property int         $id
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $text
 */
class Contact extends \yii\db\ActiveRecord
{
	
	/**
	 * {@inheritdoc}
	 */
	public static function tableName()
	{
		return 'contact';
	}
	
	/**
	 * @return array
	 */
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
	public function rules()
	{
		return [
			[['created_at'], 'default', 'value' => null],
			[['created_at'], 'integer'],
			[['first_name', 'last_name', 'email', 'phone', 'text'], 'string', 'max' => 255],
			[['first_name', 'email', 'phone', 'text'], 'required'],
			['email', 'email'],
			['last_name', 'safe'],
			[['phone'], 'match', 'pattern' => '/^(\([0-9]{3}\) |[0-9]{3}-)[0-9]{3}-[0-9]{4}$/'],
		];
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels()
	{
		return [
			'id'         => 'ID',
			'first_name' => 'First Name',
			'last_name'  => 'Last Name',
			'email'      => 'Email',
			'phone'      => 'Phone',
			'text'       => 'Text',
		];
	}
}
