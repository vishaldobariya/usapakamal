<?php

use yii\db\Migration;

/**
 * Class m200902_142612_add_columns_to_store_product
 */
class m200902_142612_add_columns_to_store_product extends Migration
{
	
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->addColumn('{{%store_product}}', 'connected', $this->boolean());
		$this->addColumn('{{%store_product}}', 'abv', $this->float(2));
		$this->addColumn('{{%store_product}}', 'vol', $this->integer());
		$this->addColumn('{{%store_product}}', 'cap', $this->integer());
		$this->addColumn('{{%store_product}}', 'product_id', $this->integer());
		$this->addColumn('{{%store_product}}', 'updated_at', $this->integer());
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		echo "m200902_142612_add_columns_to_store_product cannot be reverted.\n";
		
		return true;
	}
	
	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m200902_142612_add_columns_to_store_product cannot be reverted.\n";

		return false;
	}
	*/
}
