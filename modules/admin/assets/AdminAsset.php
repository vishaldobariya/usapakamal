<?php
/**
 *
 * @author    Paul Stolyarov <teajeraker@gmail.com>
 * @copyright industrialax.com
 * @license   https://industrialax.com/crm-general-license
 */

namespace app\modules\admin\assets;

use yii\web\AssetBundle;

class AdminAsset extends AssetBundle
{
	
	public $basePath = '@webroot';
	
	public $baseUrl  = '@web';
	
	public $js       = [
		'dist/admin.js',
	];
	
	public $css      = [
		'dist/admin.css',
	];
	
	public $depends  = [
		GleekAsset::class,
		\yii\bootstrap4\BootstrapPluginAsset::class,
	];
}
