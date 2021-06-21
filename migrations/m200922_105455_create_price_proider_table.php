<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%price_proider}}`.
 */
class m200922_105455_create_price_proider_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%price_provider}}', [
	        'id'         => $this->primaryKey(),
	        'product_id' => $this->integer(),
	        'price'      => $this->float(2),
	        'created_at' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%price_provider}}');
    }
}
