<?php

use yii\db\Migration;

/**
 * Class m201023_080923_change_column_zip_in_user
 */
class m201023_080923_change_column_zip_in_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->alterColumn('user', 'zip', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201023_080923_change_column_zip_in_user cannot be reverted.\n";

        return false;
    }
    */
}
