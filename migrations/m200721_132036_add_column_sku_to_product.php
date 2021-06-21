<?php

use yii\db\Migration;
use app\modules\shop\models\Product;

/**
 * Class m200721_132036_add_column_sku_to_product
 */
class m200721_132036_add_column_sku_to_product extends Migration
{
	
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->addColumn('{{%product}}', 'sku', $this->string());
		$this->addColumn('{{%product}}', 'cap', $this->integer());
		$products = Product::find()->all();
		$i = 1;
		foreach($products as $product) {
			$product->sku ='RBRA1000000'.$i;
			$product->save();
			$i++;
		}
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		echo "m200721_132036_add_column_sku_to_product cannot be reverted.\n";
		
		return false;
	}
	
	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m200721_132036_add_column_sku_to_product cannot be reverted.\n";

		return false;
	}
	*/
}
