<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%customer}}`.
 */
class m200724_135240_create_customer_table extends Migration
{
	
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->createTable('{{%customer}}', [
			'id'         => $this->primaryKey(),
			'first_name' => $this->string(),
			'last_name'  => $this->string(),
			'email'      => $this->string(),
			'address'    => $this->string(),
			'adress_two' => $this->string(),
			'city'       => $this->string(),
			'contry'     => $this->string(),
			'zip'        => $this->string(),
		]);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropTable('{{%customer}}');
	}
}
