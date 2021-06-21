<?php

use yii\db\Migration;

/**
 * Class m191202_131024_create_user
 */
class m191202_131024_create_user extends Migration
{
	
	public $table = '{{%user}}';
	
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->createTable($this->table, [
			'id'            => $this->primaryKey(),
			'email'         => $this->string()->notNull()->unique(),
			'password'      => $this->string(),
			'blocked'       => $this->boolean()->notNull()->defaultValue(false),
			'confirmed'     => $this->boolean()->notNull()->defaultValue(false),
			'auth_key'      => $this->string(),
			'role'          => $this->string()->notNull()->defaultValue('user'),
			'first_name'    => $this->string()->notNull(),
			'last_name'     => $this->string(),
			'phone'         => $this->string(),
			'address'       => $this->string(),
			'city'          => $this->string(),
			'state'         => $this->string(),
			'zip'           => $this->integer(),
			'bio'           => $this->text(),
			'created_at'    => $this->integer(),
			'updated_at'    => $this->integer(),
			'last_login_at' => $this->integer(),
		]);
		
		/**
		 * Admin
		 */
		$this->insert($this->table, [
			'email'      => 'admin@admin.com',
			'password'   => Yii::$app->security->generatePasswordHash('basic*&'),
			'auth_key'   => Yii::$app->security->generateRandomString(),
			'first_name' => 'Admin',
			'last_name'  => 'G',
			'role'       => 'admin',
			'created_at' => time(),
			'updated_at' => time(),
			'confirmed'  => true,
		]);
		
		/**
		 * User
		 */
		$this->insert($this->table, [
			'email'      => 'demo@demo.com',
			'password'   => Yii::$app->security->generatePasswordHash('demo'),
			'auth_key'   => Yii::$app->security->generateRandomString(),
			'first_name' => 'Demo',
			'last_name'  => 'User',
			'created_at' => time(),
			'updated_at' => time(),
			'confirmed'  => true,
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
