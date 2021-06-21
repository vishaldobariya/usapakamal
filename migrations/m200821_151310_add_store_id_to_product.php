<?php

use yii\db\Migration;

/**
 * Class m200821_151310_add_store_id_to_product
 */
class m200821_151310_add_store_id_to_product extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->addColumn('product', 'store_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200821_151310_add_store_id_to_product cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200821_151310_add_store_id_to_product cannot be reverted.\n";

        return false;
    }
    */
}
