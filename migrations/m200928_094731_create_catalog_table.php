<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%catalog}}`.
 */
class m200928_094731_create_catalog_table extends Migration
{
	
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->createTable('{{%catalog}}', [
			'id'   => $this->primaryKey(),
			'name' => $this->string(),
			'slug' => $this->string(),
		]);
		
		$this->addColumn('product', 'catalogs', $this->string());
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropTable('{{%catalog}}');
	}
}
