<?php

use kartik\grid\GridView;
use kartik\grid\CheckboxColumn;
use kartik\grid\ActionColumn;
use common\helper\Helper;
use yii\helpers\Html;
use yii\helpers\Url;
use backend\models\ContactsAssignment;

?>
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Số điện thoại gọi lại</h4>
            </div>
            <div class="card-body">
                <?= GridView::widget([
                    'dataProvider' => $callbackProvider,
                    'responsive' => true,
                    'tableOptions' => [
                        'id' => 'gridviewData'
                    ],
                    'layout' => "{summary}{items}\n{pager}",
                    'headerRowOptions' => [
                        'class' => 'thead-light'
                    ],
                    'pjax' => true,
                    'pjaxSettings' => [
                        'neverTimeout' => true,
                        'options' => [
                            'id' => 'pjax-waiting'
                        ],
                        'enablePushState' => false
                    ],
                    'columns' => [
                        [
                            'label' => 'Số điện thoại',
                            'attribute' => 'phone',
                            'format' => 'raw',
                            'value' => function ($model) {
                                return Html::a($model->contact_phone,
                                    Url::toRoute(['view', 'id' => $model->id]), ['data-pjax' => '0']);
                            }
                        ],
                        [
                            'label' => 'Khách hàng',
                            'attribute' => 'phone',
                            'format' => 'raw',
                            'value' => function ($model) {
                                return $model->contacts[0]->name;
                            }
                        ],
                        [
                            'label' => 'Trạng thái',
                            'attribute' => 'status',
                            'format' => 'html',
                            'value' => function ($model) {
                                return ContactsAssignment::label($model->status);
                            }
                        ],
                        [
                            'class' => ActionColumn::class,
                            'template' => '{view}',
                            'header' => 'Hành động',
                            'width' => '120px',
                            'buttons' => [
                                'view' => function ($url, $model) {
                                    return Html::a("<i class='fa fa-eye'></i> chi tiết",
                                        Url::toRoute(['view', 'id' => $model->id]),
                                        ['class' => 'btn btn-sm bg-white', 'data-pjax' => '0']);
                                }
                            ]
                        ],
                    ],
                ]) ?>
            </div>
        </div>
    </div>
    <div class="col-md-6">

        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Số điện thoại đang quản lý</h4>
            </div>
            <div class="card-body">
                <?= GridView::widget([
                    'dataProvider' => $assignProvider,
                    'responsive' => true,
                    'tableOptions' => [
                        'id' => 'gridviewData'
                    ],
                    'layout' => "{summary}{items}\n{pager}",
                    'headerRowOptions' => [
                        'class' => 'thead-light'
                    ],
                    'pjax' => true,
                    'pjaxSettings' => [
                        'neverTimeout' => true,
                        'options' => [
                            'id' => 'pjax-all'
                        ],
                        'enablePushState' => false
                    ],
                    'columns' => [
                        [
                            'label' => 'Số điện thoại',
                            'attribute' => 'phone',
                            'format' => 'raw',
                            'value' => function ($model) {
                                return Html::a($model->contact_phone,
                                    Url::toRoute(['view', 'id' => $model->id]), ['data-pjax' => '0']);

                            }
                        ],
                        [
                            'label' => 'Khách hàng',
                            'attribute' => 'phone',
                            'format' => 'raw',
                            'value' => function ($model) {
                                return $model->contacts[0]->name;
                            }
                        ],
                        [
                            'label' => 'Trạng thái',
                            'attribute' => 'status',
                            'format' => 'html',
                            'value' => function ($model) {
                                return ContactsAssignment::label($model->status);
                            }
                        ],
                        [
                            'class' => ActionColumn::class,
                            'template' => '{view}',
                            'header' => 'Hành động',
                            'width' => '120px',
                            'buttons' => [
                                'view' => function ($url, $model) {
                                    return Html::a("<i class='fe-eye'></i> chi tiết",
                                        Url::toRoute(['view', 'id' => $model->id]),
                                        ['class' => 'btn btn-sm bg-white', 'data-pjax' => '0']);
                                }
                            ]
                        ],
                    ],
                ]) ?>
            </div>
        </div>
    </div>
</div>
