<?php

use yii\db\Migration;
use app\modules\settings\models\Zip;

/**
 * Handles the creation of table `{{%zip}}`.
 */
class m201228_104648_create_zip_table extends Migration
{
	
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->createTable('{{%zip}}', [
			'id'      => $this->primaryKey(),
			'zipcode' => $this->string(),
			'state'   => $this->string(),
			'active'  => $this->boolean(),
		]);
		
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropTable('{{%zip}}');
	}
}
