<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
       // 'https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css',
        'https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css',
        'https://use.fontawesome.com/releases/v5.3.1/css/all.css',
        '/lib/vendors/bootstrap/dist/css/bootstrap.min.css',
        '/lib/vendors/font-awesome/css/font-awesome.min.css',
        '/lib/vendors/themify-icons/css/themify-icons.css',
        '/lib/vendors/jvectormap/jquery-jvectormap-2.0.3.css',
        '/lib/css/main.min.css'
    ];
    public $js = [
      //  '/lib/vendors/jquery/dist/jquery.min.js',
        '/lib/vendors/popper.js/dist/umd/popper.min.js',
        '/lib/vendors/bootstrap/dist/js/bootstrap.min.js',
        '/lib/vendors/metisMenu/dist/metisMenu.min.js',
        '/lib/vendors/jquery-slimscroll/jquery.slimscroll.min.js',
        '/lib/js/app.min.js',
        'https://cdn.jsdelivr.net/npm/sweetalert2@9',
        'https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js',
        '/lib/js/scripts/dashboard_1_demo.js',

    ];
    public $depends = [
        'yii\web\YiiAsset',
      // 'yii\bootstrap\BootstrapAsset',
    ];
}
