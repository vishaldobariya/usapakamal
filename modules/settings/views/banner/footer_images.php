<?php

$this->title = 'Slider';
$this->params['breadcrumbs'][] = $this->title;

use yii\helpers\Url;
use yii\web\JsExpression;
use trntv\filekit\widget\Upload;

?>

<div class="card">
	<div class="card-header">
		<h2 class="card-title">
			Footer Banner
		</h2>
	</div>
	<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" href="<?= Url::toRoute(['/settings/banner/slider']) ?>">Main Slider </a>
      </li>
	     <li class="nav-item">
        <a class="nav-link" href="<?= Url::toRoute(['/settings/banner/mobile-slider']) ?>">Mobile Slider</a>
      </li>
      <li class="nav-item ">
        <a class="nav-link" href="<?= Url::toRoute(['/settings/banner/middle-images']) ?>">Middle Images</a>
      </li>
      <li class="nav-item active">
        <a class="nav-link" href="#">Footer Images</a>
      </li>
    </ul>
  </div>
</nav>
	<div class="card-body">
			<div class="mb-3">
			<?= Upload::widget([
				'name'                => 'filename[]',
				'options'             => [
					'data-id' => '232',
				],
				'files'               => $files,
				'hiddenInputId'       => true,
				'url'                 => ['/storage/default/upload-footer'],
				'uploadPath'          => 'slider/',
				'sortable'            => true,
				'maxNumberOfFiles'    => 10,
				'showPreviewFilename' => false,
				'clientOptions'       => [
					'done' => new JsExpression('function(e, data) {
					if(e.type === "fileuploaddone"){
					$.post({
					url:"/settings/banner/get-footer-id",
					success:function(res){
					$(".js-insert").append(res)
					}
					})
					}
					 }'),
				],
			]);
			
			?>
			</div>
		
		<div class="row">
				<div class="col-md-6 js-insert">
					<?php if(!empty($files)): ?>
						<?php foreach($files as $key => $file) : ?>
							<div class="mb-4 <?= 'js-' . $file->id ?>">
				<label for="">Link <?= $key + 1 ?></label>
				<input type="text" data-id="<?= $file->id ?>" class="w-100 js-change" value="<?= $file->link ?>">
						</div>
						
						<?php endforeach; ?>
					<?php endif; ?>
			</div>
			
		</div>
	</div>
</div>
<?php $js = <<< JS

$(document).on('click','svg.remove',function() {
 let input =  $(this).parent().find('input').val()
 $.post({
 url:'/settings/banner/delete',
 data:{path:input},
 success:function(res) {
   $('div.js-'+res).remove()
 }
 })
})
$(document).on('change','.js-change',function(){
  let id = $(this).data('id')
  let val = $(this).val()
   $.post({
   url:'/settings/banner/save-link',
   data:{id:id,val:val,model_name:'Footer Image'}
   })
})

$('.ui-sortable').sortable({
       update: function(event, ui) {
  let li = $('.upload-kit-item')
  let count = li.length
  let data = []
  let keys = []
  li.each(function() {
    keys.push($(this).find('img').prop('src'))
    data.push($(this).prop('value'))
   //data[key] = $(this).prop('value')
  })
 $.post({
 url:'/settings/banner/change-position',
 data:{data:data,keys:keys},
 success:function() {
   location.reload()
 }
 })
        }
    });

JS;
$this->registerJs($js) ?>
