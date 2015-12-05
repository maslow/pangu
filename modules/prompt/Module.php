<?php

namespace app\modules\prompt;

use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\base\Event;
use app\modules\man\Module as ManModule;
use app\modules\member\Module as MemberModule;
USE app\modules\rbac\Module as RbacModule;

class Module extends \yii\base\Module implements BootstrapInterface
{
    public $controllerNamespace = 'app\modules\prompt\controllers';

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
        $handler = [$this, 'prompt'];
        // 绑定man模块的事件
        $manMessages = $this->manMessages();
        foreach ($manMessages as $e => $m) {
            Event::on(ManModule::className(), $e, $handler, $m);
        }
        // 绑定man模块的事件
        $memberMessages = $this->memberMessages();
        foreach ($memberMessages as $e => $m) {
            Event::on(MemberModule::className(), $e, $handler, $m);
        }
        // 绑定man模块的事件
        $rbacMessages = $this->rbacMessages();
        foreach ($rbacMessages as $e => $m) {
            Event::on(RbacModule::className(), $e, $handler, $m);
        }

        Event::on(ManModule::className(), ManModule::EVENT_PERMISSION_REQUIRED, [$this, 'permissionRequired']);
        Event::on(MemberModule::className(), MemberModule::EVENT_PERMISSION_REQUIRED, [$this, 'permissionRequired']);
        Event::on(RbacModule::className(), RbacModule::EVENT_PERMISSION_REQUIRED, [$this, 'permissionRequired']);
    }

    /**
     * 返回man模块的事件与通知信息数据
     * @return array
     */
    protected function manMessages()
    {
        return [
            ManModule::EVENT_CREATE_MANAGER_SUCCESS => \Yii::t('prompt', 'Success to create manager!'),
            ManModule::EVENT_CREATE_MANAGER_FAIL => \Yii::t('prompt', 'Failed to create manager!'),
            ManModule::EVENT_UPDATE_MANAGER_SUCCESS => \Yii::t('prompt', 'Success to update manager!'),
            ManModule::EVENT_UPDATE_MANAGER_FAIL => \Yii::t('prompt', 'Failed to update manager!'),
            ManModule::EVENT_DELETE_MANAGER_SUCCESS => \Yii::t('prompt', 'Success to delete manager!'),
            ManModule::EVENT_DELETE_MANAGER_FAIL => \Yii::t('prompt', 'Failed to delete manager!'),
            ManModule::EVENT_RESET_PASSWORD_FAIL => \Yii::t('prompt', 'Reset password failed!'),
            ManModule::EVENT_RESET_PASSWORD_SUCCESS => \Yii::t('prompt', 'Reset password successfully!'),
        ];
    }

    /**
     * 返回member模块的事件与通知信息数据
     * @return array
     */
    protected function memberMessages()
    {
        return [
            MemberModule::EVENT_LOGIN_SUCCESS => \Yii::t('prompt', 'Success to login!'),
            MemberModule::EVENT_LOGIN_FAIL => \Yii::t('prompt', 'Failed to login, please try again.'),
            MemberModule::EVENT_AFTER_LOGOUT => \Yii::t('prompt', 'You have been logged-out, bye!'),
            MemberModule::EVENT_SIGNUP_SUCCESS => \Yii::t('prompt', 'Success to sign up!'),
            MemberModule::EVENT_SIGNUP_FAIL => \Yii::t('prompt', 'Failed to sign up!'),

            MemberModule::EVENT_CREATE_USER_SUCCESS => \Yii::t('prompt', 'Success to create user!'),
            MemberModule::EVENT_CREATE_USER_FAIL => \Yii::t('prompt', 'Failed to create user!'),
            MemberModule::EVENT_UPDATE_USER_SUCCESS => \Yii::t('prompt', 'Success to update user!'),
            MemberModule::EVENT_UPDATE_USER_FAIL => \Yii::t('prompt', 'Failed to update user!'),
            MemberModule::EVENT_DELETE_USER_SUCCESS => \Yii::t('prompt', 'Success to delete user!'),
            MemberModule::EVENT_DELETE_USER_FAIL => \Yii::t('prompt', 'Failed to delete user!'),
        ];
    }

    /**
     * 返回rbac模块的事件与通知信息数据
     * @return array
     */
    protected function rbacMessages()
    {
        return [
            RbacModule::EVENT_CREATE_ROLE_SUCCESS => \Yii::t('prompt', 'Create role successfully!'),
            RbacModule::EVENT_CREATE_ROLE_FAIL => \Yii::t('prompt', 'Create role failed!'),
            RbacModule::EVENT_UPDATE_ROLE_SUCCESS => \Yii::t('prompt', 'Update role successfully!'),
            RbacModule::EVENT_UPDATE_ROLE_FAIL => \Yii::t('prompt', 'Update role failed!'),
            RbacModule::EVENT_DELETE_ROLE_SUCCESS => \Yii::t('prompt', 'Delete role successfully!'),
            RbacModule::EVENT_DELETE_ROLE_FAIL => \Yii::t('prompt', 'Delete role failed!'),
        ];
    }

    /**
     * @param $event \yii\base\Event
     */
    public function prompt($event)
    {
        if (isset($event->error) && $event->error) {
            \Yii::$app->session->setFlash(\Yii::$app->params['flashMessageParam'], $event->error);
        } else {
            \Yii::$app->session->setFlash(\Yii::$app->params['flashMessageParam'], $event->data);
        }
    }

    /**
     * @param $event
     */
    public function permissionRequired($event)
    {
        \Yii::$app->session->setFlash(
            \Yii::$app->params['flashMessageParam'],
            \Yii::t('prompt', 'You have no permission with the operation : {permission}', ['permission' => $event->permission])
        );
    }
}
