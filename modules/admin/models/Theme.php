<?php

namespace app\modules\admin\models;

use yii\db\ActiveRecord;
use app\modules\user\models\User;

/**
 * This is the model class for table "theme".
 *
 * @property int         $id
 * @property int         $user_id
 * @property string|null $sidebar_position
 * @property string|null $version
 * @property string|null $header_position
 * @property string|null $sidebar_style
 * @property string|null $layout
 * @property string|null $container_layout
 * @property string|null $navheader_bg
 * @property string|null $header_bg
 * @property string|null $sidebar_bg
 *
 * @property User        $user
 */
class Theme extends ActiveRecord
{
	
	/**
	 * {@inheritdoc}
	 */
	public static function tableName()
	{
		return 'theme';
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['user_id'], 'required'],
			[['user_id'], 'default', 'value' => null],
			[['user_id'], 'integer'],
			[['sidebar_position', 'version', 'header_position', 'sidebar_style', 'layout', 'container_layout', 'navheader_bg', 'header_bg', 'sidebar_bg'], 'string', 'max' => 255],
			[['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
		];
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels()
	{
		return [
			'id'               => 'ID',
			'user_id'          => 'User ID',
			'sidebar_position' => 'Sidebar Position',
			'version'          => 'Version',
			'header_position'  => 'Header Position',
			'sidebar_style'    => 'Sidebar Style',
			'layout'           => 'Layout',
			'container_layout' => 'Container Layout',
			'navheader_bg'     => 'Navheader Bg',
			'header_bg'        => 'Header Bg',
			'sidebar_bg'       => 'Sidebar Bg',
		];
	}
	
	/**
	 * Gets query for [[User]].
	 *
	 * @return \yii\db\ActiveQuery
	 */
	public function getUser()
	{
		return $this->hasOne(User::class, ['id' => 'user_id']);
	}
	
	public static function createTheme($user_id)
	{
		$theme = new self;
		$theme->user_id = $user_id;
		$theme->version = 'light';
		$theme->navheader_bg = 'color_6';
		$theme->sidebar_position = 'fixed';
		$theme->header_position = 'fixed';
		$theme->sidebar_style = 'full';
		$theme->layout = 'vertical';
		$theme->container_layout = 'wide';
		$theme->header_bg = 'color_1';
		$theme->sidebar_bg = 'color_1';
		$theme->save();
	}
}
