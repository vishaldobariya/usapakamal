<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%product}}`.
 */
class m200709_125145_create_product_table extends Migration
{
	
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->createTable('{{%brand}}', [
			'id'          => $this->primaryKey(),
			'name'        => $this->string(),
			'description' => $this->text(),
			'slug'        => $this->string(),
		]);
		
		$this->createTable('{{%product}}', [
			'id'          => $this->primaryKey(),
			'name'        => $this->string(),
			'description' => $this->text(),
			'price'       => $this->float(2),
			'category_id' => $this->integer(),
			'brand_id'    => $this->integer(),
			'slug'        => $this->string(),
			'shipping'    => $this->text(),
			'price_min'   => $this->float(2),
			'price_max'   => $this->float(2),
			'available'   => $this->boolean(),
		
		]);
		
		$this->addForeignKey('fk-product-category_id-category-id', '{{%product}}', 'category_id', '{{%category}}', 'id', 'SET NULL', 'NO ACTION');
		$this->addForeignKey('fk-product-brand_id-brand-id', '{{%product}}', 'brand_id', '{{%brand}}', 'id', 'SET NULL', 'NO ACTION');
		
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropForeignKey('fk-product-category_id-category-id', '{{%product}}');
		$this->dropForeignKey('fk-product-brand_id-brand-id', '{{%product}}');
		$this->dropTable('{{%brand}}');
		$this->dropTable('{{%product}}');
	}
}
