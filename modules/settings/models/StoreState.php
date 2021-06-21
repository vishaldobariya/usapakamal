<?php

namespace app\modules\settings\models;

use Yii;

/**
 * This is the model class for table "store_state".
 *
 * @property int $id
 * @property int|null $state_id
 * @property int|null $store_id
 */
class StoreState extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'store_state';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['state_id', 'store_id'], 'default', 'value' => null],
            [['state_id', 'store_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'state_id' => 'State ID',
            'store_id' => 'Store ID',
        ];
    }
}
