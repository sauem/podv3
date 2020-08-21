<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\form\ActiveForm;
use common\helper\Component;
use yii\grid\ActionColumn;
use yii\helpers\Url;

$this->title = 'Zipcode Countries';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-5">
        <div class="ibox">
            <div class="ibox-head">
                <h2 class="ibox-title">Tạo mới mã địa điểm</h2>
                <div class="ibox-tools">
                     <button class="btn btn-info btn-sm" data-toggle="modal"
                              data-remote="<?= Url::toRoute(['import']) ?>"
                              data-target="#zipcode-import">
                        <i class="fa fa-file-excel-o"></i>
                        Nhập dữ liệu
                    </button>
                </div>
            </div>
            <div class="ibox-body">
                <?php $form = ActiveForm::begin() ?>
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'country_code')->dropDownList(
                            ArrayHelper::map(Yii::$app->params['country'], "code", "name"),
                            ['class' => 'select2'])->label("Quốc gia") ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'zipcode')->label("Mã bưu chính (zipcode)") ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'city')->label("Tỉnh/Thành phố") ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'district')->label("Quận/Huyện") ?>
                    </div>
                    <div class="col-md-12">
                        <?= $form->field($model, 'address')->textarea(['rows' => 2])->label("Địa chỉ") ?>
                    </div>
                    <div class="col-12 text-right">
                        <?= Component::reset("Hủy") ?>
                        <?= Html::submitButton("Lưu", ['class' => 'btn btn-success']) ?>
                    </div>
                </div>
                <?php ActiveForm::end() ?>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="ibox">
            <div class="ibox-head">
                <h2 class="ibox-title">Danh sách mã</h2>
            </div>
            <div class="ibox-body">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => [
                        [
                            'label' => 'Quốc gia/Code',
                            'attribute' => 'country_name',
                            'format' => 'html',
                            'value' => function ($model) {
                                $htm = "{$model->country_name}<hr>";
                                $htm .= "{$model->country_code} | {$model->zipcode}";
                                return $htm;
                            }
                        ],
                        'city',
                        'disctrict',
                        'address',
                        [
                            'class' => ActionColumn::className(),
                            'template' => '{update}{delete}',
                            'headerOptions' => [
                                'width' => '12%'
                            ],
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
</div>

<div class="modal fade" tabindex="-1" id="zipcode-import" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nhập dữ liệu</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer d-flex justify-content-between">
                <a class="text-warning" href="<?= \yii\helpers\Url::toRoute(['/file/example_zipcode.xlsx']) ?>"><i
                            class="fa fa-download"></i> File dữ liệu mẫu</a>
                <div>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="button" data-action="zipcode" class="btn handleData btn-primary">Nhập sản phẩm
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$js =<<<JS
    initRemote("zipcode-import");
JS;
$this->registerJs($js);