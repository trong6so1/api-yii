<?php
return [
    'class' => 'yii\web\UrlManager',
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'rules' => [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v1/user/user', 'v1/post/post', 'v1/products/product', 'v1/cart/cart',
        'GET v1/product/product/<id:\d+>' => 'v1/product/product/view',
        'GET v1/post/post/<id:\d+>' => 'v1/post/post/view',
        'GET v1/user/user/<id:\d+>' => 'v1/user/user/view',
        'POST v1/user/user/signup' => 'v1/user/user/create',
    ]
];
