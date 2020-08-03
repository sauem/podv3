<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\grid\CheckboxColumn;
use kartik\form\ActiveForm;
use kartik\grid\ActionColumn;
use common\helper\Component;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\LandingPagesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Landing Pages';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-4">
        <div class="ibox table-responsive">
            <div class="ibox-head">
                <h2 class="ibox-title">Thêm trang đích</h2>
            </div>
            <?php $form = ActiveForm::begin() ?>
            <div class="ibox-body">
                <div class="row">
                    <div class="col-md-12">
                        <?= $form->field($model, 'name')->label('Tên trang') ?>
                        <?= $form->field($model, 'user_id')->hiddenInput([
                            'value' => Yii::$app->user->getId()
                        ])->label(false) ?>
                    </div>
                    <div class="col-md-12">
                        <?= $form->field($model, 'link')->label('Link trang') ?>
                    </div>
                    <div class="col-md-12">
                        <?= $form->field($model, 'category_id')
                            ->widget(\kartik\select2\Select2::className(), [
                                'data' => \backend\models\CategoriesModel::select(),
                                'theme' => \kartik\select2\Select2::THEME_DEFAULT,
                                'options' => [
                                    'prompt' => 'Chọn danh mục'
                                ]
                            ])
                            ->label("Danh mục sản phẩm") ?>
                    </div>
                    <div class="col-md-12">
                        <?= $form->field($model, 'product_id')
                            ->widget(\kartik\select2\Select2::className(), [
                                'data' => \backend\models\ProductsModel::select(),
                                'theme' => \kartik\select2\Select2::THEME_DEFAULT,
                                'options' => [
                                    'prompt' => 'Chọn sản phẩm'
                                ]
                            ])
                            ->label("Sản phẩm") ?>
                        <small class="text-warning">#Lời khuyên : Nên chọn sản phẩm với danh mục tương ứng</small>
                    </div>
                </div>
                <div class="mt-3 text-right">
                    <?= Component::reset() ?>
                    <?= Html::submitButton("Lưu", ['class' => 'btn btn-success']) ?>
                </div>
            </div>
            <?php ActiveForm::end() ?>
        </div>
    </div>
    <div class="col-md-8">
        <div class="ibox">
            <div class="ibox-head">
                <h2 class="ibox-title">Danh sách trang</h2>
                <div class="ibox-tools">
                    <a data-toggle="collapse" href="#filter"><i class="fa fa-filter"></i> Tìm kiếm</a>
                </div>
            </div>
            <div class="ibox-body">
                <?= $this->render('_search',['model' => $searchModel])?>
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'responsive' => true,
                    'headerRowOptions' => [
                        'class' => 'thead-light'
                    ],
                    'columns' => [
                        ['class' => CheckboxColumn::class],
                        [
                            'attribute' => 'name',
                            'format' => 'raw',
                            'value' => function ($model) {
                                return Html::tag("strong",$model->name). "<br>"
                                    .Html::a("<i class='fa fa-link'></i> xem trang",'http://' .$model->link,["target" => "_blank"]);
                            }
                        ],
                        [
                            'attribute' => 'category_id',
                            'format' => 'html',
                            'value' => function ($model) {
                                return $model->category->name;
                            }
                        ],
                        [
                            'attribute' => 'product_id',
                            'format' => 'html',
                            'value' => function ($model) {
                                if(!$model->product){
                                    return null;
                                }
                                return "<p><strong>{$model->product->sku}</strong> | {$model->product->name}<br>{$model->product->regular_price}</p>";
                            }
                        ],
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
<?php
$js =<<<JS

JS;
$this->registerJs($js);