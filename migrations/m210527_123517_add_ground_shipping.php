<?php

use yii\db\Migration;

/**
 * Class m210527_123517_add_ground_shipping
 */
class m210527_123517_add_ground_shipping extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
	    $this->insert('{{%setting}}', [
		    'system_key' => 'ship_one_bottle_ground',
		    'label'      => 'Price per bottle ground',
		    'value'      => '14.99',
		    'protected'  => false,
	    ]);
	    $this->insert('{{%setting}}', [
		    'system_key' => 'ship_more_bottle_ground',
		    'label'      => 'Price more bottle ground',
		    'value'      => '30',
		    'protected'  => false,
	    ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210527_123517_add_ground_shipping cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210527_123517_add_ground_shipping cannot be reverted.\n";

        return false;
    }
    */
}
