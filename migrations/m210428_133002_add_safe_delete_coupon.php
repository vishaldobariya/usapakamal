<?php

use yii\db\Migration;

/**
 * Class m210428_133002_add_safe_delete_coupon
 */
class m210428_133002_add_safe_delete_coupon extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->addColumn('coupon', 'safe_delete', $this->boolean()->defaultValue(false));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
       $this->dropColumn('coupon', 'safe_delete');

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210428_133002_add_safe_delete_coupon cannot be reverted.\n";

        return false;
    }
    */
}
