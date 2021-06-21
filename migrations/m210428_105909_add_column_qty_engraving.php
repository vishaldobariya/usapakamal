<?php

use yii\db\Migration;

/**
 * Class m210428_105909_add_column_qty_engraving
 */
class m210428_105909_add_column_qty_engraving extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->addColumn('engraving', 'qty', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210428_105909_add_column_qty_engraving cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210428_105909_add_column_qty_engraving cannot be reverted.\n";

        return false;
    }
    */
}
