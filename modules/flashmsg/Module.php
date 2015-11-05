<?php

namespace app\modules\flashmsg;

use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\base\Event;
use app\modules\man\Module as ManModule;
use app\modules\member\Module as MemberModule;
USE app\modules\rbac\Module as RbacModule;

class Module extends \yii\base\Module implements BootstrapInterface
{
    public $controllerNamespace = 'app\modules\flashmsg\controllers';

    public function init()
    {
        parent::init();

        // custom initialization code goes here
        $this->registerTranslations();
    }

    /**
     * Bootstrap method to be called during application bootstrap stage.
     * @param Application $app the application currently running
     */
    public function bootstrap($app)
    {
        $handler = [$this, 'sendFlashMessage'];
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
            //ManModule::EVENT_LOGIN_FAIL => \Yii::t('flashmsg', 'Failed to login, please try again.'),
            //ManModule::EVENT_LOGIN_REQUIRED => \Yii::t('flashmsg', 'Please login to continue your operation or your session is timeout.'),
            //ManModule::EVENT_AFTER_LOGOUT => \Yii::t('flashmsg', 'You have been logged-out, bye!'),
            ManModule::EVENT_CREATE_MANAGER_SUCCESS => \Yii::t('flashmsg', 'Success to create manager!'),
            ManModule::EVENT_CREATE_MANAGER_FAIL => \Yii::t('flashmsg', 'Failed to create manager!'),
            ManModule::EVENT_UPDATE_MANAGER_SUCCESS => \Yii::t('flashmsg', 'Success to update manager!'),
            ManModule::EVENT_UPDATE_MANAGER_FAIL => \Yii::t('flashmsg', 'Failed to update manager!'),
            ManModule::EVENT_DELETE_MANAGER_SUCCESS => \Yii::t('flashmsg', 'Success to delete manager!'),
            ManModule::EVENT_DELETE_MANAGER_FAIL => \Yii::t('flashmsg', 'Failed to delete manager!'),
        ];
    }

    /**
     * 返回member模块的事件与通知信息数据
     * @return array
     */
    protected function memberMessages()
    {
        return [
            MemberModule::EVENT_LOGIN_SUCCESS => "登录成功!",
            MemberModule::EVENT_LOGIN_FAIL => "登录失败，请重试！",
            MemberModule::EVENT_AFTER_LOGOUT => "您已安全退出，再见!",
            MemberModule::EVENT_SIGNUP_SUCCESS => "注册成功!",
            MemberModule::EVENT_SIGNUP_FAIL => "注册失败!",

            MemberModule::EVENT_CREATE_USER_SUCCESS => "创建用户成功！",
            MemberModule::EVENT_CREATE_USER_FAIL => "创建用户失败！",
            MemberModule::EVENT_UPDATE_USER_SUCCESS => "更新用户成功！",
            MemberModule::EVENT_UPDATE_USER_FAIL => "更新用户失败！",
            MemberModule::EVENT_DELETE_USER_SUCCESS => "删除用户成功！",
            MemberModule::EVENT_DELETE_USER_FAIL => "删除用户失败！",
        ];
    }

    /**
     * 返回rbac模块的事件与通知信息数据
     * @return array
     */
    protected function rbacMessages()
    {
        return [
            RbacModule::EVENT_CREATE_ROLE_SUCCESS => \Yii::t('flashmsg', 'Create role successfully!'),
            RbacModule::EVENT_CREATE_ROLE_FAIL => "创建角色失败！",
            RbacModule::EVENT_UPDATE_ROLE_SUCCESS => "更新角色成功！",
            RbacModule::EVENT_UPDATE_ROLE_FAIL => "更新角色失败！",
            RbacModule::EVENT_DELETE_ROLE_SUCCESS => "删除角色成功！",
            RbacModule::EVENT_DELETE_ROLE_FAIL => "删除角色失败！",
        ];
    }

    /**
     * @param $event \yii\base\Event
     */
    public function sendFlashMessage($event)
    {
        \Yii::$app->session->setFlash(\Yii::$app->params['flashMessageParam'], $event->data);
    }

    /**
     * @param $event
     */
    public function permissionRequired($event)
    {
        \Yii::$app->session->setFlash(\Yii::$app->params['flashMessageParam'], \Yii::t('flashmsg', 'You have no permission with the operation : {permission}', ['permission' => $event->permission]));
    }

    protected function registerTranslations()
    {
        \Yii::$app->i18n->translations['flashmsg'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@app/modules/flashmsg/messages',
            'fileMap' => [
                'flashmsg' => 'flashmsg.php',
            ],
        ];
    }
}
