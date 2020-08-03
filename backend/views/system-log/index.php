<?php

use kartik\grid\GridView;
use yii\helpers\Html;

?>

<div class="ibox">
    <div class="ibox-head">
        <h2 class="ibox-title">Lịch sử thao tác tài khoản</h2>
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
