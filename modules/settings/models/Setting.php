<?php

namespace app\modules\settings\models;

/**
 * This is the model class for table "setting".
 *
 * @property int         $id
 * @property string|null $system_key
 * @property string|null $label
 * @property string|null $value
 * @property bool|null   $protected
 * @property string|null $comment
 */
class Setting extends \yii\db\ActiveRecord
{
	
	/**
	 * {@inheritdoc}
	 */
	public static function tableName()
	{
		return 'setting';
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['protected'], 'boolean'],
			[['system_key', 'label', 'value', 'comment'], 'string', 'max' => 255],
		];
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels()
	{
		return [
			'id'         => 'ID',
			'system_key' => 'System Key',
			'label'      => 'Label',
			'value'      => 'Value',
			'protected'  => 'Protected',
			'comment'    => 'Comment',
		];
	}
}
