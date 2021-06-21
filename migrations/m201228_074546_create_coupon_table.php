<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%coupon}}`.
 */
class m201228_074546_create_coupon_table extends Migration
{
	
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->createTable('{{%coupon}}', [
			'id'          => $this->primaryKey(),
			'name'        => $this->string(),
			'description' => $this->text(),
			'percent'     => $this->float(2),
			'status'      => $this->smallInteger(),
		]);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropTable('{{%coupon}}');
	}
}
