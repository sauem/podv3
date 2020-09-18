<?php

use kartik\form\ActiveForm;
use yii\bootstrap4\Html;
use kartik\grid\GridView;

?>
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Cài đặt hệ thống</h4>
                </div>
                <div class="card-body">
                    <?php $form = ActiveForm::begin() ?>
                    <?= $form->field($model, 'backup_time') ?>
                    <?= $form->field($model, 'delete_time') ?>
                    <?= $form->field($model, 'rescan_contact_time') ?>
                    <?= $form->field($model, 'map_api') ?>
                    <?= $form->field($model, 'drive_id') ?>
                    <?= $form->field($model, 'limit_call') ?>
                    <small class="text-danger">Thư mục phải share quyền cho email : <a
                            href="mailto:nguyendinhthang.go97@gmail.com">nguyendinhthang.go97@gmail.com</a></small>
                    <div class="text-right">
                        <?= Html::submitButton("Lưu cài đặt", ['class' => 'btn btn-outline-success']) ?>
                    </div>
                    <?php ActiveForm::end() ?>

                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4 class="card-title">Cài đặt hệ thống</h4>
                    <?= Html::button('<i class="fa fa-undo"></i> Cập nhật', ['class' => 'saveData btn btn-sm btn-outline-success']) ?>
                </div>
                <div class="card-body">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'pjax' => true,
                        'pjaxSettings' => [
                            'neverTimeout' => true,
                            'options' => [
                                'id' => 'pjax-sql'
                            ]
                        ],
                        'columns' => [
                            [
                                'class' => \kartik\grid\CheckboxColumn::class
                            ],
                            'name',
                            'created_at',
                            [
                                'class' => \kartik\grid\ActionColumn::class,
                                'template' => '{delete}',
                                'buttons' => [
                                    'delete' => function ($url, $model) {
                                        $url = \yii\helpers\Url::toRoute(['delete-backup', 'id' => $model->id]);
                                        return \common\helper\Component::delete($url);
                                    }
                                ]
                            ]
                        ]
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
<?php

$js = <<<JS
    $(".saveData").click(function() {
        swal.fire({
            title : "Đang thực hiện....",
            icon : 'info',
            onBeforeOpen : function() {
                swal.showLoading()
                $.ajax({
                    url : '/ajax/reload-backup',
                    type : 'POST',
                    data : {},
                    success : function(res) {
                        if(res.success){
                            toastr.success(res.msg);
                            __reloadData();
                             swal.close()
                            return false;
                           
                        }
                        toastr.warning(res.msg);
                    }
                })
            }
        })
    });
JS;
$this->registerJs($js);