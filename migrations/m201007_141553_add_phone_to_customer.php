<?php

use yii\db\Migration;

/**
 * Class m201007_141553_add_phone_to_customer
 */
class m201007_141553_add_phone_to_customer extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->addColumn('customer', 'phone', $this->string());
		$this->addColumn('customer', 'state', $this->string());
		$this->addColumn('customer', 'billing_address', $this->string());
		$this->addColumn('customer', 'billing_address_two', $this->string());
		$this->addColumn('customer', 'billing_city', $this->string());
		$this->addColumn('customer', 'billing_country', $this->string());
		$this->addColumn('customer', 'billing_state', $this->string());
		$this->addColumn('customer', 'billing_zip', $this->string());
		$this->addColumn('customer', 'billing_phone', $this->string());
		$this->addColumn('customer', 'billing_first_name', $this->string());
		$this->addColumn('customer', 'billing_last_name', $this->string());
		$this->addColumn('order', 'is_fedex', $this->boolean());
		$this->addColumn('order', 'fedex_price', $this->float(2));
		$this->addColumn('order', 'discount_code', $this->string());
		$this->addColumn('order', 'discount_percent', $this->float(2));
		
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201007_141553_add_phone_to_customer cannot be reverted.\n";

        return false;
    }
    */
}
