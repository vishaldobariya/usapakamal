<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%order}}`.
 */
class m200724_140323_create_order_table extends Migration
{
	
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->createTable('{{%order}}', [
			'id'             => $this->primaryKey(),
			'customer_id'    => $this->integer(),
			'created_at'     => $this->integer(),
			'total_cost'     => $this->float(2),
			'transaction_id' => $this->text(),
			'status'         => $this->smallInteger(),
		
		]);
		
		$this->createTable('{{%order_item}}', [
			'id'            => $this->primaryKey(),
			'order_id'      => $this->integer(),
			'product_price' => $this->float(2),
			'product_id'    => $this->text(),
			'qty'           => $this->integer(),
		]);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropTable('{{%order}}');
	}
}
