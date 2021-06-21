<?php

use yii\db\Migration;

/**
 * Class m200818_162345_stores_tables
 */
class m200818_162345_stores_tables extends Migration
{
	
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->createTable('{{%store}}', [
			'id'      => $this->primaryKey(),
			'user_id' => $this->integer(),
			'name'    => $this->string(),
		
		]);
		
		$this->createTable('{{%store_product}}', [
			'id'           => $this->primaryKey(),
			'store_id'     => $this->integer(),
			'sku'          => $this->string(),
			'product_name' => $this->string(),
			'price'        => $this->float(2),
			'shipping'     => $this->string(),
			'note'         => $this->text(),
		]);
		
		$this->createTable('{{%price}}', [
			'id'         => $this->primaryKey(),
			'product_id' => $this->integer(),
			'price'      => $this->float(2),
			'created_at' => $this->integer(),
		]);
		
		$this->addForeignKey('fk-store-product-store', '{{%store_product}}', 'store_id', '{{%store}}', 'id', 'CASCADE', 'CASCADE');
		
		$this->addColumn('{{%store_product}}', 'old_price', $this->float(2));
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		return true;
	}
	
}
