<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/params.php'
);

return [
    'id' => 'app-backend',
    'timeZone' => 'Asia/Ho_Chi_Minh',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'layoutPath' => '@backend/views2/layouts',
    'layout' => 'main2',
    'bootstrap' => [
        'log'
    ],
    'modules' => [
        'gii' => [
            'class' => 'yii\gii\Module',
        ],
        'api' => [
            'class' => \backend\modules\api::class,
        ],
        'gridview' => [
            'class' => '\kartik\grid\Module',
            'bsVersion' => '4.x'
        ],
        'actionlog' => [
            'class' => 'cakebake\actionlog\Module',
        ],
        'settings' => [
            'class' => 'yii2mod\settings\Module',
        ],
    ],
    'components' => [
        'view' => [
            'theme' => [
                'basePath' => '@backend/views2',
                'baseUrl' => '@backend/views2',
                'pathMap' => [
                    '@backend/views' => '@backend/views2',
                ],
            ]
        ],
        'settings' => [
            'class' => 'yii2mod\settings\components\Settings',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager', // or use 'yii\rbac\DbManager'
        ],
        'request' => [
            'csrfParam' => '_csrf-backend',
            'cookieValidationKey' => COOKIE_VALID_BACKEND,
            'enableCsrfValidation' => false,
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages', // if advanced application, set @frontend/messages
                    'sourceLanguage' => 'en',
                    'fileMap' => [
                        //'main' => 'main.php',
                    ],
                ],
            ],
        ],
    ],
    'as access' => [
        'class' => \mdm\admin\components\AccessControl::class,
        'allowActions' => [
            'api/*',
           // 'ajax/*',
            'rbac/*',
            'user/*',
            'zipcode-country/*',
            'site/logout',
            'report/*',
           // 'export/*',
            'system-log/*'
        ]
    ],
    'params' => $params,
];
