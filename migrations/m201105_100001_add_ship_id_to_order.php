<?php

use yii\db\Migration;

/**
 * Class m201105_100001_add_ship_id_to_order
 */
class m201105_100001_add_ship_id_to_order extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->addColumn('order', 'ship_id', $this->string());
		$this->addColumn('order', 'ship_date', $this->string());
		$this->addColumn('order', 'tracking_number', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m201105_100001_add_ship_id_to_order cannot be reverted.\n";

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201105_100001_add_ship_id_to_order cannot be reverted.\n";

        return false;
    }
    */
}
