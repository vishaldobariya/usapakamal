<?php

namespace app\modules\shop\models;

use Yii;
use yii\behaviors\SluggableBehavior;

/**
 * This is the model class for table "catalog".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $slug
 */
class Catalog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'catalog';
    }
	public function behaviors()
	{
		return [
			[
				'class'         => SluggableBehavior::class,
				'attribute'     => 'name',
				'slugAttribute' => 'slug',
				'ensureUnique'  => true,
			],
		];
	}

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'slug'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'slug' => 'Slug',
        ];
    }
}
