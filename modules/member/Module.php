<?php

namespace app\modules\member;

use yii\base\Application;
use yii\base\BootstrapInterface;

class Module extends \yii\base\Module implements BootstrapInterface
{
    const EVENT_BEFORE_LOGIN = 'beforeLogin';
    const EVENT_LOGIN_SUCCESS = 'loginSuccess';
    const EVENT_LOGIN_FAIL = 'loginFail';

    const EVENT_BEFORE_LOGOUT = 'beforeLogout';
    const EVENT_AFTER_LOGOUT = 'afterLogout';

    const EVENT_BEFORE_SIGNUP = 'beforeSignup';
    const EVENT_SIGNUP_SUCCESS = 'signupSuccess';
    const EVENT_SIGNUP_FAIL = 'signupFail';


    const EVENT_BEFORE_CREATE_USER = 'beforeCreateUser';
    const EVENT_CREATE_USER_SUCCESS = 'createUserSuccess';
    const EVENT_CREATE_USER_FAIL ='createUserFail';

    const EVENT_BEFORE_UPDATE_USER = 'beforeUpdateUser';
    const EVENT_UPDATE_USER_SUCCESS = 'updateUserSuccess';
    const EVENT_UPDATE_USER_FAIL = 'updateUserFail';

    const EVENT_BEFORE_DELETE_USER = 'beforeDeleteUser';
    const EVENT_DELETE_USER_SUCCESS= 'deleteUserSuccess';
    const EVENT_DELETE_USER_FAIL = 'deleteUserFail';

    const EVENT_PERMISSION_REQUIRED = 'permissionRequired';

    public $controllerNamespace = 'app\modules\member\controllers';

    public function init()
    {
        parent::init();
        \Yii::configure($this,require(__DIR__.'/config.php'));
        // custom initialization code goes here
        $this->registerTranslations();
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

    protected function registerTranslations()
    {
        \Yii::$app->i18n->translations['member'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@app/modules/member/messages',
            'fileMap' => [
                'member' => 'member.php',
            ],
        ];
    }
}
