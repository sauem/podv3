<?php

use yii\helpers\Html;
use backend\models\UserModel;
use kartik\grid\CheckboxColumn;
use kartik\grid\GridView;
use common\helper\Component;
use kartik\grid\ActionColumn;
use kartik\form\ActiveForm;
use backend\models\AuthItem;
use backend\models\AuthAssignment;
use common\helper\Helper;

$this->title = 'User Models';
$this->params['breadcrumbs'][] = $this->title;

use yii\helpers\ArrayHelper;
use yii\helpers\Url;

?>
    <div class="row">

        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4 class="card-title">Danh sách tài khoản</h4>
                    <div class="card-tools">
                        <a class="btn btn-outline-success btn-sm" data-toggle="modal" href="#user-create"><i
                                    class="fe-user-plus"></i> Tạo tài khoản</a>
                        <a class="btn btn-outline-primary btn-sm" data-toggle="collapse" href="#filter"><i
                                    class="fe-search"></i> Tìm kiếm</a>
                    </div>
                </div>
                <div class="card-body">
                    <?= $this->render('_search', ['model' => $searchModel]) ?>
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'responsive' => true,
                        'columns' => [
                            [
                                'attribute' => 'username',
                                'format' => 'html',
                                'value' => function ($model) {
                                    $html = $model->username . " | " . $model->userRole->item_name . "<hr>";
                                    if ($model->country) {
                                        $html .= $model->country . "|" . Helper::getCountry($model->country);
                                    }
                                    return $html;
                                }
                            ],
                            [
                                'label' => 'email',
                                'format' => 'html',
                                'attribute' => 'email',
                                'headerOptions' => [
                                    'width' => '10%'
                                ],
                                'value' => function ($model) {
                                    return Html::a($model->email, "mailto:$model->email");
                                }
                            ],
                            'phone_of_day',
                            ['attribute' => 'status', 'format' => 'html', 'value' => function ($model) {
                                return UserModel::label($model->status);
                            }],
                            [
                                'class' => ActionColumn::class,
                                'template' => '{update}{delete}',
                                'width' => '250px',
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


    <div class="modal fade" tabindex="-1" id="user-create" role="dialog">
        <div class="modal-dialog  modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tạo tài khoản mới</h5>
                </div>
                <div class="modal-body">
                    <?= $this->render('form', ['model' => $model]) ?>
                </div>
                <div class="modal-footer d-flex justify-content-end">
                    <div class="btn-group">

                        <button form="userActiveForm" class="btn btn-sm btn-success">
                            <i class="fe-download-cloud"></i> Lưu
                        </button>
                        <button

                                class="btn btn-sm btn-secondary"
                                data-dismiss="modal">
                            <i class="fe-x"></i> Đóng
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
$url = Url::toRoute(['index']);
$js = <<<JS
    $(document).ready(function() {
        let id = (new URL(window.location.href)).searchParams.get("id");
        if(id){
            $("#user-create").modal({backdrop : 'static'});
        }
        
        $("#user-create").on("hidden.bs.modal",function() {
           if(id){
                window.location.replace("$url");
           }
        });
    });
JS;
$this->registerJs($js);