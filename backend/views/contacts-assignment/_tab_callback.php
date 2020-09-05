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
        'pjaxSettings' => [
            'neverTimeout' => true,
        ],
        'headerRowOptions' => [
            'class' => 'thead-light'
        ],
        'columns' => [
            [

                'label' => 'Số điện thoại',
                'attribute' => 'phone',
                'value' => function ($model) {
                    return $model->phone . " | " . $model->code;
                }
            ],
            [
                'label' => 'Trang đích',
                'attribute' => 'link',
                'format' => 'raw',
                'value' => function ($model) {
                    if (!$model->page) {
                        return null;
                    }
                    return Html::tag("p",
                        "<a target='_blank' href='" . \common\helper\Helper::link($model->link) . "' >{$model->page->link}</a><br>" .
                        "<small class='text-info'>address: <i>{$model->address}</i></small><br>" .
                        "<small class='text-info'>zipcode: <i>{$model->zipcode}</i></small><br>" .
                        ($model->note ? "<small class='text-danger'>Note: <i>{$model->note}</i></small>" : "")

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
                'label' => 'Ngày liên hệ',
                'attribute' => 'status',
                'format' => 'html',
                'value' => function ($model) {
                    return ContactsModel::label($model->status);
                }
            ],
            [
                'class' => ActionColumn::class,
                'template' => '{view}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a("<i class='fa fa-eye'></i> chi tiết",
                            \yii\helpers\Url::toRoute(['view', 'phone' => $model->phone]),
                            ['class' => 'btn btn-sm bg-white', 'data-pjax' => '0']);
                    }
                ]
            ],
        ],
    ]) ?>
</div>
