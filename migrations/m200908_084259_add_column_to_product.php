<?php

use yii\db\Migration;

/**
 * Class m200908_084259_add_column_to_product
 */
class m200908_084259_add_column_to_product extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->addColumn('product', 'vol', $this->integer());
		$this->addColumn('product', 'abv', $this->integer());
		$this->alterColumn('product', 'abv', $this->float(2));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200908_084259_add_column_to_product cannot be reverted.\n";
		
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200908_084259_add_column_to_product cannot be reverted.\n";

        return false;
    }
    */
}
