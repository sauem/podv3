<?php

use kartik\grid\GridView;
use kartik\grid\CheckboxColumn;
use kartik\grid\ActionColumn;
use yii\helpers\Html;
use common\helper\Component;

?>
<div class="table-responsive">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'responsive' => true,
        'headerRowOptions' => [
            'class' => 'thead-light'
        ],
       // 'pjax' => true,
        'pjaxSettings' => [
            'neverTimeout' => true,
        ],
        'columns' => [
            ['class' => CheckboxColumn::class],

            [
                'label' => 'Số điện thoại',
                'attribute' => 'phone',
                'format' => 'html',
                'value' => function ($model) {
                    return Html::a($model->phone,\yii\helpers\Url::toRoute(['contacts/index','phone' => $model->phone]));
                }
            ],
            'name',
            [
                'label' => 'Trạng thái',
                'attribute' => 'status',
                'format' => 'html',
                'value' => function ($model) {
                    return $model->assignment->status;
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
            'created_at:date',
            [
                'class' => ActionColumn::class,
                'template' => '{takenote}',
                'buttons' => [
                    'takenote' => function ($url, $model) {
                        return Html::a("<i class='fa fa-newspaper-o'></i> Ghi chú",
                            '#takeNoteModal',
                            [
                                'class' => 'btn btn-sm bg-white',
                                'data-toggle' => 'modal'
                            ]);
                    },
                ]
            ],
        ],
    ]) ?>
</div>
