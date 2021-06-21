<?php

use yii\db\Migration;

/**
 * Class m200724_163006_chande_column_order_item_product_id
 */
class m200724_163006_chande_column_order_item_product_id extends Migration
{
	
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->dropColumn('{{%order_item}}', 'product_id');
		$this->addColumn('{{%order_item}}', 'product_id', $this->integer());
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		echo "m200724_163006_chande_column_order_item_product_id cannot be reverted.\n";
		
		return false;
	}
	
	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m200724_163006_chande_column_order_item_product_id cannot be reverted.\n";

		return false;
	}
	*/
}
