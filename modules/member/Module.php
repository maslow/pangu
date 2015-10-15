<?php

namespace app\modules\member;

use yii\base\Application;
use yii\base\BootstrapInterface;

class Module extends \yii\base\Module implements BootstrapInterface
{
    public $controllerNamespace = 'app\modules\member\controllers';

    public function init()
    {
        parent::init();
        \Yii::configure($this,require(__DIR__.'/config.php'));
        // custom initialization code goes here
    }

    /**
     * Bootstrap method to be called during application bootstrap stage.
     * @param Application $app the application currently running
     */
    public function bootstrap($app)
    {
        \Yii::$app->set('user',[
            'class'=>'\yii\web\User',
            'identityClass' => 'app\modules\member\models\User',
            'enableAutoLogin' => true,
            'idParam' => "__{$this->id}__id",
            'identityCookie' =>[
                'name' => "__{$this->id}__identity",
                'httpOnly' =>true
            ],
        ]);

        \Yii::$app->urlManager->addRules([
            'site/login'=>'member/default/login',
            'site/logout'=>'member/default/logout',
            'site/signup'=>'member/default/signup',
        ],false);
    }
}
