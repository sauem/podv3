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
                'total',
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
