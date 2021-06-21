<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%shipping_adress}}`.
 */
class m210405_124911_create_shipping_adress_table extends Migration
{
	
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->createTable('{{%shipping_adress}}', [
			'id'          => $this->primaryKey(),
			'address'     => $this->string(),
			'address_two' => $this->string(),
			'city'        => $this->string(),
			'state'       => $this->string(),
			'zip'         => $this->string(),
			'is_default'  => $this->boolean(),
			'user_id'     => $this->integer(),
		]);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropTable('{{%shipping_adress}}');
	}
}
