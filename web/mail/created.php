<?php
/**
 *
 * @author    Paul Stolyarov <teajeraker@gmail.com>
 * @copyright industrialax.com
 * @license   https://industrialax.com/crm-general-license
 */
$domain = str_replace(Yii::$app->request->url, '', Yii::$app->request->absoluteUrl);

?>
<h1>Accaunt was created for <?= $model->name ?></h1>
<p><strong>Email:</strong><?= $model->email ?></p>
<p><strong>Pass:</strong><?= $model->new_pass ?></p>
<p><a href="<?= $domain . '/sign/in' ?>">Log in</a></p>
