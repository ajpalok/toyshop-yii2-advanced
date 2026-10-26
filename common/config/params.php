<?php

declare(strict_types=1);

return [
    'adminEmail' => 'ajpalok@2haas.com',
    'supportEmail' => 'contact@2haas.com',
    'senderEmail' => 'noreply@2haas.com',
    'senderName' => 'ToyShop',
    'user.passwordResetTokenExpire' => 3600,
    'user.passwordMinLength' => 8,

    // to read these as: Yii::$app->params['shop.pageSize'] 
    'shop.pageSize' => 12,
    'shop.lowStockThreshold' => 5,
    'shop.maxQtyPerItem' => 10,
    'shop.deliveryCharge' => [
        'inside_dhaka' => 80,
        'outside_dhaka' => 120,
    ],
];
