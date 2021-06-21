<?php
/**
 * @link      http://industrialax.com/
 * @email     xristmas365@gmail.com
 * @author    isteil
 * @copyright Copyright (c) 2020 INDUSTRIALAX SOLUTIONS LLC
 * @license   https://industrialax.com/license
 */

namespace app\modules\admin\assets;

use yii\web\AssetBundle;

class BootstrapAsset extends AssetBundle
{
	
	public $basePath = '@webroot';
	
	public $baseUrl  = '@web';
	
	public $css      = [
		'dist/bootstrapAdmin.css',
	];
}
