<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\shop\models\Customer */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Customers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="customer-view">

    <h1><?= Html::encode($this->title) ?></h1>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'first_name',
            'last_name',
            'email:email',
            'address',
            'adress_two',
            'city',
            'contry',
            'zip',
            'phone',
            'state',
            'billing_address',
            'billing_address_two',
            'billing_city',
            'billing_country',
            'billing_state',
            'billing_zip',
            'billing_phone',
            'billing_first_name',
            'billing_last_name',
        ],
    ]) ?>

</div>
