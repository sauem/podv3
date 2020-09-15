<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use common\helper\Helper;
use kartik\grid\ActionColumn;
use common\helper\Component;
use yii\helpers\Url;

$this->title = 'Customers';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => [
                        'name',
                        [
                            'label' => 'Liên hệ',
                            'format' => 'html',
                            'value' => function ($model) {
                                $html = "<a href='tel:{$model->phone}'>{$model->phone}</a>";
                                $html .= "<br><a href='mailto:{$model->email}'>{$model->email}</a>";
                                return $html;
                            }
                        ],
                        'zipcode',
                        [
                            'label' => 'Địa chỉ',
                            'headerOptions' => [
                                'width' => '30%'
                            ],
                            'format' => 'html',
                            'value' => function ($model) {
                                $html = "{$model->city}<br>";
                                $html .= "{$model->district}<br>";
                                $html .= Helper::getCountry($model->country) . "<br>";
                                $html .= $model->address;
                                return $html;
                            }
                        ],
                        [
                            'class' => ActionColumn::class,
                            'width' => '160px',
                            'template' => '{update}{delete}',
                            'buttons' => [
                                'update' => function ($url, $model) {
                                    $url = Url::toRoute(['index', 'id' => $model->id]);
                                    return Component::update($url);
                                },
                                'delete' => function ($url) {
                                    return Component::delete($url);
                                }
                            ]
                        ]
                    ]
                ]) ?>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Tạo khách hàng</h4>
                <hr>
                <?= $this->render("form", ['model' => $model]) ?>
            </div>
        </div>
    </div>
</div>