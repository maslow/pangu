<?php

namespace app\modules\backend\controllers;

use app\modules\backend\models\LoginForm;
use app\modules\backend\Module;
use app\base\Event;
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
            Event::trigger(Module::className(), Module::EVENT_LOGIN_REQUIRED);
            return $this->redirect(['default/login']);
        }

        $menu = Module::getInstance()->moduleManager->getMenu();
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
        Event::trigger(Module::className(), Module::EVENT_BEFORE_LOGIN);
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
    public function actionLogout()
    {
        Event::trigger(Module::className(), Module::EVENT_BEFORE_LOGOUT);
        $this->getManager()->logout(false);
        Event::trigger(Module::className(), Module::EVENT_AFTER_LOGOUT);
        return $this->redirect(['default/login']);
    }

    /**
     * 获取管理员(manager)组件对象
     * @return \yii\web\User
     */
    protected function getManager()
    {
        return \Yii::$app->manager;
    }
}
