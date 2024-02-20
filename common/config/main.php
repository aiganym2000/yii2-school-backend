<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'bootstrap' => ['log'],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'authManager' => [
            'class' => 'common\components\rbac\AuthManager',
            'itemFile' => '@common/components/rbac/items/items.php',
            'assignmentFile' => '@common/components/rbac/items/assignments.php',
            'ruleFile' => '@common/components/rbac/items/rules.php',
        ],
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'timeZone' => 'Europe/Moscow',
            'dateFormat' => 'php:d.m.Y',
            'datetimeFormat' => 'php:d.m.Y H:i',
            'timeFormat' => 'php:H:i:s',
            'thousandSeparator' => '',
            'decimalSeparator' => '.',
        ],
        'assetManager' => [
            'linkAssets' => true,
            'appendTimestamp' => true,
//            'bundles' => [
//                'yii\web\JqueryAsset' => [
//                    'js' => []
//                ],
//            ],
//            'bundles' => [
//                'yii\bootstrap\YiiAsset' => [
//                    'js'=>[]
//                ],
//            ],
        ],
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                ],
                'app' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                ],
            ],
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => $_SERVER['SMTP_HOST'],
                'username' => $_SERVER['SMTP_USER'],
                'password' => $_SERVER['SMTP_PASSWORD'],
                'port' => $_SERVER['SMTP_PORT'],
            ],
        ],
        'fcm' => [
            'class' => 'understeam\fcm\Client',
            'apiKey' => $_SERVER['FCM'],
        ],
        'fcmAdmin' => [
            'class' => 'understeam\fcm\Client',
            'apiKey' => $_SERVER['FCM_ADMIN'],
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=' . $_SERVER['DB_HOST'] . ';dbname=' . $_SERVER['DB_NAME'],
            'username' => $_SERVER['DB_USER'],
            'password' => $_SERVER['DB_PASSWORD'],
            'charset' => 'utf8',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning', 'info'],
                ],
            ],
        ],
    ],
];
