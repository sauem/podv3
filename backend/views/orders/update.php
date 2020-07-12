<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\OrdersModel */

$this->title = 'Update Orders Model: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Orders Models', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="orders-model-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
