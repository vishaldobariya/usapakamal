<?php

use yii\helpers\Html;
use yii\base\DynamicModel;
use kartik\form\ActiveForm;
use app\modules\settings\models\Setting;

/* @var $this yii\web\View */
/* @var $settings Setting[] */
/* @var $model DynamicModel */
/* @var $form ActiveForm */

$this->title = 'Settings';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin(); ?>
<div class="card">
	<div class="card-header">
		<h2 class="card-title">
			<?= $this->title ?>
		</h2>
	</div>
	<div class="card-body">
		<?php foreach($settings as $setting) : ?><?php $model->{$setting->system_key} = $setting->value ?>
			<div class="mb-3">
				<?php $d = $form->field($model, $setting->system_key)->label($setting->label)->hint($setting->comment)->textarea(['rows' => 2]) ?>
				<?= $setting->protected && Yii::$app->user->identity->role !== 'developer' ? $d->staticInput() : $d ?>
			</div>
		<?php endforeach ?>
	
	</div>
	<div class="card-footer">
		<?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
	</div>
</div>
<?php ActiveForm::end(); ?>

