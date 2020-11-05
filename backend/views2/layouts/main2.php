<?php
$this->title = 'G-SOF - Dịch vụ hỗ trợ bán hàng xuyên biên giới';

use backend\assets\AppAsset2;
use yii\helpers\Html;
use yii\helpers\Url;

AppAsset2::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <meta name="description" content="Đồng hành cùng bạn mở rộng kinh doanh tại Đông Nam Á"/>
    <link rel="icon" href="/theme2/images/fa.png" sizes="16x16 32x32" type="image/png">
    <?php $this->head() ?>
    <script>
        var Action = {
            DELTE: 'del',
            ADD: 'add',
            UP: 'up',
            DOWN: 'down'
        }
        var config = {
            type: ["xlsx", "csv", "application/vnd.ms-excel", "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"],
            ajaxUpload: "<?= Url::toRoute(['ajax/ajax-file'])?>",
            pushContact: "<?= Url::toRoute(['ajax/push-contact'])?>",
            pushProduct: "<?=Url::toRoute(['ajax/push-product'])?>",
            pushOrder: "<?=Url::toRoute(['ajax/push-oder'])?>",
            pushZipcode: "<?=Url::toRoute(['ajax/push-zipcode'])?>",
            pushLogs: "<?=Url::toRoute(['ajax/push-logs'])?>",
            removeImages: "<?= Url::toRoute(['ajax/remove-image'])?>",
            drafImage: "<?= Url::toRoute(['ajax/draf-image'])?>",
            maxSize: 52428800,
            maxRowUpload: 500000,
            zipcodeAPI: "h94g7PyOk1NqmeTesbPlcXM6KDGj9ZI8EFcjA2jTcIcJHkt0tSa4gNhqI0QxNIEx",
            exportURL: "<?=Url::toRoute(['export/order'])?>",
            exportContactURL: "<?=Url::toRoute(['export/contact-all'])?>",
            blockOrder: "<?=Url::toRoute(['ajax/block-order'])?>",
            orderData: "<?=Url::toRoute(['ajax/order-data'])?>",
            billstranfer: "<?=Url::toRoute(['ajax/upload-bill'])?>",
            changeOrderStatus: "<?=Url::toRoute(['ajax/order-status'])?>",
            autoScan: "<?= Url::toRoute(['ajax/scan-contact'])?>",
            deleteAll: "<?= Url::toRoute(['ajax/delete-all'])?>",
            saveFormInfo: "<?= Url::toRoute(['ajax/form-info'])?>",
            findFormInfo: "<?= Url::toRoute(['ajax/find-form-info'])?>",
            updateContactWaiting: "<?= Url::toRoute(['ajax/update-contact-waiting'])?>",

            findCity: "<?= Url::toRoute(['ajax/find-city'])?>",
            exportWaitInfo: "<?= Url::toRoute(['ajax/export-wait-info'])?>",
            changeAddess: "<?= Url::toRoute(['ajax/change-address'])?>",
            ajaxProductSelect: "<?= Url::toRoute(['ajax/load-product-select'])?>",
            ajaxProduct: "<?= Url::toRoute(['ajax/load-product'])?>",
            loadSkus: "<?=  Url::toRoute(['ajax/load-sku'])?>",
            changeContactStatus: "<?= Url::toRoute(['ajax/change-contact-status'])?>",
            changeMultipleStatus: "<?= Url::toRoute(['ajax/change-multiple-status'])?>",
            acceptContact: "<?= Url::toRoute(['ajax/accept-contact'])?>",
            pushGoogleSheet: "<?= Url::toRoute(['ajax/push-google-sheet'])?>",
            analyticsReport: "<?= Url::toRoute(['/ajax-analytics'])?>",
            customerSearch: "<?= Url::toRoute(['/ajax/customer-search'])?>",
        }
    </script>
    <style>
        .ui-widget-content {
            margin-left: 16%;
            margin-top: 23%;
        }

        .grid-view th {
            white-space: nowrap;
        }

        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }

        @media (min-width: 768px) {
            .modal-xl {
                width: 90%;
                max-width: 1200px;
            }
        }
    </style>
    <?= $this->render('link')?>
</head>
<?php $this->beginBody() ?>
<body class="loading" data-layout='{"mode": "light", "width": "fluid", "menuPosition": "fixed", "sidebar": { "color": "light", "size": "default", "showuser": false}, "topbar": {"color": "dark"}, "showRightSidebarOnPageLoad": true}'>
<div id="wrapper">
    <?= $this->render("@backend/views2/parts/navtop") ?>
    <?= $this->render("@backend/views2/parts/navleft") ?>
    <div class="content-page">
        <div class="content">
            <div class="content-loading">
                <div class="spinner-grow text-success" role="status"></div>
            </div>
            <div class="container-fluid mt-3">
                <?= $content ?>
            </div>
        </div>
        <?= $this->render('@backend/views2/parts/foot2') ?>
    </div>
</div>

<?php $this->endBody() ?>
<?php if (Yii::$app->request->isPjax || Yii::$app->session->hasFlash('success')): ?>
    <script>
        toastr.success("<?= Yii::$app->session->getFlash('success') ?>");
    </script>
<?php endif; ?>
<?php if (Yii::$app->request->isPjax || Yii::$app->session->hasFlash('error')): ?>
    <script>
        toastr.warning("<?= Yii::$app->session->getFlash('error') ?>");
    </script>
<?php endif; ?>

</body>
</html>
<?php $this->endPage() ?>

