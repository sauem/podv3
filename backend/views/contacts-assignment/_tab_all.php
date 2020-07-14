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
        'layout' => "{items}\n{pager}",
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
                'format' => 'raw',
                'value' => function ($model) {
                    $count = sizeof($model->sumContact);
                    return Html::a("<span data-toggle='tooltip' title='Số lượng liên hệ' class='badge badge-info rounded'> $count</span> $model->phone", \yii\helpers\Url::toRoute(['contacts/index', 'phone' => $model->phone]));
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
                'label' => 'Trạng thái',
                'attribute' => 'status',
                'format' => 'html',
                'value' => function ($model) {
                    return \backend\models\ContactsAssignment::label($model->assignment->status);
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
