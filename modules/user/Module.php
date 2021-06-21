<?php
/**
 * @link      http://industrialax.com/
 * @email     xristmas365@gmail.com
 * @author    isteil
 * @copyright Copyright (c) 2020 INDUSTRIALAX SOLUTIONS LLC
 * @license   https://industrialax.com/license
 */

namespace app\modules\user;

class Module extends \yii\base\Module
{
	
	public $adminUrl        = '/admin/dashboard/index';
	
	public $sessionDuration = 60 * 60 * 24 * 30; // 1 month
	
}
