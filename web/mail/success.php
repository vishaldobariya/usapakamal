<?php

$domain = str_replace(Yii::$app->request->url, '', Yii::$app->request->absoluteUrl);
?>

<h1>Hello, <?= $name ?></h1>
<br>
<h4>Congratulations on your successful registration</h4>
<p><a href="<?= $domain . '/sign/in' ?>">Log in</a></p>
