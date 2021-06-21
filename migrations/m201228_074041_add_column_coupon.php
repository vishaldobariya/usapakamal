<?php

use yii\db\Migration;

/**
 * Class m201228_074041_add_column_coupon
 */
class m201228_074041_add_column_coupon extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
    	$this->addColumn('order', 'coupon', $this->string());
    	$this->addColumn('order', 'coupon_percent', $this->float(2));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m201228_074041_add_column_coupon cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201228_074041_add_column_coupon cannot be reverted.\n";

        return false;
    }
    */
}
