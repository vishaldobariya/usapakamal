<?php

use yii\db\Migration;

/**
 * Class m210122_085035_add_column_to_customer
 */
class m210122_085035_add_column_to_customer extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->addColumn('customer', 'user_id', $this->integer());
		$this->addColumn('order', 'user_id', $this->integer());
	    $this->insert('{{%setting}}', [
		    'system_key' => 'tax',
		    'label'      => 'Tax',
		    'value'      => '7.75',
		    'protected'  => false,
		    'comment'    => 'without percent, separate characters with a dot',
	    ]);
	    $this->addColumn('order', 'tax', $this->float(2));
	    $this->addColumn('order', 'tax_percent', $this->float(2));
	
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        //echo "m210122_085035_add_column_to_customer cannot be reverted.\n";

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210122_085035_add_column_to_customer cannot be reverted.\n";

        return false;
    }
    */
}
