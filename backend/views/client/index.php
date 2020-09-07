<?php
use kartik\grid\GridView;
use kartik\grid\CheckboxColumn;
use kartik\grid\ActionColumn;
use common\helper\Component;
use yii\helpers\Html;
?>
<div class="ibox">
    <div class="ibox-head">
        <h2 class="ibox-title">Danh sách trang</h2>
        <div class="ibox-tools">
            <a data-toggle="collapse" href="#filter"><i class="fa fa-filter"></i> Tìm kiếm</a>
        </div>
    </div>
    <div class="ibox-body">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'responsive' => true,
            'headerRowOptions' => [
                'class' => 'thead-light'
            ],
            'columns' => [
                [
                    'attribute' => 'name',
                    'format' => 'raw',
                    'value' => function ($model) {
                        $html = $model->marketer ? "<span>{$model->marketer} | {$model->country}</span><br>" : "";

                        $html .= Html::tag("strong", $model->name) . "<br>"
                            . Html::a("<i class='fa fa-link'></i> xem trang", 'http://' . $model->link, ["target" => "_blank"]);
                        return $html;
                    }
                ],
                [
                    'attribute' => 'category_id',
                    'format' => 'html',
                    'value' => function ($model) {
                        if(!$model->category){
                            return null;
                        }
                        return $model->category->name;
                    }
                ],
                [
                    'attribute' => 'product_id',
                    'format' => 'html',
                    'value' => function ($model) {
                        if (!$model->product) {
                            return null;
                        }
                        return "<p><strong>{$model->product->sku}</strong> | {$model->product->name}<br>{$model->product->regular_price}</p>";
                    }
                ],
                'created_at:date',
            ],
        ]); ?>
    </div>
</div>
