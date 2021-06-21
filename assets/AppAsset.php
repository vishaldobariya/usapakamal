<?php

/**
 * @link      http://industrialax.com/
 * @email     xristmas365@gmail.com
 * @author    isteil
 * @copyright Copyright (c) 2020 INDUSTRIALAX SOLUTIONS LLC
 * @license   https://industrialax.com/license
 */

namespace app\assets;

use yii\web\AssetBundle;
use yii\bootstrap4\BootstrapPluginAsset;

class AppAsset extends AssetBundle
{

	public $basePath = '@webroot';

	public $baseUrl  = '@web';

	public $css      = [
		'src/css/glider.css',
		'dist/frontend.css',
	];

	public $js       = [
		'src/js/glider.js',
		'/src/js/slick.min.js',
		'src/js/magn.js',
		'dist/frontend.js',
	];

	public $depends  = [
		// BootstrapAsset::class,
		BootstrapPluginAsset::class,
	];
}
