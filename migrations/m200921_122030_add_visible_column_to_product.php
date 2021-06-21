<?php

use yii\db\Migration;

/**
 * Class m200921_122030_add_visible_column_to_product
 */
class m200921_122030_add_visible_column_to_product extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->addColumn('product', 'visible', $this->boolean()->defaultValue(true));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200921_122030_add_visible_column_to_product cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200921_122030_add_visible_column_to_product cannot be reverted.\n";

        return false;
    }
    */
}
