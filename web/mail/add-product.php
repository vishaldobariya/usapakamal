<?php
/**
 *
 * @author    Paul Stolyarov <teajeraker@gmail.com>
 * @copyright industrialax.com
 * @license   https://industrialax.com/crm-general-license
 */

use app\modules\shop\models\AddProductForm;

/**
 * @var $model AddProductForm
 */

$user = Yii::$app->user->identity;
?>
<p><strong>Name: </strong><?= $user->first_name . ' ' . $user->last_name . '(' . $user->email . ')' ?></p>
<p>wants to add <strong><?= $model->product_name ?></strong></p>
<p><strong>Cap:</strong> <?= $model->cap ?></p>
<p><strong>Vol:</strong> <?= $model->vol.'ml' ?></p>
<p><strong>Abv:</strong> <?= $model->abv.'%' ?></p>
<p><strong>Suggested Price:</strong> <?= Yii::$app->formatter->asCurrency($model->price) ?></p>

