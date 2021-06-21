<?php

use yii\helpers\Url;
use kartik\form\ActiveForm;

?>
<div class="subscribe">
            <div class="container">
                <h2>Sign up below to get news & deals</h2>
	            <?php $form = ActiveForm::begin(['id' => 'js-subscribe-form','options' => ['class' => 'subscribe-form'], 'action' => Url::toRoute(['/site/subscribe-form'])]) ?>
	            <?= $form->field($model, 'email')->input('text', ['placeholder' => 'Email'])->label(false) ?>
	            <button type="submit" class="btn">Sign Up</button>
	            <?php ActiveForm::end() ?>
	            <p class="subscribe-footer"> You must be 21+ to use our site and sign up for promotions.</p>
            </div>
        </div>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<?php $subscribe_js = <<< JS
$(document).on('beforeSubmit','#js-subscribe-form',function() {
  let url = $(this).prop('action')
  let form = $(this).serialize()
  $.post({
  url:url,
  data:form,
  success:function(res) {
  if (res.status === 'ok'){
    swal({
  title: "Thank you",
  text: res.message,
  icon: "success",
  button: true,
});
  } else{
    swal({
  title: "Oops!",
  text: res.message,
  icon: "warning",
  button: true,
});
  }
  }
  })
  return false;
})
JS;
$this->registerJs($subscribe_js) ?>
