<?php

use yii\db\Migration;

/**
 * Class m210607_140949_add_columns_to_settings_for_ship
 */
class m210607_140949_add_columns_to_settings_for_ship extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
         $this->insert('setting', [
            'system_key' => 'two_days_ship',
            'label'      => '2 days ship',
            'value'      => 0,
            'protected'  => false,
            'comment'    => '',
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210607_140949_add_columns_to_settings_for_ship cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210607_140949_add_columns_to_settings_for_ship cannot be reverted.\n";

        return false;
    }
    */
}
