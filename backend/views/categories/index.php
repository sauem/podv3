<?php

use yii\helpers\Html;
use kartik\grid\CheckboxColumn;
use kartik\grid\GridView;
use kartik\form\ActiveForm;
use kartik\grid\ActionColumn;
use common\helper\Component;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\CategoriesSearchModel */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Categories Models';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-5">
        <div class="ibox table-responsive">
            <div class="ibox-head">
                <h2 class="ibox-title">Tạo loại sản phẩm</h2>
            </div>
            <?php $form = ActiveForm::begin() ?>
            <div class="ibox-body">
                <?= $form->field($model, 'name')->label('Tên loại sản phẩm') ?>
                <?= $form->field($model, 'description')->textarea()->label('Mô tả') ?>
                <div class="text-right">
                    <?= Component::reset() ?>
                    <?= Html::submitButton("Lưu",['class' =>'btn btn-success'])?>
                </div>
            </div>
            <?php ActiveForm::end()?>
        </div>
    </div>
    <div class="col-md-7">
        <div class="ibox">
            <div class="ibox-head">
                <h2 class="ibox-title">loại sản phẩm</h2>
            </div>
            <div class="ibox-body">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'responsive' => true,
                    'headerRowOptions' => [
                        'class' => 'thead-light'
                    ],
                    'columns' => [
                        ['class' => CheckboxColumn::class],
                        'name',
                        'description',
                        'created_at:date',
                        [
                            'class' => ActionColumn::class,
                            'template' => '{update}{delete}',
                            'buttons' => [
                                'delete' => function ($url, $model) {
                                    return Component::delete($url);
                                },
                                'update' => function ($url, $model) {
                                    $url = \yii\helpers\Url::toRoute(['index','id' => $model->id]);
                                    return Component::update($url);
                                },
                            ]
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>

