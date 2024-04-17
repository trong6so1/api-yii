<?php
return [
    'class' => 'yii\web\UrlManager',
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'rules' => [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v1/users/users', 'v1/posts/posts', 'v1/products/products', 'v1/cart/cart',
        'GET v1/products/products/<id:\d+>' => 'v1/products/products/view',
        'GET v1/posts/posts/<id:\d+>' => 'v1/posts/posts/view',
        'GET v1/users/users/<id:\d+>' => 'v1/users/users/view',
        'POST v1/users/users/signup' => 'v1/users/users/create',

    ]
];
