<?php

use kartik\grid\GridView;
use kartik\grid\CheckboxColumn;
use yii\helpers\Html;
use common\helper\Helper;
use backend\models\ContactsModel;
use kartik\grid\ExpandRowColumn;
?>
    <div class="table-responsive">

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'responsive' => true,
            'layout' => "{items}\n{pager}",
            'pjax' => true,
            'pjaxSettings' => [
                'neverTimeout' => true,
                'options' => [
                    'id' => 'pjax-fail'
                ]
            ],
            'headerRowOptions' => [
                'class' => 'thead-light'
            ],
            'columns' => [
                [
                    'class' => CheckboxColumn::class,
                    'checkboxOptions' => function ($model) {
                        if(!$model->page){
                            return ['data-cate' => null];
                        }
                        return ['data-cate' => $model->page->category_id];
                    }
                ],
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
                            "<small class='text-info'>CTCODE: <i><strong>{$model->code}</strong></i> | Marketing: <strong>{$model->page->user->username}</strong></small><br>" .
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
                        return ContactsModel::label($model->status);
                    }
                ],
                [
                    'label' => 'Ngày nhận',
                    'attribute' => 'created_at',
                    'format' => 'html',
                    'value' => function ($model) {
                        return Html::tag("small",date("H:i:s d/m/Y",$model->created_at));
                    }
                ],
                [
                    'class' => ExpandRowColumn::class,
                    'width' => '50px',
                    'value' => function ($model, $key, $index, $column) {
                        return GridView::ROW_EXPANDED;
                    },
                    'detail' => function ($model, $key, $index, $column) {
                        return Yii::$app->controller->renderPartial('_expand', ['model' => $model]);
                    },
                    'headerOptions' => ['class' => 'expand-area'],
                    'expandOneOnly' => true,
                    'detailRowCssClass' => GridView::TYPE_DEFAULT
                ],
            ],
        ]) ?>
    </div>


<?php
