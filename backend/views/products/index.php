<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\grid\CheckboxColumn;
use kartik\form\ActiveForm;
use kartik\grid\ActionColumn;
use common\helper\Component;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\ProductsSearchModel */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Products Models';
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="row">
    <div class="col-md-4">
        <div class="ibox table-responsive">
            <div class="ibox-head">
                <h2 class="ibox-title">Tạo sản phẩm</h2>
                <div class="ibox-tools">
                    <button class="btn btn-success btn-sm" data-toggle="modal"
                            data-remote="<?= Url::toRoute(['products/import'])?>"
                            data-target="#product-import">
                        <i class="fa fa-file-excel-o"></i>
                        Nhập sản phẩm</button>
                </div>
            </div>
            <?php $form = ActiveForm::begin() ?>
            <div class="ibox-body">
                <div class="row">
                    <div class="col-md-12">
                        <?= $form->field($model, 'name')->label('Tên sản phẩm') ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'sku')->label('Mã sản phẩm') ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'category_id')
                            ->widget(\kartik\select2\Select2::className(), [
                                'data' => \backend\models\CategoriesModel::select(),
                                'theme' => \kartik\select2\Select2::THEME_DEFAULT,
                                'options' => [
                                    'prompt' => 'Chọn loại sản phẩm'
                                ]
                            ])
                            ->label('loại sản phẩm ') ?>
                    </div>
                    <div class="col-md-12">
                        <?= Component::money($form, $model, 'regular_price') ?>
                    </div>
                    <div class="col-md-12">
                        <?= $form->field($model, 'option')->textarea(['rows' => 10])->label('Tùy chỉnh (Mỗi thuộc tính xuống dòng)') ?>
                    </div>

                </div>
                <div class="text-right">
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
                <h2 class="ibox-title">Danh sách sản phẩm</h2>
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
                            'format' => 'html',
                            'value' => function ($model) {
                                return "<strong><i>$model->sku</i></strong> | $model->name ";
                            }
                        ],
                        'category_id',
                        'regular_price',
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

<div class="modal fade" tabindex="-1" id="product-import" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nhập sản phẩm</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer d-flex justify-content-between">
                <a class="text-warning" href="<?= \yii\helpers\Url::toRoute(['/file/product_example.xlsx']) ?>"><i
                            class="fa fa-download"></i> File dữ liệu mẫu</a>
                <div>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="button" data-action="product" class="handleData btn btn-primary">Nhập sản phẩm</button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php

$js =<<<JS
    initRemote("product-import");
JS;
$this->registerJs($js);