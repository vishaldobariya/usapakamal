<?php

use yii\db\Migration;

/**
 * Class m210105_083852_add_column_website_price
 */
class m210105_083852_add_column_website_price extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->addColumn('product', 'provider_price', $this->float(2));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210105_083852_add_column_website_price cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210105_083852_add_column_website_price cannot be reverted.\n";

        return false;
    }
    */
}
