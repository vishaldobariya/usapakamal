<?php
/**
 * @author      Anthony <xristmas365@gmail.com>
 * @copyright   industrialax.com
 * @license     https://industrialax.com/crm-general-license
 */

namespace app\modules\shop\widgets;

use app\modules\shop\models\search\OrderItemSearch;
use Yii;
use yii\jui\Widget;

class OrderItemsWidget extends Widget
{
	
	public  $id;
	
	
	public function run()
	{
		$searchModel = new OrderItemSearch();
		$dataProvider = $searchModel->search($this->id);
		
		return $this->render('_order_items', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}
	
}
