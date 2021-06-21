<?php

use yii\db\Migration;

/**
 * Class m210126_112940_add_columns_to_order
 */
class m210126_112940_add_columns_to_order extends Migration
{
	
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->addColumn('order', 'note_name', $this->string());
		$this->addColumn('order', 'note_email', $this->string());
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		echo "m210126_112940_add_columns_to_order cannot be reverted.\n";
		
		return false;
	}
	
	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m210126_112940_add_columns_to_order cannot be reverted.\n";

		return false;
	}
	*/
}
