<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%storge}}`.
 */
class m200709_130843_create_storge_table extends Migration
{
	
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->createTable('{{%storage}}', [
			'id'         => $this->primaryKey(),
			'model_id'   => $this->integer(),
			'model_name' => $this->string(),
			'path'       => $this->string()->notNull(),
			'base_url'   => $this->string(),
			'type'       => $this->string(),
			'size'       => $this->integer(),
			'name'       => $this->string(),
			'created_at' => $this->integer(),
		
		]);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropTable('{{%storage}}');
	}
}
