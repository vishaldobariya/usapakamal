<?php
/**
 * @link      http://industrialax.com/
 * @email     xristmas365@gmail.com
 * @author    isteil
 * @copyright Copyright (c) 2020 INDUSTRIALAX SOLUTIONS LLC
 * @license   https://industrialax.com/license
 */

namespace app\components;

use yii\helpers\Html;

class ActionColumn extends \kartik\grid\ActionColumn
{
	
	public $mergeHeader = false;
	
	public $clearAction = ['index'];
	
	public function init()
	{
		parent::init();
		
		if(true) {
			$this->header = Html::a('Clear', $this->clearAction, ['class' => 'btn btn-light']);
		}
		
	}
	
	protected function renderHeaderCellContent()
	{
	}
	
	protected function renderFilterCellContent()
	{
		return parent::renderHeaderCellContent();
	}
}
