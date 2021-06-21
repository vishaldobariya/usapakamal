<?php
/**
 *
 * @author    Paul Stolyarov <teajeraker@gmail.com>
 * @copyright industrialax.com
 * @license   https://industrialax.com/crm-general-license
 */

namespace app\modules\admin\assets;

use yii\web\AssetBundle;

class GleekAsset extends AssetBundle
{
	
	public $basePath = '@webroot';
	
	public $baseUrl  = '@web/admin/gleek';
	
	public $css      = [
		'main/css/style.css',
	];
	
	public $js       = [
		'assets/plugins/common/common.min.js',
		'main/js/custom.min.js',
		'main/js/settings.js',
		'main/js/styleSwitcher.js',
	];
	
	public $depends  = [
		'yii\web\YiiAsset',
		\yii\bootstrap4\BootstrapPluginAsset::class,
	];
}
