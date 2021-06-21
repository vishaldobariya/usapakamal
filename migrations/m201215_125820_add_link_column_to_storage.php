<?php

use yii\db\Migration;

/**
 * Class m201215_125820_add_link_column_to_storage
 */
class m201215_125820_add_link_column_to_storage extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->addColumn('{{%storage}}','link', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m201215_125820_add_link_column_to_storage cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201215_125820_add_link_column_to_storage cannot be reverted.\n";

        return false;
    }
    */
}
