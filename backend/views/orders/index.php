<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use common\helper\Component;

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
            'layout' => "{items}\n{pager}",
            'headerRowOptions' => [
                'class' => [
                    'thead-light'
                ]
            ],
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'id',
                'customer_name',
                'customer_phone',
                'customer_email:email',
                'address',
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{view}',
                    'buttons' => [
                        'view' => function ($url) {
                            return Component::view($url);
                        }
                    ]
                ],
            ],
        ]); ?>

        <?php Pjax::end(); ?>

    </div>
</div>
