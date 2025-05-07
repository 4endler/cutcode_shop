<?php
//Пример с курса

return [
    'providers' => [
        'unitpay' => [
            'public_key' => env('UNITPAY_PUBLIC_KEY'),
            'secret_key' => env('UNITPAY_SECRET_KEY'),
            'url' => env('UNITPAY_URL'),
        ],
        'youkassa' => [
            'login' => env('YOOKASSA_LOGIN'),
            'password' => env('YOOKASSA_PASSWORD'),
            // 'shop_id' => env('YOOKASSA_SHOP_ID'),
            // 'secret_key' => env('YOOKASSA_SECRET_KEY'),
            // 'url' => env('YOOKASSA_URL'),
        ],
    ],
];