<?php

use app\modules\shop\models\Product;
use yii\db\Migration;

/**
 * Class m200818_100529_add_columns_to_product
 */
class m200818_100529_add_columns_to_product extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->addColumn('product', 'special_offers', $this->boolean()->defaultValue(false));
		$this->addColumn('product', 'featured_brand', $this->boolean()->defaultValue(false));
		$this->dropColumn('product', 'sale');
	    Product::updateAll(['special_offers' => false],['>','id',0]);
	    Product::updateAll(['featured_brand' => false],['>','id',0]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200818_100529_add_columns_to_product cannot be reverted.\n";

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200818_100529_add_columns_to_product cannot be reverted.\n";

        return false;
    }
    */
}
