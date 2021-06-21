<?php

use yii\db\Migration;

/**
 * Class m201026_183339_create_alter_columns_order
 */
class m201026_183339_create_alter_columns_order extends Migration
{
	
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->dropColumn('order', 'is_fedex');
		$this->dropColumn('order', 'fedex_price');
		$this->addColumn('order', 'ship_name', $this->string());
		$this->addColumn('order', 'ship_code', $this->string());
		$this->addColumn('order', 'ship_price', $this->string());
		$this->addColumn('order', 'ship_service_code', $this->string());
		$this->addColumn('order', 'ship_order_key', $this->string());
		$this->addColumn('order', 'total_provider_cost', $this->float(2));
		$this->addColumn('order_item', 'provider_price', $this->float(2));
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
		echo "m201026_183339_create_alter_columns_order cannot be reverted.\n";

		return false;
	}
	*/
}
