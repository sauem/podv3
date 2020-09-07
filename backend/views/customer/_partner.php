<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use common\helper\Helper;
use kartik\grid\ActionColumn;
use common\helper\Component;
use yii\helpers\Url;

$this->title = 'Customers';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-8">
        <div class="ibox">
            <div class="ibox-body">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => [
                        'username',
                        'email',
                        'pic',
                        [
                            'class' => ActionColumn::class,
                            'template' => '{update}{delete}',
                            'buttons' => [
                                'update' => function ($url, $model) {
                                    $url = Url::toRoute(['partner', 'id' => $model->id]);
                                    return Component::update($url);
                                },
                                'delete' => function ($url, $model) {
                                    $url = Url::toRoute(['user/delete', 'id' => $model->id]);
                                    return Component::delete($url);
                                }
                            ]
                        ]
                    ]
                ]) ?>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="ibox">
            <div class="ibox-body">
                <h4 class="ibox-title">Tạo khách hàng</h4>
                <hr>
                <?= $this->render("_form_partner", ['model' => $model]) ?>
            </div>
        </div>
    </div>
</div>