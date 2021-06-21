<?php

use yii\db\Migration;

/**
 * Class m200710_121824_add_column_status_to_category
 */
class m200710_121824_add_column_status_to_category extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->addColumn('{{%category}}', 'status', $this->smallInteger(1)->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%category}}', 'status');
    }
}
