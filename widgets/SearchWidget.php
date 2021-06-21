<?php
/**
 *
 * @author    Paul Stolyarov <teajeraker@gmail.com>
 * @copyright industrialax.com
 * @license   https://industrialax.com/crm-general-license
 */

namespace app\widgets;

use yii\jui\Widget;

class SearchWidget extends Widget
{
	
	public function run()
	{
		return $this->render('search');
	}
	
}
