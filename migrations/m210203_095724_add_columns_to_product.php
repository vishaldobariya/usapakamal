<?php

use yii\db\Migration;

/**
 * Class m210203_095724_add_columns_to_product
 */
class m210203_095724_add_columns_to_product extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->addColumn('product', 'year', $this->integer());
		$this->addColumn('product', 'country', $this->string());
		$this->addColumn('product', 'region', $this->string());
		$this->addColumn('product', 'sub_category_id', $this->integer());
		
		$this->addColumn('category', 'parent_id', $this->integer());
		
		$this->addForeignKey('fk-sub-cat-cat', 'category', 'parent_id', 'category', 'id','SET NULL','NO ACTION');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210203_095724_add_columns_to_product cannot be reverted.\n";

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210203_095724_add_columns_to_product cannot be reverted.\n";

        return false;
    }
    */
}
