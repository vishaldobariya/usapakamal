<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%setting}}`.
 */
class m191225_112003_create_setting_table extends Migration
{
	
	public $table = '{{%setting}}';
	
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->createTable($this->table, [
			'id'         => $this->primaryKey(),
			'system_key' => $this->string(),
			'label'      => $this->string(),
			'value'      => $this->text(),
			'protected'  => $this->boolean(),
			'comment'    => $this->string(),
		]);
		
		$this->insert($this->table, [
			'system_key' => 'main_title',
			'label'      => 'Meta-Title Home Page',
			'value'      => Yii::$app->name,
			'protected'  => false,
			'comment'    => 'Title for Home Page',
		]);
		
		$this->insert($this->table, [
			'system_key' => 'main_description',
			'label'      => 'Meta Description Home Page',
			'value'      => Yii::$app->name,
			'protected'  => false,
			'comment'    => 'Seo Description for Home Page',
		]);
		
		$this->insert($this->table, [
			'system_key' => 'main_keywords',
			'label'      => 'Keywords Main Page',
			'value'      => Yii::$app->name,
			'protected'  => false,
			'comment'    => 'Seo Keywords for Home Page',
		]);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropTable($this->table);
	}
}
