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
            Event::trigger(Module::className(), Module::EVENT_LOGIN_REQUIRED);
            return $this->redirect(['default/login']);
        }

        /* 获取所有模块man配置信息 */
        $modules = Module::getInstance()->moduleManager->getModules();
        $menus = [];
        foreach ($modules as $id => $m) {
            if ($m['manager'] !== false && isset($m['manager']['menu'])) {
                foreach ($m['manager']['menu']['items'] as $i => $item) {
                    if (isset($item['permission']) && !$this->getManager()->can($item['permission'])) {
                        unset($m['manager']['menu']['items'][$i]);
                    }
                }
                if(count($m['manager']['menu']['items'])){
                    $menus[$id] = $m['manager']['menu'];
                }
            }
        }
        return $this->render('index', ['menus' => $menus]);
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
     * 进入中控台后默认页面
     * @return string
     */
    public function actionInfo()
    {
        if ($this->getManager()->isGuest) {
            Event::trigger(Module::className(), Module::EVENT_LOGIN_REQUIRED);
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
}
