<?php

use yii\db\Migration;

/**
 * Class m201011_155023_add_column_to_provider
 */
class m201011_155023_add_column_to_provider extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->addColumn('store', 'api_key', $this->string());
		$this->addColumn('store', 'api_secret', $this->string());
		$this->addColumn('store', 'connected', $this->boolean());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m201011_155023_add_column_to_provider cannot be reverted.\n";

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201011_155023_add_column_to_provider cannot be reverted.\n";

        return false;
    }
    */
}
