<?php

use yii\db\Migration;

/**
 * Class m201231_122026_add_column_position_to_storage
 */
class m201231_122026_add_column_position_to_storage extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
    	$this->addColumn('storage', 'position', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m201231_122026_add_column_position_to_storage cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201231_122026_add_column_position_to_storage cannot be reverted.\n";

        return false;
    }
    */
}
