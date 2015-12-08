<?php

namespace app\modules\backend;

use app\modules\backend\models\Manager;
use yii\base\Application;
use yii\base\BootstrapInterface;

/**
 * Class Module
 * @property \app\modules\ModuleManager $moduleManager
 * @package app\modules\backend
 */
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

    const EVENT_RESET_PASSWORD_SUCCESS = "resetPasswordSuccess";
    const EVENT_RESET_PASSWORD_FAIL = "resetPasswordSuccess";


    public $controllerNamespace = 'app\modules\backend\controllers';

    public function init()
    {
        parent::init();
        \Yii::configure($this, require(__DIR__ . '/config.php'));
    }

    /**
     * Bootstrap method to be called during application bootstrap stage.
     * @param Application $app the application currently running
     */
    public function bootstrap($app)
    {
        \Yii::$app->set('manager', [
            'class' => '\yii\web\User',
            'identityClass' => Manager::className(),
            'enableAutoLogin' => false,
            'idParam' => "__{$this->id}__id",
            'identityCookie' => [
                'name' => "__{$this->id}__identity",
                'httpOnly' => true
            ],
        ]);
    }
}
