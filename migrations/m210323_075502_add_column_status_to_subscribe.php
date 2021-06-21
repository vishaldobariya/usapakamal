<?php

use yii\db\Migration;

/**
 * Class m210323_075502_add_column_status_to_subscribe
 */
class m210323_075502_add_column_status_to_subscribe extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->addColumn('subscribe', 'status', $this->smallInteger(1)->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210323_075502_add_column_status_to_subscribe cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210323_075502_add_column_status_to_subscribe cannot be reverted.\n";

        return false;
    }
    */
}
