<?php

use kartik\grid\GridView;
use kartik\grid\CheckboxColumn;
use yii\helpers\Html;
use common\helper\Helper;
use kartik\grid\ExpandRowColumn;
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
                'options' => [
                    'id' => isset($id) ? "pjax-$id" : 'pjax-wait'
                ]
            ],
            'headerRowOptions' => [
                'class' => 'thead-light'
            ],
            'columns' => [
                [
                    'class' => CheckboxColumn::class,
                    'checkboxOptions' => function ($model) {
                        if (!$model->page) {
                            return ['data-cate' => null];
                        }
                        return ['data-cate' => $model->page->category_id];
                    }
                ],
                'code',
                [
                    'label' => 'sản phẩm',
                    'attribute' => 'category_id',
                    'format' => 'html',
                    'value' => function ($model) {
                        if (!$model->page || !$model->page->product) {
                            return null;
                        }
                        return Html::tag("p",
                            $model->page->product->name .
                            "<br><small>{$model->page->product->sku} </small> | <small><i>{$model->page->category->name}</i></small>" .
                            "<br><small>{$model->option}</small>");
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
                            "<a target='_blank' href='" . Helper::link($model->link) . "' >{$model->page->link}  <i class='fa fa-chrome'></i></a><br>" .
                            "<small class='text-info'>address: <i>{$model->address}</i></small><br>" .
                            "<small class='text-info'>zipcode: <i>{$model->zipcode}</i></small><br>" .
                            ($model->note ? "<small class='text-danger'>Note: <i>{$model->note}</i></small><br>" : "")

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
                    'label' => 'Ngày đặt hàng',
                    'attribute' => 'created_at',
                    'format' => 'html',
                    'value' => function ($model) {
                        return Html::tag("small", date("d/m/Y H:i:s", $model->register_time));
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
