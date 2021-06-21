<?php
/**
 *
 * @author    Paul Stolyarov <teajeraker@gmail.com>
 * @copyright industrialax.com
 * @license   https://industrialax.com/crm-general-license
 */

namespace app\modules\shop\widgets;

use yii\jui\Widget;
use app\modules\shop\models\Order;

class CartWidget extends Widget
{
	
	public function run()
	{
		return $this->render('cart', [
			'order' => \Yii::$app->session->get('order') ?? new Order,
		]);
	}
}
