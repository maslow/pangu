<?php

namespace app\modules\backend\controllers;

use app\modules\backend\models\LoginForm;
use app\modules\backend\Module;
use yii\base\Event;
use yii\captcha\CaptchaAction;
use yii\web\Controller;
use yii\web\Cookie;

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
     * 切换系统显示语言
     * @param $lang
     * @return \yii\web\Response
     */
    public function actionSetLanguage($lang)
    {
        \Yii::$app->response->cookies->add(new Cookie([
            'name' => '__lang',
            'value' => $lang,
            'expire' => time() + 3600 * 24,
        ]));
        return $this->redirect(['/backend']);
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