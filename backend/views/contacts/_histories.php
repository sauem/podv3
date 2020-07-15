<?php

use kartik\grid\GridView;
use \kartik\grid\SerialColumn;
use yii\helpers\Html;
use backend\models\ContactsModel;

?>
<div class="ibox">
    <div class="ibox-head">
        <h2 class="ibox-title">Lịch sử liên hệ</h2>
    </div>
    <div class="ibox-body">
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
                    'class' => SerialColumn::class,
                ],
                'created_at',
                [
                    'label' => 'Sản phẩm',
                    'attribute' => 'contacts.name',
                    'format' => 'raw',
                    'value' => function ($model) {
                        $html = $model->contact->page->product->name."<br>";
                        $html.= $model->contact->page->product->sku ." | ";
                        $html.= $model->contact->page->product->regular_price. " | ";
                        $html.= $model->contact->page->product->category->name . "<br>";
                        $html.= $model->contact->option;
                        return $html;
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
                'note'
            ],
        ]) ?>
    </div>
</div>
