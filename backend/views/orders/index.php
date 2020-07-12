<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\OrdersSearchModel */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Orders Models';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ibox">
    <div class="ibox-head">
        <div class="ibox-title">
            Danh sách order
        </div>
    </div>
    <div class="ibox-body">
        <?php Pjax::begin(); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'id',
                'customer_name',
                'customer_phone',
                'customer_email:email',
                'address',
                //'city',
                //'district',
                //'zipcode',
                //'country',
                //'sale',
                //'sub_total',
                //'total',
                //'order_note',
                //'user_id',
                //'status',
                //'status_note',
                //'created_at',
                //'updated_at',

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>

        <?php Pjax::end(); ?>

    </div>
</div>
