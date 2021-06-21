<?php

use app\modules\settings\models\Zip;
use yii\db\Migration;

/**
 * Class m210504_164308_create_add_columns_to_zip
 */
class m210504_164308_create_add_columns_to_zip extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
    	$this->truncateTable('zip');
		$this->addColumn('zip', 'city', $this->string());
	    if(($handle = fopen(Yii::getAlias('@app/zip_cods.csv'), "r")) !== false) {
		    $flag = true;
		    //find or create store
		
		    while(($data = fgetcsv($handle, 1000000, ',')) !== false) {
			    if($flag) {
				    $flag = false;
				    continue;
			    }
			    $zip = new Zip;
			    $zip->zipcode = $data[0];
			    $zip->city = $data[2];
			    $zip->state = $data[3];
			    $zip->active = true;
			    $zip->save();
		    }
	    }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210504_164308_create_add_columns_to_zip cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210504_164308_create_add_columns_to_zip cannot be reverted.\n";

        return false;
    }
    */
}
