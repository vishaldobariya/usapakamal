<?php

$domain = str_replace(Yii::$app->request->url, '', Yii::$app->request->absoluteUrl);
?>

<h1>Dear Valued Retailer,</h1>
<p>In order to join our suppliers list, please click this link to create your account.</p>
<p><a href="<?= $domain . '/sign/up?info=' . base64_encode($data) ?>">Crate account</a></p>
<p></p>
<p>After the account setup is completed you will be able to upload your items, prices, and update them when necessary.</p>
