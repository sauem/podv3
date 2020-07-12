<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\OrdersModel */

$this->title = 'Create Orders Model';
$this->params['breadcrumbs'][] = ['label' => 'Orders Models', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="orders-model-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
