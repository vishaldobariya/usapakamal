<?php

/**
 *
 * @author    Paul Stolyarov <teajeraker@gmail.com>
 * @copyright industrialax.com
 * @license   https://industrialax.com/crm-general-license
 */
$domain = str_replace(Yii::$app->request->url, '', Yii::$app->request->absoluteUrl);

?>
<h2>Hello, <?= $user->email ?></h2>
<h4>Congratulations on your successful registration</h4>
