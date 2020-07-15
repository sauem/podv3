<?php

use kartik\grid\GridView;
use kartik\grid\CheckboxColumn;
use kartik\grid\ActionColumn;
use yii\helpers\Html;
use common\helper\Component;
use yii\helpers\Url;

?>
<div class="table-responsive">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'responsive' => true,
        'layout' => "{items}\n{pager}",
        'headerRowOptions' => [
            'class' => 'thead-light'
        ],
        // 'pjax' => true,
        'pjaxSettings' => [
            'neverTimeout' => true,
        ],
        'columns' => [
            [
                'label' => 'Số điện thoại',
                'attribute' => 'phone',
                'format' => 'raw',
                'value' => function ($model) {
                    $count = sizeof($model->sumContact);
                    return Html::a("$model->phone", Url::toRoute(['view', 'phone' => $model->phone]));
                }
            ],
            [
                'label' => 'Tên khách hàng',
                'attribute' => 'name',
                'format' => 'html',
                'value' => function ($model) {
                    return $model->name;
                }
            ],
            [
                'label' => 'Quản lý',
                'attribute' => 'status',
                'format' => 'html',
                'value' => function ($model) {
                    return $model->assignment->user->username;
                }
            ],
            [
                'label' => 'Số lượng',
                'attribute' => 'status',
                'format' => 'html',
                'value' => function ($model) {
                    $count = sizeof($model->sumContact);
                    return $count;
                }
            ],
            [
                'class' => ActionColumn::class,
                'template' => '{view}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a("<i class='fa fa-eye'></i> chi tiết",
                            \yii\helpers\Url::toRoute(['view', 'phone' => $model->phone]),
                            ['class' => 'btn btn-sm bg-white']);
                    }
                ]
            ],
        ],
    ]) ?>
</div>
