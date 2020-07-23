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
        'pjax' => true,
        'pjaxSettings' => [
            'neverTimeout' => true,
        ],
        'headerRowOptions' => [
            'class' => 'thead-light'
        ],
        'columns' => [
            [
                'class' => CheckboxColumn::class,
                'checkboxOptions' => function ($model) {
                    if(!$model->page){
                        return null;
                    }
                    return ['data-cate' => $model->page->category_id,'disabled' => true];
                }
            ],
            [
                'label' => 'sản phẩm',
                'attribute' => 'category_id',
                'format' => 'html',
                'value' => function ($model) {
                    if(!$model->page){
                        return null;
                    }
                    return Html::tag("p",
                        $model->page->product->name . "<br><small>{$model->page->product->sku} | {$model->page->product->regular_price}</small><br>" .
                        "<small><i>{$model->page->category->name}</i></small>");
                }
            ],
            [
                'label' => 'Trang đích',
                'attribute' => 'link',
                'format' => 'raw',
                'value' => function ($model) {
                    if(!$model->page){
                        return null;
                    }
                    return Html::tag("p",
                        "<a target='_blank' href='{$model->link}' >{$model->page->link}  <i class='fa fa-chrome'></i></a><br><small>{$model->option}</small><br>" .
                        "<small class='text-danger'>Note: <i>{$model->note}</i></small>");
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
                'template' => '{takenote}{view}',
                'buttons' => [
                    'takenote' => function ($url, $model) {
                        return Html::a("<i class='fa fa-newspaper-o'></i> Trạng thái",
                            'javascript:;',
                            [
                                'class' => 'btn btn-sm mb-1 bg-white btnNoteModal',
                                'data-contact' => $model->id,
                                'data-status' => $model->status,

                            ]);
                    },
                    'view' => function ($url, $model) {
                        return Html::a("<i class='fa fa-eye'></i> chi tiết", '#viewNote', [
                            'data-remote' => \yii\helpers\Url::toRoute(['view', 'id' => $model->id]),
                            'data-target' => "#viewNote",
                            'data-toggle' => 'modal',
                            'class' => 'btn btn-sm bg-white'
                        ]);
                    }
                ]
            ],
        ],
    ]) ?>
</div>