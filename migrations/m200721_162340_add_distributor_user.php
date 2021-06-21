<?php

use yii\db\Migration;

/**
 * Class m200721_162340_add_distributor_user
 */
class m200721_162340_add_distributor_user extends Migration
{
	
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->insert('{{%user}}', [
			'id'         => 3,
			'email'      => 'distributor@distributor.com',
			'password'   => Yii::$app->security->generatePasswordHash('distributor*&'),
			'auth_key'   => Yii::$app->security->generateRandomString(),
			'first_name' => 'Distributor',
			'last_name'  => 'G',
			'role'       => 'distributor',
			'created_at' => time(),
			'updated_at' => time(),
			'confirmed'  => true,
		]);
		
		$this->insert('{{%theme}}', ['id' => 3, 'user_id' => 3]);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		echo "m200721_162340_add_distributor_user cannot be reverted.\n";
		
		return false;
	}
	
	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m200721_162340_add_distributor_user cannot be reverted.\n";

		return false;
	}
	*/
}
