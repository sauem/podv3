<?php

use kartik\grid\GridView;
use yii\helpers\Html;

?>

    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h4 class="card-title">Lịch sử thao tác tài khoản</h4>
            <?= \yii\bootstrap4\Html::button('Làm trống dữ liệu', ['class' => 'btn-outline-danger btn-sm resetImport btn ']) ?>

        </div>
        <div class="card-body">
            <?= $this->render("_search", ['model' => $searchModel]) ?>
            <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
                'pjax' => true,
                'pjaxSettings' => [
                    'neverTimeout' => true,
                    'options' => [
                        'id' => 'pjax-sys'
                    ]
                ],
                'columns' => [
                    'time',
                    'category',
                    'action',
                    [
                        'attribute' => 'user_id',
                        'value' => function ($model) {
                            $user = \backend\models\UserModel::findOne($model->user_id);
                            if ($user) {
                                return $user->username;
                            }
                            return null;
                        }
                    ],
                    [
                        'attribute' => 'status',
                        'format' => 'html',
                        'value' => function ($model) {
                            return Html::tag("span", $model->status, ['class' => "badge badge-$model->status"]);
                        }
                    ],
                    [
                        'attribute' => 'message',
                        'format' => 'html',
                        'value' => function ($model) {
                            return unserialize($model->message);
                        }
                    ]
                ]
            ])
            ?>
        </div>
    </div>

<?php
$js = <<<JS
    $(".resetImport").click(function() {
        swal.fire({
            title :'Thông báo!',
            icon : "warning",
            text : "Xóa tất cả lịch sử hệ thống?",
            showCancelButton: true,
        }).then((val) => {
            if(val.value){
                $.ajax({
                    url : "/ajax/remove-history-system",
                    data : {},
                    type : "POST",
                    success : function(res) {
                      if(res.success){
                          toastr.success("Xóa dữ liệu thành công!");
                          __reloadData();
                          return;
                      }
                      toastr.warning(res.msg);
                    }
                });
            }
        })
    });
JS;
$this->registerJs($js);