<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\grid\CheckboxColumn;
use kartik\form\ActiveForm;
use kartik\grid\ActionColumn;
use common\helper\Component;

use yii\helpers\Url;
use backend\models\LandingPages;
use backend\models\CategoriesModel;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use backend\models\ProductsModel;
$this->title = 'Landing Pages';
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="row">
        <div class="col-md-4">
            <div class="card table-responsive">
                <div class="card-header">
                    <h4 class="card-title">Thêm trang đích</h4>
                </div>
                <?php $form = ActiveForm::begin() ?>
                <div class="card-body">
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
                                ->widget(Select2::className(), [
                                    'data' => CategoriesModel::select(),
                                    'theme' => Select2::THEME_DEFAULT,
                                    'options' => [
                                        'prompt' => 'Chọn loại sản phẩm'
                                    ]
                                ])
                                ->label("Loại sản phẩm") ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'country')->dropDownList(
                                ArrayHelper::map(Yii::$app->params['country'], 'code', 'name'),
                                ['class' => 'select2']
                            ) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'marketer')->textInput(['placeholder' => 'Tên quản lý']) ?>
                        </div>
                        <div class="col-md-12">
                            <?= $form->field($model, 'product_id')
                                ->widget(Select2::className(), [
                                    'data' => ProductsModel::select(),
                                    'theme' => Select2::THEME_DEFAULT,
                                    'options' => [
                                        'prompt' => 'Chọn sản phẩm'
                                    ]
                                ])
                                ->label("Sản phẩm") ?>
                            <small class="text-warning">#Lời khuyên : Nên chọn sản phẩm với loại sản phẩm tương
                                ứng</small>
                        </div>
                    </div>
                    <div class="mt-3 text-right">
                        <?= Component::reset() ?>
                        <?= Html::submitButton("<i class='fe-save'></i> Lưu", ['class' => 'btn btn-sm btn-success']) ?>
                    </div>
                </div>
                <?php ActiveForm::end() ?>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4 class="card-title">Danh sách trang</h4>
                    <div class="card-tools">
                        <a data-toggle="collapse" href="#filter"><i class="fa fa-filter"></i> Tìm kiếm</a>
                    </div>
                </div>
                <div class="card-body">
                    <?= $this->render('_search', ['model' => $searchModel]) ?>
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
                                    if (!$model->category) {
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
                            [
                                'class' => ActionColumn::class,
                                'template' => '{update}{delete}',
                                'width' => '150px',
                                'buttons' => [
                                    'delete' => function ($url, $model) {
                                        return Component::delete($url);
                                    },
                                    'update' => function ($url, $model) {
                                        $url = Url::toRoute(['index', 'id' => $model->id]);

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
$js = <<<JS

JS;
$this->registerJs($js);