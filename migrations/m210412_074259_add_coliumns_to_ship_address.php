<?php

use yii\db\Migration;

/**
 * Class m210412_074259_add_coliumns_to_ship_address
 */
class m210412_074259_add_coliumns_to_ship_address extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
    	$this->addColumn('shipping_adress', 'first_name', $this->string());
    	$this->addColumn('shipping_adress', 'last_name', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210412_074259_add_coliumns_to_ship_address cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210412_074259_add_coliumns_to_ship_address cannot be reverted.\n";

        return false;
    }
    */
}
