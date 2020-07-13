<?php

use kartik\grid\GridView;
use kartik\grid\CheckboxColumn;
use kartik\grid\ActionColumn;
use yii\helpers\Html;
use common\helper\Component;

?>
<?= $this->render("_search",['model' => $searchModel])?>
    <div class="table-responsive">

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'responsive' => true,
            'layout' => "{items}\n{pager}",
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
                        return ['data-cate' => $model->page->category_id];
                    }
                ],
                [
                    'label' => 'sản phẩm',
                    'attribute' => 'category_id',
                    'format' => 'html',
                    'value' => function ($model) {
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
                            return Component::view($url);
                        }
                    ]
                ],
            ],
        ]) ?>
    </div>

<?php
