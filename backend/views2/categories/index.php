<?php

use yii\helpers\Html;
use kartik\grid\CheckboxColumn;
use kartik\grid\GridView;
use kartik\form\ActiveForm;
use kartik\grid\ActionColumn;
use common\helper\Component;
use yii\helpers\Url;

$this->title = 'Categories Models';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Loại sản phẩm</h4>
            </div>
            <div class="card-body">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'responsive' => true,
                    'panelTemplate' => '{panelHeading}{items}{panelFooter}',
                    'panel' => [
                        'type' => 'default',
                        'heading' => '<div class="d-flex">' . Html::a('<i class="fa fa-trash"></i> Xóa lựa chọn', 'javascript:;',
                                [
                                    'class' => 'btn btn-sm deleteAll btn-warning',
                                    'data-pjax' => '0',
                                    'data-model' => $dataProvider->query->modelClass
                                ]) . '{export}{toggleData}</div>',
                    ],
                    'toggleDataContainer' => ['class' => 'btn-group-sm ml-1'],
                    'exportContainer' => ['class' => 'btn-group-sm ml-1'],
                    'columns' => [
                        ['class' => CheckboxColumn::class],
                        'name',
                        'description',
                        'created_at:date',
                        [
                            'class' => ActionColumn::class,
                            'template' => '{update}{delete}',
                            'header' => 'Thao tác',
                            'width' => '200px',
                            'buttons' => [
                                'delete' => function ($url, $model) {
                                    return Component::delete($url);
                                },
                                'update' => function ($url, $model) {
                                    $url =  Url::toRoute(['index', 'id' => $model->id]);
                                    return Component::update($url);
                                },
                            ]
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card table-responsive">
            <div class="card-header">
                <h4 class="card-title">Tạo loại sản phẩm</h4>
            </div>
            <?php $form = ActiveForm::begin() ?>
            <div class="card-body">
                <?= $form->field($model, 'name')->label('Tên loại sản phẩm') ?>
                <?= $form->field($model, 'description')->textarea()->label('Mô tả') ?>
                <div class="text-right">
                    <?= Component::reset() ?>
                    <?= Html::submitButton("Lưu", ['class' => 'btn btn-success']) ?>
                </div>
            </div>
            <?php ActiveForm::end() ?>
        </div>
    </div>
</div>

