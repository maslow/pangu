<?php

namespace app\modules\flashmsg;

use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\base\Event;
use \app\modules\man\Module as ManModule;
use app\modules\member\Module as MemberModule;

class Module extends \yii\base\Module implements BootstrapInterface
{
    public $controllerNamespace = 'app\modules\flashmsg\controllers';

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

        Event::on(ManModule::className(), ManModule::EVENT_PERMISSION_REQUIRED, [$this, 'permissionRequired']);
        Event::on(MemberModule::className(), MemberModule::EVENT_PERMISSION_REQUIRED, [$this, 'permissionRequired']);
    }

    /**
     * 返回man模块的事件与通知信息数据
     * @return array
     */
    protected function manMessages()
    {
        return [
            ManModule::EVENT_LOGIN_FAIL => "登录失败，请重试！",
            ManModule::EVENT_LOGIN_REQUIRED => "请登录后再进行操作或者您的登录已过期!",
            ManModule::EVENT_AFTER_LOGOUT => "您已安全退出，再见!",
            ManModule::EVENT_CREATE_MANAGER_SUCCESS => "创建管理员成功！",
            ManModule::EVENT_CREATE_MANAGER_FAIL => "创建管理员失败！",
            ManModule::EVENT_UPDATE_MANAGER_SUCCESS => "更新管理员成功！",
            ManModule::EVENT_UPDATE_MANAGER_FAIL => "更新管理员失败！",
            ManModule::EVENT_DELETE_MANAGER_SUCCESS => "删除管理员成功！",
            ManModule::EVENT_DELETE_MANAGER_FAIL => "删除管理员失败！",
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
        \Yii::$app->session->setFlash(\Yii::$app->params['flashMessageParam'], "您没有进行该操作的权限[{$event->permission}]！");
    }

}
