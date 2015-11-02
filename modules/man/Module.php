<?php

namespace app\modules\man;

use app\modules\man\models\Manager;
use yii\base\Application;
use yii\base\BootstrapInterface;

class Module extends \yii\base\Module implements BootstrapInterface
{

    const EVENT_BEFORE_LOGIN = 'beforeLogin';
    const EVENT_LOGIN_FAIL = 'loginFail';
    const EVENT_LOGIN_SUCCESS = 'loginSuccess';

    const EVENT_BEFORE_LOGOUT = 'beforeLogout';
    const EVENT_AFTER_LOGOUT = 'afterLogout';

    const EVENT_LOGIN_REQUIRED = 'loginRequired';

    const EVENT_PERMISSION_REQUIRED = 'permissionRequired';

    const EVENT_CREATE_MANAGER_SUCCESS = "createManagerSuccess";
    const EVENT_CREATE_MANAGER_FAIL = "createManagerFail";

    const EVENT_UPDATE_MANAGER_SUCCESS = "updateManagerSuccess";
    const EVENT_UPDATE_MANAGER_FAIL = "updateManagerFail";

    const EVENT_DELETE_MANAGER_SUCCESS = "deleteManagerSuccess";
    const EVENT_DELETE_MANAGER_FAIL = "deleteManagerFail";


    public $controllerNamespace = 'app\modules\man\controllers';

    public function init()
    {
        parent::init();
        \Yii::configure($this, require(__DIR__ . '/config.php'));

        $this->registerTranslations();
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

    protected function registerTranslations()
    {
        \Yii::$app->i18n->translations['man'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@app/modules/man/messages',
            'fileMap' => [
                'man' => 'man.php',
            ],
        ];
    }
}
