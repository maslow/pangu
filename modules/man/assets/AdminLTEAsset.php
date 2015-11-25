<?php

namespace app\modules\man\assets;

use yii\web\AssetBundle;

class AdminLTEAsset extends AssetBundle
{
    public $sourcePath = "@app/modules/man/assets/AdminLTE2";

    public $css =[
        'font-awesome/css/font-awesome.min.css',
        'dist/css/AdminLTE.min.css',
        'dist/css/skins/skin-blue.min.css'
    ];

    public $js =[
        'dist/js/app.min.js',
    ];

    public $depends = [
        'yii\bootstrap\BootstrapPluginAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

}