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
//            'apiKey' => 'AAAA4NFmX6Q:APA91bHovYUriwb-X78Kav4A6NvjyLL2baFAZTd8JBgIE1nGj1zR2RsM6BmdQ4tK2iQUDE1o7VdsxPSUxaiZ_7DdJPIkrcy8qg1TFN8s0bMoNDwuIWjPYCCP7vG165551jBSNdr9D6gb',
            'apiKey' => $_SERVER['FCM'],
//            'apiKey' => 'AAAA4NFmX6Q:APA91bE_FP5-Pu0ca1hnnYLPWBFMhlAOP2FS8gD7VKwzJ6zE3qy4L3coCvunQiLYIwoeG1ALItcCQKx2ySrmJNu4_8IvhDXvo7qWbwuT9smkmhArpoDq_etlAT0JyYMIMGx-LEaBXkU9',
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
