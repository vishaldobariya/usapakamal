<?php

use yii\db\Migration;

/**
 * Class m201221_063319_create_add_row_to_setting
 */
class m201221_063319_create_add_row_to_setting extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
	    $this->insert('{{%setting}}', [
		    'system_key' => 'user_message',
		    'label'      => 'Message for User',
		    'value'      => Yii::$app->name,
		    'protected'  => false,
		    'comment'    => 'Message for User',
	    ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m201221_063319_create_add_row_to_setting cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201221_063319_create_add_row_to_setting cannot be reverted.\n";

        return false;
    }
    */
}
