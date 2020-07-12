<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\OrdersModel */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Orders Models', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="orders-model-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'customer_name',
            'customer_phone',
            'customer_email:email',
            'address',
            'city',
            'district',
            'zipcode',
            'country',
            'sale',
            'sub_total',
            'total',
            'order_note',
            'user_id',
            'status',
            'status_note',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
