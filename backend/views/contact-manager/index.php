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
        <div class="ibox">
            <div class="ibox-body">
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
                        'name',
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
        <div class="ibox">
            <div class="ibox-body">
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
                        'name',
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
</div>
