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
    <link href="/lib/css/pages/auth-light.css" rel="stylesheet" />
</head>
<body class="bg-silver-300">
<?php $this->beginBody() ?>
<div class="content">
    <?= $content?>
</div>
<!-- BEGIN PAGA BACKDROPS-->
<div class="sidenav-backdrop backdrop"></div>
<div class="preloader-backdrop">
    <div class="page-preloader">Loading</div>
</div>

<?php $this->endBody() ?>
<?php if (Yii::$app->session->hasFlash('success')): ?>
    <script>
        toastr.success("<?= Yii::$app->session->getFlash('success') ?>");
    </script>
<?php endif; ?>
<?php if (Yii::$app->session->hasFlash('error')): ?>
    <script>
        toastr.warning("<?= Yii::$app->session->getFlash('error') ?>");
    </script>
<?php endif; ?>

</body>
</html>
<?php $this->endPage() ?>
