<?php

use yii\db\Migration;

/**
 * Class m210408_135631_add_column_order
 */
class m210408_135631_add_column_order extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
    	$this->addColumn('order', 'provider_status', $this->smallInteger(1)->defaultValue(0));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210408_135631_add_column_order cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210408_135631_add_column_order cannot be reverted.\n";

        return false;
    }
    */
}
