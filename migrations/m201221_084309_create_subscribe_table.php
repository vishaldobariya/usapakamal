<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%subscribe}}`.
 */
class m201221_084309_create_subscribe_table extends Migration
{
	
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->createTable('{{%subscribe}}', [
			'id'     => $this->primaryKey(),
			'email'  => $this->string(),
			'active' => $this->boolean(),
		]);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropTable('{{%subscribe}}');
	}
}
