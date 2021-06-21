<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%contact}}`.
 */
class m200724_155003_create_contact_table extends Migration
{
	
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->createTable('{{%contact}}', [
			'id'         => $this->primaryKey(),
			'first_name' => $this->string(),
			'last_name'  => $this->string(),
			'email'      => $this->string(),
			'phone'      => $this->string(),
			'text'       => $this->string(),
			'created_at' => $this->integer(),
		]);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropTable('{{%contact}}');
	}
}
