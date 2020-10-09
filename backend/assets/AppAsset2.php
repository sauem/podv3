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
        '/theme2/libs/select2/css/select2.min.css',
        '/theme2/libs/bootstrap-select/css/bootstrap-select.min.css',
        '/theme2/libs/selectize/css/selectize.bootstrap3.css',
        '/theme2/css/bootstrap.min.css',
        '/theme2/css/app.min.css',
        //'/theme2/css/bootstrap-dark.min.css',
        //'/theme2/css/app-dark.min.css',
        '/css/site.css?v=1.2',
        '/theme2/css/icons.min.css',
    ];
    public $js = [
        '/theme2/js/vendor.js',
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
        '/lib/js/money.js',
        '/lib/js/handlebars-v4.7.6.js',
        '/lib/js/handlebars-helper.js?v=1.5',
        '/lib/js/admin.js?v=2.23',
        '/js/excel/xlsx/dist/xlsx.full.min.js',
        '/js/site.js?v=2.9',
        '/theme2/js/report-chart.js?v=1.4',
        '/theme2/js/app.min.js'


    ];
    public $depends = [
        'yii\web\YiiAsset',
        //'yii\bootstrap\BootstrapAsset',
    ];
}