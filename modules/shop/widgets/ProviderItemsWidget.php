<?php
/**
 *
 * @author    Paul Stolyarov <teajeraker@gmail.com>
 * @copyright industrialax.com
 * @license   https://industrialax.com/crm-general-license
 */

namespace app\modules\shop\widgets;

use app\modules\shop\models\search\OrderItemSearch;
use yii\jui\Widget;

class ProviderItemsWidget extends Widget
{
	
	public  $id;
	
	
	public function run()
	{
		$searchModel = new OrderItemSearch();
		$dataProvider = $searchModel->searchProvider($this->id);
		
		return $this->render('_provider_order_items', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}
	
}

