<?php
use kartik\grid\GridView;
use kartik\grid\ExpandRowColumn;
use yii\helpers\Html;
use common\helper\Helper;
?>
<div class="row">
    <div class="col-md-12">
        <div class="ibox">
            <div class="ibox-body">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'responsive' => true,
                    'layout' => "{summary}{items}\n{pager}",
                    'pjax' => true,
                    'pjaxSettings' => [
                        'neverTimeout' => true,
                        'options' => [
                            'id' => 'pjax-wait'
                        ]
                    ],
                    'headerRowOptions' => [
                        'class' => 'thead-light'
                    ],
                    'columns' => [
                        [
                            'label' => 'sản phẩm',
                            'attribute' => 'category_id',
                            'format' => 'html',
                            'value' => function ($model) {
                                if(!$model->page || !$model->page->product){
                                    return null;
                                }
                                return Html::tag("p",
                                    $model->page->product->name .
                                    "<br><small>{$model->page->product->sku} |".Helper::money($model->page->product->regular_price)."</small> | <small><i>{$model->page->category->name}</i></small>" .
                                    "<br><small>{$model->option}</small>");
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
                                    "<a target='_blank' href='{$model->link}' >{$model->page->link}  <i class='fa fa-chrome'></i></a><br>" .
                                    "<small class='text-info'>CTCODE: <i><strong>{$model->code}</strong></i> | marketer: <strong>{$model->page->marketer}</strong> | Type : <strong>{$model->type}</strong></small><br>" .
                                    "<small class='text-info'>address: <i>{$model->address}</i></small><br>" .
                                    "<small class='text-info'>zipcode: <i>{$model->zipcode}</i></small><br>" .
                                    "<small class='text-danger'>Note: <i>{$model->note}</i></small><br>"

                                );
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
                        [
                            'label' => 'Ngày đặt hàng',
                            'attribute' => 'created_at',
                            'format' => 'html',
                            'value' => function ($model) {
                                return Html::tag("small",date("d/m/Y H:i:s",$model->register_time));
                            }
                        ]
                    ],
                ]) ?>
            </div>
        </div>
    </div>
</div>
