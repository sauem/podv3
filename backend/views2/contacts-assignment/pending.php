<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use backend\models\ContactsModel;
use kartik\grid\ActionColumn;
?>

<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h4 class="card-title">Danh sách liên hệ chờ</h4>
        <div class="card-tools">
            <button class="btn btn-sm btn-outline-warning"><i class="fe-cloud-off"></i> Kích hoạt liên hệ</button>
        </div>
    </div>
    <div class="card-body">
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
                'code',
                [
                    'label' => 'Trang đích',
                    'headerOptions' => [
                        'width' => '35%'
                    ],
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
                    'label' => 'Trạng thái',
                    'attribute' => 'status',
                    'format' => 'html',
                    'value' => function ($model) {
                        return ContactsModel::label($model->status);
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
</div>


