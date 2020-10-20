<?php

use yii\helpers\Url;
use backend\models\UserModel;
use yii\helpers\Html;
use yii\widgets\Pjax;

$user = Yii::$app->user;
?>


<?php
Pjax::begin([
    'id' => 'pjax-info'
]) ?>

    <div class="card-box">
        <ul class="nav nav-tabs nav-bordered tabs-line">
            <li class="nav-item">
                <a class="nav-link active" href="#wating" data-toggle="tab">
                    <i class="ti-bar-chart"></i>
                    Lần gọi 1
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#callback" data-toggle="tab">
                    <i class="ti-time"></i> Lần gọi 2
                </a>
            </li>
        </ul>
        <div class="tab-content">
            <?= $this->render("order_form") ?>
            <div class="tab-pane fade show active" id="wating">
                <?= $this->render("tab/first_call",
                    [
                        'dataProvider' => $dataProvider,
                        'callbackProvider' => $callbackProvider,
                        'failureProvider' => $failureProvider,
                        'successProvider' => $successProvider,
                        'currentHistories' => $currentHistories,
                        'user' => $user,
                        'info' => $info
                    ]) ?>

            </div>
            <div class="tab-pane fade" id="callback">
                <?= $this->render("tab/second_call",
                    [
                        'dataProvider' => $_dataProvider,
                        'callbackProvider' => $_callbackProvider,
                        'failureProvider' => $_failureProvider,
                        'successProvider' => $_successProvider,
                        'currentHistories' => $_currentHistories,
                        'user' => $user,
                        'info' => $_info
                    ]) ?>

            </div>
        </div>
    </div>

<?php Pjax::end() ?>
<?php
