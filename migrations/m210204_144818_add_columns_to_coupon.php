<?php

use yii\db\Migration;

/**
 * Class m210204_144818_add_columns_to_coupon
 */
class m210204_144818_add_columns_to_coupon extends Migration
{
	
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->addColumn('coupon', 'is_percent', $this->boolean());
		$this->addColumn('coupon', 'is_usd', $this->boolean());
		$this->addColumn('coupon', 'start_date', $this->integer());
		$this->addColumn('coupon', 'end_date', $this->integer());
		$this->addColumn('coupon', 'is_products_with_ship', $this->boolean());
		$this->addColumn('coupon', 'is_only_products', $this->boolean());
		$this->addColumn('coupon', 'is_only_ship', $this->boolean());
		$this->addColumn('coupon', 'min_cart_price', $this->float(2));
		$this->dropColumn('coupon', 'percent');
		$this->addColumn('coupon', 'value', $this->float(2));
		$this->createTable('coupon_user', [
			'id'        => $this->primaryKey(),
			'email'     => $this->string(),
			'count'     => $this->integer(),
			'coupon_id' => $this->integer(),
		]);
		
		$this->addForeignKey('fk-coupon_user-coupon-id-coupon-id', 'coupon_user', 'coupon_id', 'coupon', 'id', 'CASCADE');
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		echo "m210204_144818_add_columns_to_coupon cannot be reverted.\n";
		
		return false;
	}
	
	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m210204_144818_add_columns_to_coupon cannot be reverted.\n";

		return false;
	}
	*/
}
