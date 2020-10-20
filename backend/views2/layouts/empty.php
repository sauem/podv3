<?php

/* @var $this \yii\web\View */

/* @var $content string */

use yii\helpers\Url;
use backend\assets\AppAsset2;
use yii\helpers\Html;

AppAsset2::register($this);
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
            ajaxUpload: "<?= Url::toRoute(['ajax/ajax-file'])?>",
            pushContact: "<?= Url::toRoute(['ajax/push-contact'])?>",
            pushProduct: "<?=Url::toRoute(['ajax/push-product'])?>",
            pushOrder: "<?=Url::toRoute(['ajax/push-order'])?>",
            pushZipcode: "<?=Url::toRoute(['ajax/push-zipcode'])?>",
            pushLogs: "<?=Url::toRoute(['ajax/push-logs'])?>",
            pushCategories: "<?= Url::toRoute(['ajax/push-categories'])?>",
            maxSize: 52428800,
            maxRowUpload: 500000,
            saveFormInfo: "<?= Url::toRoute(['ajax/form-info'])?>",
            updateContactWaiting: "<?= Url::toRoute(['ajax/update-contact-waiting'])?>",
        }

    </script>
    <?= $this->render('link')?>
</head>
<body class="fixed-navbar">
<?php $this->beginBody() ?>
<div class="page-wrapper">
    <?= $content ?>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
