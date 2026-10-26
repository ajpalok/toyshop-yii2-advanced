<?php

declare(strict_types=1);

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php',
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'user' => [
            'identityClass' => \common\models\User::class,
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'toyshop-advanced-frontend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => \yii\log\FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

        // Routes for the frontend application
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '' => 'site/index',
                'toys' => 'toy/index',
                'toys/category/<category:[a-z0-9\-]+>' => 'toy/category',
                'toy/<slug:[a-z0-9\-]+>' => 'toy/view',

                'cart' => 'cart/index',
                'POST cart/add' => 'cart/add',
                'POST cart/remove' => 'cart/remove',

                'checkout' => 'checkout/index',
                'orders' => 'order/index',
                'order/<number:[A-Z0-9]+>' => 'order/view',

                'login' => 'site/login',
                'logout' => 'site/logout',
                'signup' => 'site/signup',
            ],
        ],
    ],
    'params' => $params,
];
