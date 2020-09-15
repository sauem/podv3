<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\grid\CheckboxColumn;
use kartik\form\ActiveForm;
use kartik\grid\ActionColumn;
use common\helper\Component;
use yii\helpers\Url;
use backend\models\CategoriesModel;
use kartik\select2\Select2;

$this->title = 'Quản lý sản phẩm';
$this->params['breadcrumbs'][] = $this->title;

?>
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4 class="card-title">Danh sách sản phẩm</h4>
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
                                'format' => 'html',
                                'value' => function ($model) {
                                    return "<strong><i>$model->sku</i></strong> | $model->name ";
                                }
                            ],
                            'category_id',
                            [
                                'label' => 'Ngày tạo',
                                'attribute' => 'created_at',
                                'value' => function ($model) {
                                    return date('d/m/Y', $model->created_at);
                                }
                            ],
                            [
                                'class' => ActionColumn::class,
                                'template' => '{update}{delete}',
                                'width' => '200px',
                                'buttons' => [
                                    'delete' => function ($url, $model) {
                                        return Component::delete($url);
                                    },
                                    'update' => function ($url, $model) {
                                        $url = \yii\helpers\Url::toRoute(['index', 'id' => $model->id]);
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
                <div class="card-header justify-content-between d-flex">
                    <h4 class="card-title">Tạo sản phẩm</h4>
                    <div class="card-tools">
                        <button class="btn btn-success btn-sm" data-toggle="modal"
                                data-remote="<?= Url::toRoute(['products/import']) ?>"
                                data-target="#product-import">
                            <i class="fa fa-file-excel-o"></i>
                            Nhập sản phẩm
                        </button>
                    </div>
                </div>
                <?php $form = ActiveForm::begin() ?>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <?= $form->field($model, 'name')->label('Tên sản phẩm') ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'sku')->label('Mã sản phẩm') ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'category_id')
                                ->widget(Select2::className(), [
                                    'data' => CategoriesModel::select(),
                                    'theme' => Select2::THEME_DEFAULT,
                                    'options' => [
                                        'prompt' => 'Chọn loại sản phẩm'
                                    ]
                                ])
                                ->label('loại sản phẩm ') ?>
                        </div>

                        <div class="col-md-12">
                            <?= $form->field($model, 'option')->textarea(['rows' => 10])->label('Tùy chỉnh (Mỗi thuộc tính xuống dòng)') ?>
                        </div>

                    </div>
                    <div class="text-right">
                        <?= Component::reset() ?>
                        <?= Html::submitButton("<i class='fe-save'></i> Lưu", ['class' => 'btn btn-sm btn-success']) ?>
                    </div>
                </div>
                <?php ActiveForm::end() ?>
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
                    <a class="text-warning" href="<?= Url::toRoute(['/file/product_example.xlsx']) ?>"><i
                                class="fa fa-download"></i> File dữ liệu mẫu</a>
                    <div>
                        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Đóng</button>
                        <button type="button" data-action="product" class="handleData btn-sm btn btn-primary">Nhập sản phẩm
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php

$js = <<<JS
    initRemote("product-import");
JS;
$this->registerJs($js);