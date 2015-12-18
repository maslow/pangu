<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language' => 'zh-CN',
    'components' => [
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'qq' => [
                    'class'=>'app\common\components\QqOAuth',
                    'clientId'=>'101269915',
                    'clientSecret'=>'d1555b83c4fbc5aa81be4dab9bd3acf8'
                ],
                'wb' => [
                    'class'=>'app\common\components\WbOAuth',
                    'clientId'=>'3673260080',
                    'clientSecret'=>'e087b7e39d0ae7f1cceb751eb6cf8ce1'
                ],
                'wx' => [
                    'class'=>'app\common\components\WxOAuth',
                    'clientId'=>'wx03c3c0522401b2b2',
                    'clientSecret'=>'caf61f62271069eba112302d482b380d'
                ],
            ],
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'vip-design.net',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'rules' => [
            ],
        ],
        'assetManager'=>[
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'js' => [
                        YII_ENV_DEV ? 'jquery.js' : 'jquery.min.js'
                    ]
                ],
                'yii\bootstrap\BootstrapAsset' => [
                    'css' => [
                        YII_ENV_DEV ? 'css/bootstrap.css' : 'css/bootstrap.min.css',
                    ]
                ],
                'yii\bootstrap\BootstrapPluginAsset' => [
                    'js' => [
                        YII_ENV_DEV ? 'js/bootstrap.js' : 'js/bootstrap.min.js',
                    ]
                ],
            ],
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.126.com',
                'username' => 'sender@126.com',
                'password' => 'your password',
                'port' => '25',
                'encryption' => 'tls',
            ],
            'messageConfig' => [
                'charset' => 'UTF-8',
                'from' => 'sender@vip-design.com',
                //'bcc' => 'developer@mydomain.com',
            ]
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
        'db' => require(__DIR__ . '/db.php'),
    ],
    'params' => $params,

];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug']['class'] = 'yii\debug\Module';
    $config['modules']['debug']['allowedIPs'] = ['*'];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii']['class'] = 'yii\gii\Module';
    $config['modules']['gii']['allowedIPs'] = ['*'];
}

//Load the configuration file of modules
$modulesConfig = file_get_contents(__DIR__ . '/modules.php');
$modules = unserialize($modulesConfig);
foreach ($modules as $id => $m) {
    if ($m['bootstrap'] == true) {
        $config['bootstrap'][] = $id;
    }
    $config['modules'][$id]['class'] = $m['class'];
    foreach($m['i18n'] as $transName => $transConfig){
        $config['components']['i18n']['translations'][$transName] = $transConfig;
    }
}
return $config;
