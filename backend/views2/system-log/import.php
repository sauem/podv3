<?php

use kartik\grid\GridView;
use yii\helpers\Html;

?>

    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h4 class="card-title">Lịch sử nhập liệu </h4>
            <?= \yii\bootstrap4\Html::button('Làm trống dữ liệu', ['class' => 'btn-sm resetImport btn btn-outline-success']) ?>
        </div>
        <div class="card-body">
            <?=
            \kartik\grid\GridView::widget([
                'dataProvider' => $dataProvider,
                'pjax' => true,
                'pjaxSettings' => [
                    'neverTimeout' => 'true',
                    'options' => [
                        'id' => 'pjax-import'
                    ]
                ],
                'columns' => [
                    ['class' => '\kartik\grid\SerialColumn'],
                    'name',
                    'line',
                    'message',
                    'created_at:date'
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
            text : "Xóa tất cả lịch sử nhập liệu?",
            showCancelButton: true,
        }).then((val) => {
            if(val.value){
                $.ajax({
                    url : "/ajax/remove-history-import",
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