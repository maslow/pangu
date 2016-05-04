<?php

Yii::setAlias('@tests', dirname(__DIR__) . '/tests');

$params = require(__DIR__ . '/params.php');
$db = null;
if(file_exists(__DIR__.'/db.php')){
    $db = require(__DIR__ . '/db.php');
}

$config = [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'gii'],
    'controllerNamespace' => 'app\commands',
    'modules' => [
        'gii' => 'yii\gii\Module',
    ],
    'components' => [
        'moduleManager'=>[
            'class'=>'app\base\ModuleManager',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
    ],
    'params' => $params,
];

// 加载模块配置，是为了读取模块的i18n配置，module/create命令中运行gii生成crud时，需要读取i18n配置
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
