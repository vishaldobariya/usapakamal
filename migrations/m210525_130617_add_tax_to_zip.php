<?php

use yii\db\Migration;
use app\modules\v1\models\TaxRate;
use app\modules\settings\models\Zip;

/**
 * Class m210525_130617_add_tax_to_zip
 */
class m210525_130617_add_tax_to_zip extends Migration
{
	
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		
		$this->addColumn('zip', 'tax', $this->float());
		$path = \Yii::getAlias('@app/zip');
		$array = scandir($path);
		array_shift($array);
		array_shift($array);
		foreach($array as $value) {
			if(($handle = fopen($path . '/' . $value, "r")) !== false) {
				$flag = true;
				//find or create store
				
				while(($data = fgetcsv($handle, 1000000, ',')) !== false) {
					if($flag) {
						$flag = false;
						continue;
					}
					$zip = Zip::findOne(['zipcode' => trim($data[1])]);
					
					if($zip){
						$zip->tax = Yii::$app->formatter->asDecimal(((float)$data[4] * 100), 2);
						$zip->save();
					}
					
				}
			}
		}
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		echo "m210525_130617_add_tax_to_zip cannot be reverted.\n";
		
		return true;
	}
	
	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m210525_130617_add_tax_to_zip cannot be reverted.\n";

		return false;
	}
	*/
}
