<?php

$domain = str_replace(Yii::$app->request->url, '', Yii::$app->request->absoluteUrl);
?>

<h1>Hello, <?= $name ?></h1>
<br>
<?php if($name !== 'Admin') : ?>
	<h4>You were selected to be the executor for the order #<?= 10000 + $order ?>.Please confirm it or refuse in your <a href="<?=$domain.'/sign/in'?>">Dashboard/Orders</a></h4>
<?php else: ?>
	The order <b>#<?= 10000 + $order ?></b>. <b>Executor not found.</b>
<?php endif; ?>
