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
        'columns' => [
            ['class' => CheckboxColumn::class],
            [
                'label' => 'sản phẩm',
                'attribute' => 'category_id',
                'format' => 'html',
                'value' => function ($model) {
                    return Html::tag("p",
                        $model->page->product->name . "<br><small>{$model->page->product->sku} | {$model->page->product->regular_price}</small><br>" .
                        "<small><i>{$model->page->category->name}</i></small>"
                        , []);
                }
            ],
            [
                'label' => 'Trang đích',
                'attribute' => 'link',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::tag("p",
                        "<a target='_blank' href='{$model->link}' >{$model->name}  <i class='fa fa-chrome'></i></a><br><small>{$model->option}</small><br>" .
                        "<small class='text-danger'>Note: <i>{$model->note}</i></small>"
                        , []);
                }
            ],
            [
                'label' => 'Trạng thái',
                'attribute' => 'status',
                'format' => 'html',
                'value' => function ($model) {
                    return \backend\models\ContactsModel::label($model->status);
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
