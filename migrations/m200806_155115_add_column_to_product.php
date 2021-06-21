<?php

use yii\db\Migration;

/**
 * Class m200806_155115_add_column_to_product
 */
class m200806_155115_add_column_to_product extends Migration
{
	
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->addColumn('{{%product}}', 'sale', $this->boolean()->defaultValue(false));
		$this->addColumn('{{%product}}', 'sale_price', $this->float(2));
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		echo "m200806_155115_add_column_to_product cannot be reverted.\n";
		
		return false;
	}
	
	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m200806_155115_add_column_to_product cannot be reverted.\n";

		return false;
	}
	*/
}
