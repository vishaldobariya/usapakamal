<?php

use yii\db\Migration;

/**
 * Class m201224_120412_add_columns_to_setting
 */
class m201224_120412_add_columns_to_setting extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
	    $this->insert('setting', [
		    'system_key' => 'ship_one_bottle',
		    'label'      => 'Price per bottle',
		    'value'      => 14.99,
		    'protected'  => false,
		    'comment'    => '',
	    ]);
	    $this->insert('setting', [
		    'system_key' => 'ship_more_bottle',
		    'label'      => 'For every next bottle',
		    'value'      => 3.00,
		    'protected'  => false,
		    'comment'    => '',
	    ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m201224_120412_add_columns_to_setting cannot be reverted.\n";

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201224_120412_add_columns_to_setting cannot be reverted.\n";

        return false;
    }
    */
}
