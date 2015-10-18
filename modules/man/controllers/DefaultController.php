<?php

namespace app\modules\man\controllers;

use app\modules\man\models\LoginForm;
use app\modules\man\Module;
use yii\base\Event;
use yii\web\Controller;

class DefaultController extends Controller
{
    public $layout = 'main';

    /**
     * 显示中央控制台主面板
     * @return string
     */
    public function actionIndex()
    {
        if ($this->getManager()->isGuest) {
            Event::trigger(Module::className(),Module::EVENT_LOGIN_REQUIRED);
            $this->sendFlashMessage("请登录后再进行操作或者您的登录已过期！");
            return $this->redirect(['default/login']);
        }

        /* 获取所有模块man配置信息 */
        $modules = Module::getInstance()->moduleManager->getModules();
        $menu = [];
        foreach ($modules as $id => $m) {
            if ($m['man'] !== false) {
                $menu[$id] = $m['man'];
            }
        }
        return $this->render('index', ['menu' => $menu]);
    }

    /**
     * 中央控制台登录入口
     * @return string|\yii\web\Response
     */
    public function actionLogin()
    {
        if (!$this->getManager()->isGuest) {
            return $this->redirect(['default/index']);
        }
        $model = new LoginForm();
        if ($model->load(\Yii::$app->request->post()) && $model->login()) {
            return $this->redirect(['default/index']);
        }
        return $this->render('login', ['model' => $model]);
    }

    /**
     * 退出登录中央控制台
     * @return \yii\web\Response
     */
    public function actionLogout(){
        Event::trigger(Module::className(),Module::EVENT_BEFORE_LOGOUT);
        $this->getManager()->logout(false);
        $this->sendFlashMessage("您已安全退出，再见！");
        Event::trigger(Module::className(),Module::EVENT_AFTER_LOGOUT);
        return $this->redirect(['default/login']);
    }

    /**
     * 进入中控台后默认页面
     * @return string
     */
    public function actionInfo(){
        if ($this->getManager()->isGuest) {
            Event::trigger(Module::className(),Module::EVENT_LOGIN_REQUIRED);
            $this->sendFlashMessage("请登录后再进行操作或者您的登录已过期！");
            return $this->redirect(['default/login']);
        }
        return $this->render('info');
    }

    /**
     * 获取管理员(manager)组件对象
     * @return \yii\web\User
     */
    protected function getManager()
    {
        return \Yii::$app->manager;
    }

    /**
     * 发送通知信息到下一个请求页面
     * @param $message string 要发送的信息
     */
    protected function sendFlashMessage($message){
        //\Yii::$app->session->setFlash(\Yii::$app->params['flashMessageParam'], $message);
    }
}
