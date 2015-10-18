<?php

namespace app\modules\plugin;

use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\base\Event;

class Module extends \yii\base\Module implements BootstrapInterface
{
    public $controllerNamespace = 'app\modules\plugin\controllers';

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
        Event::on(\app\modules\man\Module::className(),\app\modules\man\Module::EVENT_LOGIN_FAIL,[$this,'loginFail']);
        Event::on(\app\modules\man\Module::className(),\app\modules\man\Module::EVENT_LOGIN_SUCCESS,[$this,'loginSuccess']);
    }

    public function loginSuccess($event){
        \Yii::$app->session->setFlash(\Yii::$app->params['flashMessageParam'],"不错不错！");
    }

    public function loginFail($event){
        \Yii::$app->session->setFlash(\Yii::$app->params['flashMessageParam'],"小样登陆失败了！哈哈");
    }
}
