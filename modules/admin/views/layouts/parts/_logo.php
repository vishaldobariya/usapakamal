<?php

use yii\helpers\{Url, Html};
use app\modules\admin\bundles\AdminCustomAsset;

?>

<?php

AdminCustomAsset::register($this);
?>

<div class="g-site-logo">
	<a href="<?= Url::toRoute(['/site/index']); ?>" class="site-logo">
		<?= Html::img('/images/music-logo.png', ['class' => 'hidden-md-down'])?>
		<div class="site-name">
			<?= Yii::$app->name ?>
		</div>
	</a>
</div>
