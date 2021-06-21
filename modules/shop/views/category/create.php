<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\shop\models\Category */

$this->title = 'Create Category';
$this->params['breadcrumbs'][] = ['label' => 'Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-create">

    <h1><?= Html::encode($this->title) ?></h1>
	
	<?= $this->render('_form', [
		'model'      => $model,
		'catParents' => $catParents,
		'products'   => $products,
		'data'       => $data,
		'count_prod' => $count_prod,
		'count_assign' => $count_assign
	]) ?>

</div>
