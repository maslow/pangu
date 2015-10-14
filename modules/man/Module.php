<?php

namespace app\modules\man;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\man\controllers';

    public function init()
    {
        parent::init();
        \Yii::configure($this, require(__DIR__ . '/config.php'));
        // custom initialization code goes here
    }
}
