<?php

namespace app\modules\test;

use yii\base\Application;
use yii\base\BootstrapInterface;

class Module extends \app\base\Module implements BootstrapInterface
{
    public $controllerNamespace = 'app\modules\i\controllers';

    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }

    /**
     * Bootstrap method to be called during application bootstrap stage.
     * @param Application $app the application currently running
     */
    public function bootstrap($app)
    {
       \Yii::trace('预加载模块！',__METHOD__);
    }
}
