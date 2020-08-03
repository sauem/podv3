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
                'options' => [
                    'id' => 'pjax-contact_histories'
                ]
            ],
            'headerRowOptions' => [
                'class' => 'thead-light'
            ],
            'columns' => [
                [
                    'label' => 'Contact',
                    'attribute' => 'contact_id',
                    'format' => 'raw',
                    'value' => function ($model) {
                        $html =  $model->contact->code."<br>";
                        $html .= "<small class='text-warning'>{$model->created_at}</small>";
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
                [
                    'label' => 'Ghi chú',
                    'attribute' => 'note'
                ]
            ],
        ]) ?>
    </div>
</div>
