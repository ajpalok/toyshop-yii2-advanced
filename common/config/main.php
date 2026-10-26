<?php

declare(strict_types=1);

return [
    'name' => 'ToyShop',
    'language' => 'en-US',
    'timeZone' => 'Asia/Dhaka',

    'bootstrap' => [
        \common\bootstrap\MailerBootstrap::class,
    ],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'db' => [
            'class' => \yii\db\Connection::class,
            'dsn' => $_ENV['DB_DSN'],
            'username' => $_ENV['DB_USER'],
            'password' => $_ENV['DB_PASS'],
            'charset' => 'utf8mb4',
            'enableSchemaCache' => true,
            'schemaCacheDuration' => 3600,
        ],
        'cache' => [
            'class' => \yii\caching\FileCache::class,
            'cachePath' => '@common/runtime/cache',
        ],
        'formatter' => [
            'class' => \yii\i18n\Formatter::class,
            'locale' => 'en-BD',
            'dateFormat' => 'php:Y-m-d',
            'datetimeFormat' => 'php:Y-m-d H:i:s',
            'timeFormat' => 'php:H:i:s',
            'currencyCode' => 'BDT',
        ],
    ],
];
