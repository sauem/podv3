<?php

use kartik\grid\GridView;
use yii\helpers\Html;

?>

<div class="ibox">
    <div class="ibox-head">
        <h2 class="ibox-title">Lịch sử thao tác tài khoản</h2>
        <?= \yii\bootstrap4\Html::button('Làm trống dữ liệu', ['class' => 'resetImport btn btn-secondary']) ?>

    </div>
    <div class="ibox-body">
        <?= $this->render("_search", ['model' => $searchModel]) ?>
        <?=
        GridView::widget([
            'dataProvider' => $dataProvider,
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