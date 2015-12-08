<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Class LayerAsset
 * @package app\assets
 */
class LayerAsset extends AssetBundle
{
    public $sourcePath = '@app/assets/layer2.1/layer';

    public $js = [
        'layer.js'
    ];

    public $depends = [
        'yii\web\JqueryAsset'
    ];
}