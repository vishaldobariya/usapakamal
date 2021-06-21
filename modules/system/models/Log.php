<?php

namespace app\modules\system\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "log".
 *
 * @property int         $id
 * @property int|null    $level
 * @property string|null $category
 * @property float|null  $log_time
 * @property string|null $prefix
 * @property string|null $message
 *
 * @property string      $levelName
 * @property string      $logName
 */
class Log extends \yii\db\ActiveRecord
{
	
	static $levels = [
		1 => 'Error',
		2 => 'Warning',
		3 => 'Info',
		4 => 'Trace',
		5 => 'Profile begin',
		6 => 'Profile end',
		7 => 'Profile',
	];
	
	/**
	 * {@inheritdoc}
	 */
	public static function tableName()
	{
		return 'log';
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['level'], 'default', 'value' => null],
			[['level'], 'integer'],
			[['log_time'], 'number'],
			[['prefix', 'message'], 'string'],
			[['category'], 'string', 'max' => 255],
		];
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels()
	{
		return [
			'id'       => '#',
			'level'    => 'Level',
			'category' => 'Category',
			'log_time' => 'Log Time',
			'prefix'   => 'Prefix',
			'message'  => 'Message',
		];
	}
	
	public function getLevelName()
	{
		return ArrayHelper::getValue(static::$levels, $this->level);
	}
	
	public function getLogName()
	{
		return '#' . $this->id . ' ' . Yii::$app->formatter->asDatetime($this->log_time) . ' (' . $this->levelName . ')';
	}
}
