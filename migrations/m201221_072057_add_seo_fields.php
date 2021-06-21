<?php

use yii\db\Migration;

/**
 * Class m201221_072057_add_seo_fields
 */
class m201221_072057_add_seo_fields extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
    	$this->addColumn('product', 'seo_title', $this->string());
    	$this->addColumn('product', 'seo_description', $this->text());
    	$this->addColumn('product', 'seo_keywords', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m201221_072057_add_seo_fields cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201221_072057_add_seo_fields cannot be reverted.\n";

        return false;
    }
    */
}
