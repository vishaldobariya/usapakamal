<?php

use yii\db\Migration;

/**
 * Class m200825_163658_add_column_to_brand
 */
class m200825_163658_add_column_to_brand extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->addColumn('brand', 'position', $this->integer());
		$this->addColumn('brand', 'main', $this->boolean()->defaultValue(false));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200825_163658_add_column_to_brand cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200825_163658_add_column_to_brand cannot be reverted.\n";

        return false;
    }
    */
}
