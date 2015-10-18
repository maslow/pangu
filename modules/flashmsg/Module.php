<?php

namespace app\modules\flashmsg;

use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\base\Event;
use \app\modules\man\Module as ManModule;

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
        Event::on(ManModule::className(), ManModule::EVENT_LOGIN_FAIL, $handler, "登录失败，请重试！");

        Event::on(ManModule::className(), ManModule::EVENT_LOGIN_SUCCESS, $handler, "登录成功！");

        Event::on(ManModule::className(), ManModule::EVENT_LOGIN_REQUIRED, $handler, "请登录后再进行操作或者您的登录已过期!");

        Event::on(ManModule::className(), ManModule::EVENT_AFTER_LOGOUT, $handler, "您已安全退出，再见!");

        Event::on(ManModule::className(), ManModule::EVENT_CREATE_MANAGER_SUCCESS, $handler, "创建管理员成功！");

        Event::on(ManModule::className(), ManModule::EVENT_CREATE_MANAGER_FAIL, $handler, "创建管理员失败！");

        Event::on(ManModule::className(), ManModule::EVENT_UPDATE_MANAGER_SUCCESS, $handler, "更新管理员成功！");

        Event::on(ManModule::className(), ManModule::EVENT_UPDATE_MANAGER_FAIL, $handler, "更新管理员失败！");

        Event::on(ManModule::className(), ManModule::EVENT_DELETE_MANAGER_SUCCESS, $handler, "删除管理员成功！");

        Event::on(ManModule::className(), ManModule::EVENT_DELETE_MANAGER_FAIL, $handler, "删除管理员失败！");

        Event::on(ManModule::className(), ManModule::EVENT_PERMISSION_REQUIRED, [$this,'permissionRequired']);

    }

    /**
     * @param $event \yii\base\Event
     */
    public function sendFlashMessage($event)
    {
        \Yii::$app->session->setFlash(\Yii::$app->params['flashMessageParam'], $event->data);
    }

    public function permissionRequired($event){
        \Yii::$app->session->setFlash(\Yii::$app->params['flashMessageParam'], "您没有进行该操作的权限[{$event->permission}]！");
    }

}
