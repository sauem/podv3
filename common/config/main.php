<?php

use mdm\admin\Module;
use yii\queue\redis\Queue;

return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'bootstrap' => [
        'rbac',
        'queue',
        'log'
    ],
    'modules' => [
        'rbac' => [
            'class' => Module::class,
            'layout' => 'right-menu',
            'mainLayout' => '@backend/views2/layouts/main2.php',
        ],
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => SQL_HOST,
            'username' => SQL_USER_NAME,
            'password' => SQL_PASSWORD,
            'charset' => 'utf8',
            'enableSchemaCache' => YII_DEBUG ? false : true,
            'schemaCacheDuration' => 3600,
            'schemaCache' => 'cache',
            'queryCache' => 'cache',
            'enableQueryCache' => YII_DEBUG ? false : true,
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            'useFileTransport' => true,
        ],
//        'redis' => [
//            'class' => 'yii\redis\Connection',
//            'hostname' => REDIS_HOST,
//            'port' => REDIS_PORT,
//            'password' => REDIS_PASS,
//            'database' => 0,
//            'retries' => 1,
//        ],
        'queue' => [
            'class' => Queue::class,
            'redis' => 'redis',
            'channel' => 'queue',
            'as log' => \yii\queue\LogBehavior::class,
        ],
//        'assetManager' => [
//            'bundles' => [
//                'yii\web\JqueryAsset' => [
//                    'js' => [
//                        '/theme2/js/vendor.js',
//                        '/theme2/libs/chart.js/Chart.bundle.min.js'
//                    ]
//                ],
//                'yii\bootstrap\BootstrapAsset' => [
//                    'sourcePath' => null,
//                    'css' => [
//                        '/theme2/css/bootstrap.min.css'
//                    ],
//                    'js' => [
//                        '/theme2/js/vendor.js'
//                    ],
//                ],
//            ],
//        ],

        'formatter' => [
            'class' => \yii\i18n\Formatter::class,
            'thousandSeparator' => ',',
            'decimalSeparator' => '.',
            'currencyCode' => 'VND',
        ],
    ],
];
