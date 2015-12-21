<?php

return [
    'components' => [
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'qq' => [
                    'class' => 'app\modules\member\authclients\QqOAuth',
                    'clientId' => '101269915',
                    'clientSecret' => 'd1555b83c4fbc5aa81be4dab9bd3acf8'
                ],
                'wb' => [
                    'class' => 'app\modules\member\authclients\WbOAuth',
                    'clientId' => '3673260080',
                    'clientSecret' => 'e087b7e39d0ae7f1cceb751eb6cf8ce1'
                ],
                'wx' => [
                    'class' => 'app\modules\member\authclients\WxOAuth',
                    'clientId' => 'wx03c3c0522401b2b2',
                    'clientSecret' => 'caf61f62271069eba112302d482b380d'
                ],
            ],
        ],
    ],
    'params' => [

    ]
];