<?php

namespace app\modules\man;

use app\modules\man\models\Manager;
use yii\base\Application;
use yii\base\BootstrapInterface;

class Module extends \yii\base\Module implements BootstrapInterface
{

    const EVENT_LOGIN_FAIL = 'login.fail';
    const EVENT_LOGIN_SUCCESS = 'login.success';

    public $controllerNamespace = 'app\modules\man\controllers';

    public function init()
    {
        parent::init();
        \Yii::configure($this, require(__DIR__ . '/config.php'));
        // custom initialization code goes here
    }

    /**
     * Bootstrap method to be called during application bootstrap stage.
     * @param Application $app the application currently running
     */
    public function bootstrap($app)
    {
        \Yii::$app->set('manager',[
            'class'=>'\yii\web\User',
            'identityClass'=>Manager::className(),
            'enableAutoLogin' => false,
            'idParam' => "__{$this->id}__id",
            'identityCookie' =>[
                'name' => "__{$this->id}__identity",
                'httpOnly' =>true
            ],
        ]);
    }
}
