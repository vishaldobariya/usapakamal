<?php
/**
 *
 * @author    Paul Stolyarov <teajeraker@gmail.com>
 * @copyright industrialax.com
 * @license   https://industrialax.com/crm-general-license
 */

namespace app\widgets;

use yii\jui\Widget;
use app\modules\subscribe\models\Subscribe;

class SubscribeWidget extends Widget
{
	
	public function run()
	{
		$model = new Subscribe;
		
		return $this->render('subscribe', [
			'model' => $model,
		]);
	}
}
