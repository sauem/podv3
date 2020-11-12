<?php


namespace backend\assets;
use yii\web\AssetBundle;

class AppAsset2 extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        '/theme2/libs/jquery-toast-plugin/jquery.toast.min.css',
        '/theme2/libs/flatpickr/flatpickr.min.css',
        '/theme2/libs/multiselect/css/multi-select.css',
        '/theme2/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css',
        '/theme2/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css',
        '/theme2/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css',
        '/theme2/libs/datatables.net-select-bs4/css//select.bootstrap4.min.css',
        '/theme2/libs/select2/css/select2.min.css',
        '/theme2/libs/bootstrap-select/css/bootstrap-select.min.css',
        '/theme2/libs/selectize/css/selectize.bootstrap3.css',
        '/theme2/css/bootstrap.min.css',
        '/theme2/css/app.min.css',
        //'/theme2/css/bootstrap-dark.min.css',
        //'/theme2/css/app-dark.min.css',
        '/css/site.css?v=1.6',
        '/theme2/css/icons.min.css',
    ];
    public $js = [
        '/theme2/js/vendor.js',
        '/lib/js/handlebars-v4.7.6.js',
        '/lib/js/typeahead.js',
        '/lib/js/handlebars-helper.js?v=1.5',
        'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js',
        'https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js',
        'https://cdn.jsdelivr.net/npm/sweetalert2@9',
        'https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js',
        '/lib/vendors/moment/min/moment.min.js',
        '/theme2/libs/jquery-toast-plugin/jquery.toast.min.js',
        '/theme2/libs/selectize/js/standalone/selectize.min.js',
        '/theme2/libs/multiselect/js/jquery.multi-select.js',
        '/theme2/libs/select2/js/select2.min.js',
        '/theme2/libs/flatpickr/flatpickr.min.js',
        '/theme2/libs/chart.js/Chart.bundle.min.js',
        '/theme2/libs/bootstrap-select/js/bootstrap-select.min.js',
        '/theme2/libs/datatables.net/js/jquery.dataTables.min.js',
        '/theme2/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js',
        '/theme2/libs/datatables.net-responsive/js/dataTables.responsive.min.js',
        '/theme2/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js',
        '/theme2/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js',
        '/lib/js/money.js',
        '/lib/js/admin.js?v=3.0.2',
        '/js/excel/xlsx/dist/xlsx.full.min.js',
        '/js/site.js?v=2.9',
        '/js/order.js?v=2.9',
        '/theme2/js/report-chart.js?v=2.1',
        '/theme2/js/partner.js?v=3.5',
        '/theme2/js/app.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        //'yii\bootstrap\BootstrapAsset',
    ];
}