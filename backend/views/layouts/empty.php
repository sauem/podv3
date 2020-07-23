<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <script>
        var config = {
            type: ["xlsx", "csv", "application/vnd.ms-excel", "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"],
            ajaxUpload:  "<?= \yii\helpers\Url::toRoute(['ajax/ajax-file'])?>",
            pushContact: "<?= \yii\helpers\Url::toRoute(['ajax/push-contact'])?>",
            pushProduct: "<?= \yii\helpers\Url::toRoute(['ajax/push-product'])?>",
            maxSize: 10485760
        }

    </script>
</head>
<body class="fixed-navbar">
<?php $this->beginBody() ?>
<div class="page-wrapper">
    <?= $content?>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
