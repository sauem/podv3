<?php

use kartik\grid\GridView;
use kartik\grid\CheckboxColumn;
use kartik\grid\ActionColumn;
use yii\helpers\Html;
use common\helper\Component;
use backend\models\ContactsModel;

?>
<div class="table-responsive">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'responsive' => true,
        'layout' => "{summary}{items}\n{pager}",
        'pjax' => true,
        'resizableColumns' => false,
        'pjaxSettings' => [
            'neverTimeout' => true,
        ],
        'headerRowOptions' => [
            'class' => 'thead-light'
        ],
        'columns' => [
            [
                'label' => 'Code',
                'attribute' => 'code',
                'format' => 'html',
                'value' => function ($model) {
                    $html = $model->code . "<br>";
                    $html .= ContactsModel::label($model->status);
                    return $html;
                }
            ],
            [
                'label' => 'Trang đích',
                'width' => '20%',
                'format' => 'raw',
                'value' => function ($model) {
                    if (!$model->page) {
                        return null;
                    }
                    return Html::tag("p",
                        "<a target='_blank' href='" . \common\helper\Helper::link($model->link) . "' >{$model->page->link}  <i class='fa fa-chrome'></i></a><br>" .
                        "<small class='text-info'>address: <i>{$model->address}</i></small><br>" .
                        "<small class='text-info'>zipcode: <i>{$model->zipcode}</i></small><br>" .
                        "<small class='text-danger'>Note: <i>{$model->note}</i></small><br>"

                    );
                }
            ],
            [
                'label' => 'sản phẩm',
                'attribute' => 'category_id',
                'format' => 'html',
                'value' => function ($model) {
                    if (!$model->page || !$model->page->product) {
                        return null;
                    }
                    return Html::tag("p",
                        $model->page->product->name . "<br><small>{$model->page->product->sku} | {$model->page->category->name}</small><br>");
                }
            ],
            [
                'label' => 'Yêu cầu',
                'attribute' => 'status',
                'format' => 'html',
                'value' => function ($model) {
                    return $model->option;
                }
            ],
            [
                'attribute' => 'register_time',
                'format' => 'html',
                'headerOptions' => [
                    'width' => '15%'
                ],
                'value' => function ($model) {
                    return date("d/m/Y H:i:s", $model->register_time);
                }
            ],
            [
                'class' => ActionColumn::class,
                'template' => '{view}',
                'width' => '10%',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a("<i class='fe-eye'></i> chi tiết", '#viewNote', [
                            'data-remote' => \yii\helpers\Url::toRoute(['contacts/view', 'id' => $model->id]),
                            'data-target' => "#viewNote",
                            'data-toggle' => 'modal',
                            'class' => 'btn btn-xs btn-outline-info'
                        ]);
                    }
                ]
            ],
        ],
    ]) ?>
</div>
