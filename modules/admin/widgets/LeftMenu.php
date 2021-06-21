<?php

namespace app\modules\admin\widgets;

use Yii;
use yii\base\Widget;

/**
 * @link      http://industrialax.com/
 * @email     xristmas365@gmail.com
 * @author    isteil
 * @copyright Copyright (c) 2020 INDUSTRIALAX SOLUTIONS LLC
 * @license   https://industrialax.com/license
 */
class LeftMenu extends Widget
{
	
	public    $menuFile = '@app/modules/admin/menu/left.php';
	
	protected $items    = [];
	
	public function init()
	{
		parent::init();
		$this->items = require Yii::getAlias($this->menuFile);
	}
	
	public function run()
	{
		parent::run();
		
		return $this->render('left', ['items' => $this->items]);
	}
}
