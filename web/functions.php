<?php

use app\modules\storage\models\StorageItem;
use yii\helpers\VarDumper;

/**
 *
 * @author    Paul Stolyarov <teajeraker@gmail.com>
 * @copyright industrialax.com
 * @license   https://industrialax.com/crm-general-license
 */
function dd($var)
{
	VarDumper::dump($var, 5, true);
	die;
}

function dump($var)
{
	VarDumper::dump($var, 5, true);
	
}

function authorization()
{
	return 'Basic ' . base64_encode(SHIPSTATION_API_Key . ':' . SHIPSTATION_API_Secret);
}

function imageBlock()
{
	$img = StorageItem::find()->where(['model_name' => 'Slider'])->orderBy(['position' => SORT_ASC])->one();
	return $img ? $img->src : '/images/hero.jpg';
}
