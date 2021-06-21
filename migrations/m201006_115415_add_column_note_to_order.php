<?php

use yii\db\Migration;

/**
 * Class m201006_115415_add_column_note_to_order
 */
class m201006_115415_add_column_note_to_order extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->addColumn('order', 'note', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m201006_115415_add_column_note_to_order cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201006_115415_add_column_note_to_order cannot be reverted.\n";

        return false;
    }
    */
}
