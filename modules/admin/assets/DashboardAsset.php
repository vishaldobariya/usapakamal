<?php

namespace app\modules\admin\assets;

use yii\web\AssetBundle;

class DashboardAsset extends AssetBundle
{
	
	public $basePath = '@webroot';
	
	public $baseUrl  = '@web/admin/gleek';
	
	public $js       = [
		'assets/plugins/chart.js/Chart.bundle.min.js',
		'main/js/dashboard/dashboard-1.js',
	];
	
	
	public $depends = [
		GleekAsset::class,
	];
}
