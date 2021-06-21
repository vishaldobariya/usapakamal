<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tags}}`.
 */
class m200817_162905_create_tags_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
    	$this->addColumn('product', 'tags', $this->string());
	    //$this->createTable('{{%product_tag}}', [
		//    'id'        => $this->primaryKey(),
		//    'name'      => $this->string(),
		//    'frequency' => $this->integer()->defaultValue(0),
	    //
	    //]);
	    //$this->createTable('{{%product_tag_relative}}', [
		//    'product_id' => $this->integer(),
		//    'tag_id'       => $this->integer(),
	    //]);
	    //$this->addForeignKey('fk-tag_relative-tag', '{{%product_tag_relative}}', 'tag_id', '{{%product_tag}}', 'id', 'CASCADE', 'CASCADE');
	    //$this->addForeignKey('fk-tag_relative-product', '{{%product_tag_relative}}', 'product_id', '{{%product}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    	$this->dropTable('{{%product_tag_relative}}');
        $this->dropTable('{{%product_tag}}');
    }
}
