<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%engraving}}`.
 */
class m210121_074534_create_engraving_table extends Migration
{
	
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->createTable('{{%engraving}}', [
			'id'            => $this->primaryKey(),
			'order_item_id' => $this->integer(),
			'front_line_1'  => $this->string(),
			'front_line_2'  => $this->string(),
			'front_line_3'  => $this->string(),
			'back_line_1'   => $this->string(),
			'back_line_2'   => $this->string(),
			'back_line_3'   => $this->string(),
			'front_price'   => $this->float(2),
			'back_price'    => $this->float(2),
		]);
		
		$this->insert('{{%setting}}', [
			'system_key' => 'front_engraving',
			'label'      => 'Price for Front Engraving',
			'value'      => '39.00',
			'protected'  => false,
			'comment'    => 'only numbers',
		]);
		$this->insert('{{%setting}}', [
			'system_key' => 'back_engraving',
			'label'      => 'Price for Back Engraving',
			'value'      => '20.00',
			'protected'  => false,
			'comment'    => 'only numbers',
		]);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropTable('{{%engraving}}');
	}
}
