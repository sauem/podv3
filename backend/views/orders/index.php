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
use common\helper\Helper;
?>
<div class="ibox">
    <div class="ibox-head">
        <div class="ibox-title">
            Danh sách order
        </div>
    </div>
    <div class="ibox-body">
        <?= $this->render("_search", ['model' => $searchModel]) ?>
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
                [
                    'attribute' => 'customer_name',
                    'format' => 'html',
                    'value' => function ($model) {
                        $html = $model->customer_name . "<br>";
                        $html .= $model->customer_phone . "<br>";
                        $html .= $model->customer_email;
                        $html .= $model->address;
                        return $html;
                    }
                ],
                [
                    'label' => 'Số liên hệ',
                    'attribute' => 'total',
                    'format' => 'html',
                    'value' => function ($model) {
                        return $model->getContacts()->count();
                    }
                ],
                [
                    'label' => 'Người tạo đơn',
                    'attribute' => 'total',
                    'format' => 'html',
                    'value' => function ($model) {
                        return $model->user->username;
                    }
                ],
                [
                    'label' => 'Tổng đơn',
                    'attribute' => 'total',
                    'format' => 'html',
                    'value' => function ($model) {
                        return Helper::money($model->total);
                    }
                ],
                [
                    'label' => 'Ngày tạo đơn',
                    'attribute' => 'created_at',
                    'format' => 'html',
                    'value' => function ($model) {
                        return date('H:i:s d/m/Y');
                    }
                ],
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
