<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\form\ActiveForm;
use backend\models\UserModel;
use yii\helpers\Url;
$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'User Models', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
    <div class="row">
        <div class="col-md-6">
            <div class="ibox">
                <div class="ibox-head">
                    <h2 class="ibox-title">Chi tiết tài khoản</h2>
                </div>
                <div class="ibox-body">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'username',
                            'email:email',
                            'created_at:datetime',
                            'updated_at:datetime',
                        ],
                    ]) ?>

                    <?php
                    $form = ActiveForm::begin([
                       // 'id' => 'accountForm',
                        'method' => 'POST',
                        'action' => Url::toRoute(['change-password'])
                    ])
                    ?>
                    <?= $form->field($changePass, 'oldPassword')->label("Mật khẩu cũ") ?>
                    <?= $form->field($changePass, 'newPassword')->label("Mật khẩu mới") ?>
                    <?= $form->field($changePass, 'retypePassword')->label("Nhập lại mật khẩu mới") ?>
                    <?= Html::submitButton("Lưu", ['class' => 'btn btn-success']) ?>
                    <?php
                    \kartik\form\ActiveForm::end()
                    ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="ibox">
                <div class="ibox-head">
                    <h2 class="ibox-title">Lịch sử tài khoản</h2>
                </div>

            </div>
        </div>
    </div>
<?php

$js = <<<JS
    // $(document).on("beforeSubmit","#accountForm",function(e) {
    //     e.preventDefault();
    //         let _form  = new FormData($(this)[0]);
    //         let _action = $(this).attr("action");
    //         $.ajax({
    //             url : _action,
    //             type : 'POST',
    //             data : _form,
    //             cache : false,
    //             success : function(res) {
    //                 console.log(res);
    //             }
    //         })
    //     return false;
    // })
JS;
$this->registerJs($js);
?>