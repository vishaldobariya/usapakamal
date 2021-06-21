<?php

use yii\db\Migration;

/**
 * Class m201103_120201_add_age_column_to_product
 */
class m201103_120201_add_age_column_to_product extends Migration
{
	
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->addColumn('product', 'age', $this->float(2));
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		echo "m201103_120201_add_age_column_to_product cannot be reverted.\n";
		
		return false;
	}
	
	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m201103_120201_add_age_column_to_product cannot be reverted.\n";

		return false;
	}
	*/
}
