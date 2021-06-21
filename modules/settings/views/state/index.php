<?php

use yii\helpers\ArrayHelper;
use app\components\AdminGrid;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\settings\models\search\StateSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'States';
$this->params['breadcrumbs'][] = $this->title;

$ids = ArrayHelper::getColumn($dataProvider->models, 'id', 'id');
$main_check = '';
$i = 0;

foreach($ids as $mod_id) {
	if(in_array($mod_id, $states)) {
		$i++;
	}
}
if($i == 20) {
	$main_check = 'checked';
}

?>
<?= AdminGrid::widget([
	'title'        => 'States',
	'createButton' => '<span></span>',
	'dataProvider' => $dataProvider,
	'filterModel'  => $searchModel,
	'columns'      => [
		[
			'header'          => '<input type="checkbox" ' . $main_check . ' class="select-on-check-all" name="selection_all" value="1">',
			'class'           => \kartik\grid\CheckboxColumn::class,
			'checkboxOptions' => function($model, $i, $c) use ($states)
			{
				$checked = false;
				if(in_array($i, $states)) {
					$checked = true;
				}
				
				return ['checked' => $checked];
			},
		],
		'short',
		'name',
	],
]); ?>
<?php $js = <<< JS
 $(document).on('change','.kv-row-checkbox',function() {
      
      let type = $(this).prop('checked') === true ? 'add' : 'remove'
      let val = $(this).val()
        $.post({
        url: '/settings/state/state',
        data:{type:type,val:val},
        })
    })
JS;
$this->registerJs($js);
?>
