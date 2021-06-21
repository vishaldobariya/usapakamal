<?php

use yii\db\Migration;

/**
 * Class m200930_111523_add_column_to_order
 */
class m200930_111523_add_column_to_order extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->addColumn('order', 'store_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200930_111523_add_column_to_order cannot be reverted.\n";

        return false;
    }
    
}
