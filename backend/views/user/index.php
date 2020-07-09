<?php

use yii\helpers\Html;
use backend\models\UserModel;
use kartik\grid\CheckboxColumn;
use kartik\grid\GridView;
use common\helper\Component;
use kartik\grid\ActionColumn;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\UserSearchModel */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'User Models';
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="row">
        <div class="col-md-8">
            <div class="ibox">
                <div class="ibox-head">
                    <h2 class="ibox-title">Danh sách tài khoản</h2>
                    <div class="ibox-tools">
                        <a data-toggle="modal" data-target="#accountModal" class="btn btn-outline-success btn-sm"
                           href="">Tạo tài khoản</a>
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
                            'username',
                            'email:email',
                            ['attribute' => 'status', 'format' => 'html', 'value' => function ($model) {
                                return UserModel::label($model->status);
                            }],
                            ['attribute' => 'role', 'format' => 'html', 'value' => function ($model) {
                                    return $model->role->item_name;
                            }],
                            [
                                'class' => ActionColumn::class,
                                'template' => '{update}{delete}',
                                'buttons' => [
                                    'delete' => function ($url, $model) {
                                        return Component::delete($url);
                                    },
                                    'update' => function ($url, $model) {
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
            <div class="ibox table-responsive">
                <div class="ibox-head">
                    <h2 class="ibox-title">Nhật ký</h2>
                </div>
                <div class="ibox-body">

                </div>
            </div>
        </div>
    </div>
    <!--//Modal add new User-->
<?= $this->render('_modal', ['model' => $model]) ?>
<?php
$js == <<<JS
    
JS;
$this->registerJs($js);